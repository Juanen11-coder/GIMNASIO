<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Musculo;
use App\Models\Message;
use App\Models\Like;
use App\Models\EjercicioPredefinido;
use App\Models\DetalleEntrenamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Friendship;

class SocialController extends Controller
{
    /**
     * Lee un archivo JSON y lo devuelve como array
     */
    private function readJson($filename)
    {
        $path = storage_path('app/public/data/' . $filename);
        if (!file_exists($path)) {
            return [];
        }
        $content = file_get_contents($path);
        return json_decode($content, true);
    }

    /**
     * Escribe un array en un archivo JSON
     */
    private function writeJson($filename, $data)
    {
        $path = storage_path('app/public/data/' . $filename);
        $result = file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));

        if ($result === false) {
            throw new \Exception("No se pudo guardar el archivo: $filename");
        }
    }

    /**
     * Muestra el feed de publicaciones de los amigos
     */
    public function feed()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Obtener IDs de los amigos aceptados
        $friendIds = Friendship::where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('friend_id', $user->id);
        })->where('status', 'accepted')
          ->get()
          ->map(function($friendship) use ($user) {
              return $friendship->user_id == $user->id ? $friendship->friend_id : $friendship->user_id;
          });

        // Si no tiene amigos, mostrar mensaje
        if ($friendIds->isEmpty()) {
            $posts = collect();
        } else {
            // Obtener posts de los amigos (incluyendo el propio si quiere)
            $posts = Post::with('user', 'detalles.musculo')
                ->withCount('likes')
                ->whereIn('user_id', $friendIds)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $musculos = Musculo::all();

        return view('social.feed', compact('posts', 'musculos'));
    }

    /**
     * Muestra el perfil de un usuario
     */
    public function perfil($id)
    {
        $user = User::findOrFail($id);

        $userPosts = Post::with('detalles.musculo')
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_posts' => $userPosts->count(),
            'total_likes' => $userPosts->sum('likes'),
            'total_comments' => $userPosts->sum('comments_count'),
        ];

        return view('social.perfil', compact('user', 'userPosts', 'stats'));
    }

    public function editProfile()
    {
        return view('social.profile-edit', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'fitness_goal' => 'nullable|string|max:255',
            'fitness_level' => 'nullable|in:principiante,intermedio,avanzado',
            'height_cm' => 'nullable|integer|min:80|max:250',
            'weight_kg' => 'nullable|numeric|min:20|max:300',
        ]);

        $user->update($data);

        return redirect()->route('perfil.show', Auth::id())->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Muestra la lista de conversaciones del usuario
     */
    public function chats()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $currentUserId = Auth::id();

        $conversations = Message::where('sender_id', $currentUserId)
            ->orWhere('receiver_id', $currentUserId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) use ($currentUserId) {
                return $message->sender_id == $currentUserId ? $message->receiver_id : $message->sender_id;
            })
            ->map(function($messages, $friendId) use ($currentUserId) {
                $friend = $messages->first()->sender_id == $currentUserId
                    ? $messages->first()->receiver
                    : $messages->first()->sender;

                $lastMessage = $messages->first();
                $unreadCount = $messages->where('receiver_id', $currentUserId)->whereNull('read_at')->count();

                return [
                    'friend' => $friend,
                    'last_message' => $lastMessage,
                    'unread_count' => $unreadCount,
                    'updated_at' => $lastMessage->created_at
                ];
            })
            ->sortByDesc('updated_at')
            ->values();

        return view('social.chats', compact('conversations'));
    }

    /**
     * Muestra una conversación específica
     */
    public function chat($friendId)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $currentUser = Auth::user();
    $friend = User::findOrFail($friendId);

    // Verificar amistad
    $isFriend = Friendship::where(function($query) use ($currentUser, $friend) {
        $query->where('user_id', $currentUser->id)->where('friend_id', $friend->id);
    })->orWhere(function($query) use ($currentUser, $friend) {
        $query->where('user_id', $friend->id)->where('friend_id', $currentUser->id);
    })->where('status', 'accepted')->exists();

    if (!$isFriend) {
        return redirect()->route('chats.index')->with('error', 'Solo puedes chatear con amigos.');
    }

    // Obtener mensajes
    $messages = Message::where(function($query) use ($currentUser, $friend) {
        $query->where('sender_id', $currentUser->id)->where('receiver_id', $friend->id);
    })->orWhere(function($query) use ($currentUser, $friend) {
        $query->where('sender_id', $friend->id)->where('receiver_id', $currentUser->id);
    })->with(['sender', 'receiver'])
      ->orderBy('created_at', 'asc')
      ->get();

    // Marcar mensajes como leídos
    Message::where('sender_id', $friend->id)
        ->where('receiver_id', $currentUser->id)
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

    // Preparar datos para la vista
    $otherUser = [
        'id' => $friend->id,
        'name' => $friend->name,
        'avatar' => $friend->avatar ?? 'https://ui-avatars.com/api/?background=6366f1&color=fff&name=' . urlencode($friend->name)
    ];

    $conversationId = $friendId; // ← ESTA ES LA LÍNEA CLAVE

    return view('social.chat', compact('otherUser', 'messages', 'conversationId'));
}

    /**
     * Envía un mensaje en una conversación
     */
    public function sendMessage(Request $request, $friendId)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $currentUser = Auth::user();
    $friend = User::findOrFail($friendId);

    if (!$friend) {
        return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
    }

    // Verificar amistad DIRECTAMENTE (evitando el método)
    $isFriend = Friendship::where(function($query) use ($currentUser, $friend) {
        $query->where('user_id', $currentUser->id)->where('friend_id', $friend->id);
    })->orWhere(function($query) use ($currentUser, $friend) {
        $query->where('user_id', $friend->id)->where('friend_id', $currentUser->id);
    })->where('status', 'accepted')->exists();

    if (!$isFriend) {
        return redirect()->route('chat.show', $friendId)->with('error', 'Solo puedes enviar mensajes a amigos.');
    }

    $request->validate([
        'message' => 'required|string|max:1000'
    ]);

    Log::info('SendMessage called', [
        'sender_id' => $currentUser->id,
        'receiver_id' => $friendId,
        'message' => $request->message
    ]);

    // Crear el mensaje
    $message = Message::create([
        'sender_id' => $currentUser->id,
        'receiver_id' => $friendId,
        'message' => $request->message
    ]);

    Log::info('Message created', ['message_id' => $message->id]);

    return redirect()->route('chat.show', $friendId)
        ->with('success', 'Mensaje enviado correctamente');
}

    /**
     * Crea una nueva publicación
     */
    public function createPost(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'content' => 'nullable|string|max:1000',
            'ejercicios' => 'required|array|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Subir imagen si existe
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        // Crear el post
        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content ?? 'Entrenamiento completado 💪',
            'image' => $imagePath,
            'likes' => 0,
            'comments_count' => 0,
        ]);

        // Guardar los ejercicios
        foreach ($request->ejercicios as $ejercicioData) {
            if (isset($ejercicioData['ejercicio_otro']) && $ejercicioData['ejercicio_otro']) {
                $nombreEjercicio = $ejercicioData['ejercicio_otro'];
            } elseif (isset($ejercicioData['ejercicio_id']) && $ejercicioData['ejercicio_id']) {
                $ejercicioPredefinido = EjercicioPredefinido::find($ejercicioData['ejercicio_id']);
                $nombreEjercicio = $ejercicioPredefinido ? $ejercicioPredefinido->nombre : 'Ejercicio';
            } else {
                $nombreEjercicio = 'Ejercicio';
            }

            if (isset($ejercicioData['musculo_otro']) && $ejercicioData['musculo_otro']) {
                $musculo = Musculo::firstOrCreate(['nombre' => $ejercicioData['musculo_otro']]);
                $musculoId = $musculo->id;
            } else {
                $musculoId = $ejercicioData['musculo_id'] ?? null;
            }

            if ($musculoId) {
                $post->detalles()->create([
                    'musculo_id' => $musculoId,
                    'ejercicio' => $nombreEjercicio,
                    'series' => $ejercicioData['series'] ?? null,
                    'repeticiones' => $ejercicioData['repeticiones'] ?? null,
                    'peso' => $ejercicioData['peso'] ?? null,
                ]);
            }
        }

        return redirect()->route('feed')->with('success', '¡Entrenamiento publicado!');
    }

    /**
     * Elimina una publicación
     */
    public function deletePost(Post $post)
    {
        if ($post->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $post->detalles()->delete();
        $post->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Marcar o desmarcar like en una publicación
     */
    public function toggleLike(Post $post)
    {
        Log::info('Entrando a toggleLike para post: ' . $post->id);

        if (!Auth::check()) {
            Log::info('Usuario no autenticado');
            return response()->json(['success' => false, 'message' => 'No autorizado'], 401);
        }

        Log::info('Usuario autenticado: ' . Auth::id());

        $existingLike = Like::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->first();

        if ($existingLike) {
            Log::info('Eliminando like existente');
            $existingLike->delete();
            $liked = false;
        } else {
            Log::info('Creando nuevo like');
            Like::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
            ]);
            $liked = true;
        }

        $likesCount = $post->likes()->count();
        Log::info('Likes count: ' . $likesCount);

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $likesCount
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Musculo;
use App\Models\EjercicioPredefinido;
use App\Models\DetalleEntrenamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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
     * Muestra el feed de publicaciones (solo posts del usuario logueado)
     */
    public function feed()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $posts = Post::with('user', 'detalles.musculo')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $musculos = Musculo::all();

        return view('social.feed', compact('posts', 'musculos'));
    }

    /**
     * Muestra el perfil de un usuario
     */
    public function perfil($id)
    {
        // Buscar usuario en la BD, no en JSON
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

    /**
     * Muestra la lista de conversaciones del usuario
     */
    public function chats()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $currentUserId = auth()->id();

        // Obtener conversaciones de la BD (cuando estén implementadas)
        // Por ahora, como no tienes chats en BD, puedes mostrarlos vacíos

        $myConversations = []; // Temporal

        return view('social.chats', compact('myConversations'));
    }

    /**
     * Muestra una conversación específica
     */
    public function chat($conversationId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $currentUserId = auth()->id();

        $chatsData = $this->readJson('chats.json');
        $users = $this->readJson('users.json');

        $usersById = [];
        foreach ($users as $user) {
            $usersById[$user['id']] = $user;
        }

        $conversation = null;
        foreach ($chatsData['conversations'] as $conv) {
            if ($conv['id'] == $conversationId) {
                $conversation = $conv;
                break;
            }
        }

        if (!$conversation) {
            abort(404, 'Conversación no encontrada');
        }

        if (!in_array($currentUserId, $conversation['participants'])) {
            abort(403, 'No tienes acceso a esta conversación');
        }

        $messages = array_filter($chatsData['messages'], function ($msg) use ($conversationId) {
            return $msg['conversation_id'] == $conversationId;
        });

        usort($messages, function ($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });

        foreach ($messages as &$msg) {
            $msg['user'] = $usersById[$msg['user_id']] ?? null;
        }

        $otherUserId = null;
        foreach ($conversation['participants'] as $pId) {
            if ($pId != $currentUserId) {
                $otherUserId = $pId;
                break;
            }
        }
        $otherUser = $usersById[$otherUserId] ?? null;

        return view('social.chat', compact('conversation', 'messages', 'otherUser'));
    }

    /**
     * Envía un mensaje en una conversación
     */
    public function sendMessage(Request $request, $conversationId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $currentUserId = auth()->id();

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $chatsData = $this->readJson('chats.json');

        $messages = $chatsData['messages'] ?? [];
        $maxId = empty($messages) ? 0 : max(array_column($messages, 'id'));

        $newMessage = [
            'id' => $maxId + 1,
            'conversation_id' => (int) $conversationId,
            'user_id' => $currentUserId,
            'message' => $request->message,
            'created_at' => date('c')
        ];

        $chatsData['messages'][] = $newMessage;

        foreach ($chatsData['conversations'] as &$conv) {
            if ($conv['id'] == $conversationId) {
                $conv['last_message'] = $request->message;
                $conv['last_message_time'] = date('c');
                break;
            }
        }

        $this->writeJson('chats.json', $chatsData);

        return redirect()->route('chat.show', $conversationId)
            ->with('success', 'Mensaje enviado');
    }

    /**
     * Crea una nueva publicación
     */
    /**
     * Crea una nueva publicación
     */
public function createPost(Request $request)
{

    if (!auth()->check()) {
        return redirect()->route('login');
    }

    // Depuración - ver qué está llegando
    \Log::info('Datos del formulario:', $request->all());
    \Log::info('Archivos:', $request->files->all());

    $validator = validator($request->all(), [
        'content' => 'nullable|string|max:1000',
        'ejercicios' => 'required|array|min:1',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        \Log::error('Error de validación:', $validator->errors()->all());
        return back()->withErrors($validator)->withInput();
    }

    // ... resto del código

    if (!auth()->check()) {
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
        'user_id' => auth()->id(),
        'content' => $request->content ?? 'Entrenamiento completado 💪',
        'image' => $imagePath,
        'likes' => 0,
        'comments_count' => 0,
    ]);

    // Guardar los ejercicios
    foreach ($request->ejercicios as $ejercicioData) {
        // Determinar el nombre del ejercicio
        if (isset($ejercicioData['ejercicio_otro']) && $ejercicioData['ejercicio_otro']) {
            $nombreEjercicio = $ejercicioData['ejercicio_otro'];
        } elseif (isset($ejercicioData['ejercicio_id']) && $ejercicioData['ejercicio_id']) {
            $ejercicioPredefinido = EjercicioPredefinido::find($ejercicioData['ejercicio_id']);
            $nombreEjercicio = $ejercicioPredefinido ? $ejercicioPredefinido->nombre : 'Ejercicio';
        } else {
            $nombreEjercicio = 'Ejercicio';
        }

        // Determinar el ID del músculo
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
        // Verificar que el usuario es el dueño del post
        if ($post->user_id != auth()->id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        // Eliminar primero los detalles (ejercicios asociados)
        $post->detalles()->delete();

        // Eliminar el post
        $post->delete();

        return response()->json(['success' => true]);
    }
}

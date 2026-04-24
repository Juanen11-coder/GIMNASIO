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
        $user = User::findOrFail($id);
        $posts = Post::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_posts' => $posts->count(),
            'total_likes' => $posts->sum('likes'),
            'total_comments' => $posts->sum('comments_count'),
        ];

        return view('social.perfil', compact('user', 'posts', 'stats'));
    }

    /**
     * Muestra la lista de conversaciones del usuario
     */
    public function chats()
    {
        // Verificar que hay usuario logueado
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

        $conversations = $chatsData['conversations'] ?? [];

        // Filtrar conversaciones donde participa el usuario actual
        $myConversations = array_filter($conversations, function ($conv) use ($currentUserId) {
            return in_array($currentUserId, $conv['participants']);
        });

        // Añadir los datos del otro usuario a cada conversación
        foreach ($myConversations as &$conv) {
            $otherUserId = null;
            foreach ($conv['participants'] as $pId) {
                if ($pId != $currentUserId) {
                    $otherUserId = $pId;
                    break;
                }
            }
            $conv['other_user'] = $usersById[$otherUserId] ?? null;
        }
        unset($conv);

        return view('social.chats', compact('myConversations'));
    }

    /**
     * Muestra una conversación específica
     */
    public function chat($conversationId)
    {
        // Verificar que hay usuario logueado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $currentUserId = auth()->id();

        $chatsData = $this->readJson('chats.json');
        $users = $this->readJson('users.json');

        // Indexar usuarios por ID
        $usersById = [];
        foreach ($users as $user) {
            $usersById[$user['id']] = $user;
        }

        // Buscar la conversación por ID
        $conversation = null;
        foreach ($chatsData['conversations'] as $conv) {
            if ($conv['id'] == $conversationId) {
                $conversation = $conv;
                break;
            }
        }

        // Si no existe la conversación, error 404
        if (!$conversation) {
            abort(404, 'Conversación no encontrada');
        }

        // Verificar que el usuario actual está en la conversación
        if (!in_array($currentUserId, $conversation['participants'])) {
            abort(403, 'No tienes acceso a esta conversación');
        }

        // Filtrar mensajes de esta conversación
        $messages = array_filter($chatsData['messages'], function ($msg) use ($conversationId) {
            return $msg['conversation_id'] == $conversationId;
        });

        // Ordenar mensajes por fecha (más antiguos primero)
        usort($messages, function ($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });

        // Añadir datos del usuario a cada mensaje
        foreach ($messages as &$msg) {
            $msg['user'] = $usersById[$msg['user_id']] ?? null;
        }

        // Obtener el otro participante
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
        // Verificar que hay usuario logueado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $currentUserId = auth()->id();

        // Validar que el mensaje no esté vacío
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $chatsData = $this->readJson('chats.json');

        // Calcular el nuevo ID del mensaje
        $messages = $chatsData['messages'] ?? [];
        $maxId = empty($messages) ? 0 : max(array_column($messages, 'id'));

        // Crear nuevo mensaje
        $newMessage = [
            'id' => $maxId + 1,
            'conversation_id' => (int) $conversationId,
            'user_id' => $currentUserId,
            'message' => $request->message,
            'created_at' => date('c')
        ];

        // Añadir mensaje al array
        $chatsData['messages'][] = $newMessage;

        // Actualizar el último mensaje en la conversación
        foreach ($chatsData['conversations'] as &$conv) {
            if ($conv['id'] == $conversationId) {
                $conv['last_message'] = $request->message;
                $conv['last_message_time'] = date('c');
                break;
            }
        }

        // Guardar cambios
        $this->writeJson('chats.json', $chatsData);

        // Redirigir de vuelta al chat
        return redirect()->route('chat.show', $conversationId)
            ->with('success', 'Mensaje enviado');
    }

    /**
     * Crea una nueva publicación
     */
    public function createPost(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'content' => 'nullable|string|max:1000',
            'ejercicios' => 'required|array|min:1',
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content ?? 'Entrenamiento completado 💪',
            'likes' => 0,
            'comments_count' => 0,
        ]);

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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialController extends Controller
{
    private function readJson($filename)
    {
        $path = storage_path('app/public/data/' . $filename);
        if (!file_exists($path)) {
            return [];
        }
        $content = file_get_contents($path);
        return json_decode($content, true);
    }
    private function writeJson($filename, $data)
    {

        $path = storage_path('app/public/data/' . $filename);
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
    }

   public function feed()
{
    $posts = $this->readJson('posts.json');
    $users = $this->readJson('users.json');

    $usersById = [];
    foreach ($users as $user) {
        $usersById[$user['id']] = $user;
    }

    // Crear un array nuevo para posts válidos
    $validPosts = [];
    foreach ($posts as $post) {
        // Si el usuario existe, añadir sus datos y guardar el post
        if (isset($usersById[$post['user_id']])) {
            $post['user'] = $usersById[$post['user_id']];
            $validPosts[] = $post;
        } else {
            // Si no existe, mostrar un mensaje en la terminal para depurar
            error_log("Post con ID {$post['id']} tiene user_id {$post['user_id']} que no existe en users.json");
        }
    }

    // Ordenar los posts válidos
    usort($validPosts, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    return view('social.feed', compact('validPosts'));
}
    public function perfil($id)
{
    // 1. Leer datos
    $users = $this->readJson('users.json');
    $posts = $this->readJson('posts.json');

    // 2. Buscar el usuario por ID
    $user = null;
    foreach ($users as $u) {
        if ($u['id'] == $id) {
            $user = $u;
            break;
        }
    }

    // 3. Si no existe, error 404
    if (!$user) {
        abort(404, 'Usuario no encontrado');
    }

    // 4. Filtrar posts del usuario
    $userPosts = array_filter($posts, function($post) use ($id) {
        return $post['user_id'] == $id;
    });

    // 5. Ordenar posts
    usort($userPosts, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    // 6. Calcular estadísticas
    $stats = [
        'total_posts' => count($userPosts),
        'total_likes' => array_sum(array_column($userPosts, 'likes')),
        'total_comments' => array_sum(array_column($userPosts, 'comments_count'))
    ];

    // 7. Enviar a la vista
    return view('social.perfil', compact('user', 'userPosts', 'stats'));
}
public function chats()
{
    $currentUserId = 1;  // TEMPORAL: usuario logueado

    $chatsData = $this->readJson('chats.json');
    $users = $this->readJson('users.json');

    $usersById = [];
    foreach ($users as $user) {
        $usersById[$user['id']] = $user;
    }

    $conversations = $chatsData['conversations'] ?? [];

    $myConversations = array_filter($conversations, function($conv) use ($currentUserId) {
        return in_array($currentUserId, $conv['participants']);
    });

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

    return view('social.chats', compact('myConversations'));
}
public function sendMessage(Request $request, $conversationId)
{
    $currentUserId = 1;  // TEMPORAL

    // Validar que el mensaje no esté vacío
    $request->validate([
        'message' => 'required|string|max:1000'
    ]);

    $chatsData = $this->readJson('chats.json');

    // Crear nuevo mensaje
    $newMessage = [
        'id' => count($chatsData['messages']) + 1,
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
public function createPost(Request $request)
{
    $currentUserId = 1;  // TEMPORAL

    $request->validate([
        'content' => 'required|string|max:1000',
        'exercise' => 'nullable|string|max:100',
        'weight' => 'nullable|numeric',
        'reps' => 'nullable|integer',
        'sets' => 'nullable|integer'
    ]);

    $posts = $this->readJson('posts.json');

    $newPost = [
        'id' => count($posts) + 1,
        'user_id' => $currentUserId,
        'content' => $request->content,
        'exercise' => $request->exercise,
        'weight' => $request->weight,
        'reps' => $request->reps,
        'sets' => $request->sets,
        'image' => null,
        'likes' => 0,
        'comments_count' => 0,
        'created_at' => date('c')
    ];

    $posts[] = $newPost;
    $this->writeJson('posts.json', $posts);

    return redirect()->route('feed')->with('success', '¡Publicación creada!');
}
/**
 * Mostrar una conversación específica
 */
public function chat($conversationId)
{
    $currentUserId = 1; // Temporal: usuario logueado

    $chatsData = $this->readJson('chats.json');
    $users = $this->readJson('users.json');

    // Indexar usuarios por ID para acceso rápido
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
    $messages = array_filter($chatsData['messages'], function($msg) use ($conversationId) {
        return $msg['conversation_id'] == $conversationId;
    });

    // Ordenar mensajes por fecha (más antiguos primero)
    usort($messages, function($a, $b) {
        return strtotime($a['created_at']) - strtotime($b['created_at']);
    });

    // Añadir datos del usuario a cada mensaje
    foreach ($messages as &$msg) {
        $msg['user'] = $usersById[$msg['user_id']] ?? null;
    }

    // Obtener el otro participante (el que no es el usuario actual)
    $otherUserId = null;
    foreach ($conversation['participants'] as $pId) {
        if ($pId != $currentUserId) {
            $otherUserId = $pId;
            break;
        }
    }
    $otherUser = $usersById[$otherUserId] ?? null;

    // Pasar datos a la vista
    return view('social.chat', compact('conversation', 'messages', 'otherUser'));
}
};

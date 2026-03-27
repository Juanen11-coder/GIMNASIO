<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;  // ← ESTA LÍNEA ES IMPORTANTE

Route::get('/', function () {
    return view('welcome');
});

// ============================================
// RUTAS PARA LA PARTE SOCIAL
// ============================================

// Mostrar el feed de publicaciones
Route::get('/feed', [SocialController::class, 'feed'])->name('feed');

// Mostrar el perfil de un usuario (ej: /perfil/1)
Route::get('/perfil/{id}', [SocialController::class, 'perfil'])->name('perfil.show');

// Mostrar la lista de chats
Route::get('/chats', [SocialController::class, 'chats'])->name('chats.index');

// Mostrar una conversación específica (ej: /chat/1)
Route::get('/chat/{conversationId}', [SocialController::class, 'chat'])->name('chat.show');

// Enviar un mensaje (cuando el usuario envía el formulario)
Route::post('/chat/{conversationId}', [SocialController::class, 'sendMessage'])->name('chat.send');

// Crear una nueva publicación (cuando el usuario envía el formulario)
Route::post('/post', [SocialController::class, 'createPost'])->name('post.create');

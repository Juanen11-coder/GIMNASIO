<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PageController;

// Páginas públicas
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/sobre-nosotros', [PageController::class, 'about'])->name('about');
Route::get('/inscribete', [PageController::class, 'offers'])->name('inscribete');
Route::get('/contacto', [PageController::class, 'contact'])->name('contact');
Route::get('/tienda', [PageController::class, 'tienda'])->name('tienda');

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    // Rutas de actividades (Pablo)
    Route::get('/actividades', [BookingController::class, 'index'])->name('activities.index');
    Route::get('/reservar', [BookingController::class, 'createActivity'])->name('activities.create');
    Route::post('/reservar', [BookingController::class, 'storeActivity'])->name('activities.store');
    Route::post('/actividades/{activity}/apuntarse', [BookingController::class, 'enroll'])->name('activities.enroll');
    Route::delete('/actividades/{activity}/desapuntarse', [BookingController::class, 'unenroll'])->name('activities.unenroll');
    Route::get('/actividad/{activity}/alumnos', [BookingController::class, 'showStudents'])->name('activities.students');

    // Rutas sociales (Juanen)
    Route::get('/feed', [SocialController::class, 'feed'])->name('feed');
    Route::get('/perfil/{id}', [SocialController::class, 'perfil'])->name('perfil.show');
    Route::get('/chats', [SocialController::class, 'chats'])->name('chats.index');
    Route::get('/chat/{conversationId}', [SocialController::class, 'chat'])->name('chat.show');
    Route::post('/chat/{conversationId}', [SocialController::class, 'sendMessage'])->name('chat.send');
    Route::post('/post', [SocialController::class, 'createPost'])->name('post.create');
    Route::delete('/post/{post}', [SocialController::class, 'deletePost'])->name('post.delete');
    Route::post('/post/{post}/like', [SocialController::class, 'toggleLike'])->name('post.like');
    Route::get('/post/{post}/comments', [CommentController::class, 'index'])->name('post.comments');
    Route::post('/post/{post}/comments', [CommentController::class, 'store'])->name('post.comments.store');

    // Rutas de amigos
    Route::get('/friends', [FriendshipController::class, 'index'])->name('friends.index');
    Route::get('/friends/search', [FriendshipController::class, 'search'])->name('friends.search');
    Route::get('/friends/{user}/request', [FriendshipController::class, 'sendRequest'])->name('friends.request.get'); // Temporal para testing
    Route::post('/friends/{user}/request', [FriendshipController::class, 'sendRequest'])->name('friends.request');
    Route::post('/friends/{friendship}/accept', [FriendshipController::class, 'acceptRequest'])->name('friends.accept');
    Route::post('/friends/{friendship}/reject', [FriendshipController::class, 'rejectRequest'])->name('friends.reject');
    Route::delete('/friends/{friendship}/cancel', [FriendshipController::class, 'cancelRequest'])->name('friends.cancel');
});



// API para obtener ejercicios por grupo muscular
Route::get('/api/ejercicios-por-grupo/{grupoId}', function ($grupoId) {
    return \App\Models\EjercicioPredefinido::where('grupo_muscular_id', $grupoId)->get();
});


Route::get('/api/ejercicios-por-musculo/{musculoId}', function ($musculoId) {
    return \App\Models\EjercicioPredefinido::where('musculo_id', $musculoId)
        ->select('id', 'nombre')
        ->get();
});

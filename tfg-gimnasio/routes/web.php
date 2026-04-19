<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login-test', function () {
    Auth::loginUsingId(1);
    return "Logueado. Ve a /actividades";
});

Route::get('/actividades', [BookingController::class, 'index'])->name('activities.index');

Route::group([],function () {
    Route::get('/reservar', [BookingController::class, 'createActivity'])->name('activities.create');
    // Nota: El nombre del método en el controlador debe coincidir (storeActivity)
    Route::post('/reservar', [BookingController::class, 'storeActivity'])->name('activities.store');
    Route::post('/actividades/{activity}/apuntarse', [BookingController::class, 'enroll'])->name('activities.enroll');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login-as/{user}', [AuthController::class, 'loginAs'])->name('login.as');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/actividad/{activity}/alumnos', [BookingController::class, 'showStudents'])->name('activities.students');

use App\Http\Controllers\SocialController;

Route::get('/feed', [SocialController::class, 'feed'])->name('feed');
Route::get('/perfil/{id}', [SocialController::class, 'perfil'])->name('perfil.show');
Route::get('/chats', [SocialController::class, 'chats'])->name('chats.index');
Route::get('/chat/{conversationId}', [SocialController::class, 'chat'])->name('chat.show');
Route::post('/chat/{conversationId}', [SocialController::class, 'sendMessage'])->name('chat.send');
Route::post('/post', [SocialController::class, 'createPost'])->name('post.create');

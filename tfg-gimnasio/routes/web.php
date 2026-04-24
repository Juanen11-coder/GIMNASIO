<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialController;

// Rutas públicas (sin autenticación)
Route::get('/', function () {
    return redirect('/login');
});

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
    Route::get('/actividad/{activity}/alumnos', [BookingController::class, 'showStudents'])->name('activities.students');

    // Rutas sociales (Juanen)
    Route::get('/feed', [SocialController::class, 'feed'])->name('feed');
    Route::get('/perfil/{id}', [SocialController::class, 'perfil'])->name('perfil.show');
    Route::get('/chats', [SocialController::class, 'chats'])->name('chats.index');
    Route::get('/chat/{conversationId}', [SocialController::class, 'chat'])->name('chat.show');
    Route::post('/chat/{conversationId}', [SocialController::class, 'sendMessage'])->name('chat.send');
    Route::post('/post', [SocialController::class, 'createPost'])->name('post.create');
});

use App\Http\Controllers\PageController;

// Rutas públicas (sin login)
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/sobre-nosotros', [PageController::class, 'about'])->name('about');
Route::get('/inscribete', [PageController::class, 'offers'])->name('offers');
Route::get('/contacto', [PageController::class, 'contact'])->name('contact');


use App\Http\Controllers\RutinaController;

// Rutas de rutinas
Route::prefix('rutinas')->group(function () {
    Route::get('/', [RutinaController::class, 'index'])->name('rutinas.index');
    Route::get('/create', [RutinaController::class, 'create'])->name('rutinas.create');
    Route::post('/', [RutinaController::class, 'store'])->name('rutinas.store');
    Route::get('/{rutina}/edit', [RutinaController::class, 'edit'])->name('rutinas.edit');
    Route::post('/{rutina}/add-dia', [RutinaController::class, 'addDia'])->name('rutinas.add-dia');
    Route::post('/dia/{dia}/add-grupo', [RutinaController::class, 'addGrupo'])->name('rutinas.add-grupo');
    Route::post('/grupo/{grupo}/add-ejercicio', [RutinaController::class, 'addEjercicio'])->name('rutinas.add-ejercicio');
    Route::post('/{rutina}/publish', [RutinaController::class, 'publish'])->name('rutinas.publish');
});


// Rutas para rutinas (protegidas por auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/rutinas', [RutinaController::class, 'index'])->name('rutinas.index');
    Route::get('/rutinas/create', [RutinaController::class, 'create'])->name('rutinas.create');
    Route::post('/rutinas', [RutinaController::class, 'store'])->name('rutinas.store');
    Route::get('/rutinas/{rutina}/edit', [RutinaController::class, 'edit'])->name('rutinas.edit');
    Route::post('/rutinas/{rutina}/add-dia', [RutinaController::class, 'addDia'])->name('rutinas.add-dia');
    Route::post('/rutinas/dia/{dia}/add-grupo', [RutinaController::class, 'addGrupo'])->name('rutinas.add-grupo');
    Route::post('/rutinas/grupo/{grupo}/add-ejercicio', [RutinaController::class, 'addEjercicio'])->name('rutinas.add-ejercicio');
    Route::post('/rutinas/{rutina}/publish', [RutinaController::class, 'publish'])->name('rutinas.publish');

    // Eliminar rutas
    Route::delete('/rutinas/dia/{dia}', [RutinaController::class, 'deleteDia'])->name('rutinas.delete-dia');
    Route::delete('/rutinas/grupo/{grupo}', [RutinaController::class, 'deleteGrupo'])->name('rutinas.delete-grupo');
    Route::delete('/rutinas/ejercicio/{ejercicio}', [RutinaController::class, 'deleteEjercicio'])->name('rutinas.delete-ejercicio');
});

// API para obtener ejercicios por grupo muscular
Route::get('/api/ejercicios-por-grupo/{grupoId}', function($grupoId) {
    return \App\Models\EjercicioPredefinido::where('grupo_muscular_id', $grupoId)->get();
});

// Páginas públicas
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/inscribete', [PageController::class, 'inscribete'])->name('inscribete');
Route::get('/sobre-nosotros', [PageController::class, 'about'])->name('sobre-nosotros');
Route::get('/ofertas', [PageController::class, 'ofertas'])->name('ofertas');

Route::get('/tienda', [PageController::class, 'tienda'])->name('tienda');

Route::get('/api/ejercicios-por-musculo/{musculoId}', function($musculoId) {
    return App\Models\EjercicioPredefinido::where('musculo_id', $musculoId)
        ->select('id', 'nombre')
        ->get();
});

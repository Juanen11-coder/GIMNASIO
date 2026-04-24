@extends('layouts.app')

@section('title', isset($user) && $user ? $user->name . ' - Perfil' : 'Perfil')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">

        {{-- Tarjeta de perfil --}}
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex items-center gap-6">

                {{-- Avatar --}}
                @if (isset($user) && $user)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full">
                @else
                    <div class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center text-gray-500 text-2xl">
                        ?
                    </div>
                @endif

                {{-- Información --}}
                <div class="flex-1">
                    @if (isset($user) && $user)
                        <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
                        <p class="text-gray-500">{{ $user->email }}</p>
                    @else
                        <h1 class="text-2xl font-bold text-gray-500">Usuario no encontrado</h1>
                    @endif

                    {{-- Estadísticas --}}
                    @if (isset($stats))
                        <div class="flex gap-4 mt-2 text-sm">
                            <span>📊 {{ $stats['total_posts'] ?? 0 }} publicaciones</span>
                            <span>❤️ {{ $stats['total_likes'] ?? 0 }} likes recibidos</span>
                            <span>💬 {{ $stats['total_comments'] ?? 0 }} comentarios</span>
                        </div>
                    @endif
                </div>

                {{-- Botón seguir (solo si no es tu propio perfil) --}}
                @if (auth()->check() && $user->id != auth()->id())
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                        + Seguir
                    </button>
                @else
                    <a href="{{ route('feed') }}"
                        class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                        Mi feed
                    </a>
                @endif
            </div>
        </div>

        {{-- Publicaciones del usuario --}}
        <h2 class="text-xl font-bold text-gray-800 mb-4">Publicaciones</h2>

        @if (isset($userPosts) && count($userPosts) > 0)
            @foreach ($userPosts as $post)
                <div class="bg-white rounded-xl shadow-md mb-4 overflow-hidden">
                    <div class="p-4">
                        <p class="text-gray-800 mb-2">{{ $post['content'] }}</p>

                        @if (!empty($post['exercise']))
                            <div class="bg-gray-50 rounded-lg p-2 text-sm">
                                🏋️ {{ $post['exercise'] }}
                                @if (!empty($post['weight']))
                                    · {{ $post['weight'] }}kg
                                @endif
                                @if (!empty($post['reps']))
                                    · {{ $post['reps'] }} reps
                                @endif
                                @if (!empty($post['sets']))
                                    · {{ $post['sets'] }} series
                                @endif
                            </div>
                        @endif

                        <p class="text-xs text-gray-400 mt-2">
                            {{ \Carbon\Carbon::parse($post['created_at'])->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white rounded-xl shadow-md p-8 text-center text-gray-500">
                @if (isset($user) && $user)
                    {{ $user->name }} aún no tiene publicaciones.
                @else
                    No hay publicaciones para mostrar.
                @endif
            </div>
        @endif
    </div>

    {{-- Modal nueva publicación (solo visible en tu perfil) --}}
    @if (auth()->check() && $user->id == auth()->id())
        <div id="createPostModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Compartir entreno</h3>
                    <button onclick="document.getElementById('createPostModal').classList.add('hidden')"
                        class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>
                <form action="{{ route('post.create') }}" method="POST">
                    @csrf
                    <textarea name="content" rows="3"
                        class="w-full border rounded-lg p-2 mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="¿Qué has entrenado hoy?" required></textarea>
                    <input type="text" name="exercise"
                        class="w-full border rounded-lg p-2 mb-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Ejercicio (opcional)">
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <input type="number" name="weight" step="0.5"
                            class="border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Peso (kg)">
                        <input type="number" name="reps"
                            class="border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Reps">
                        <input type="number" name="sets"
                            class="border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Series">
                    </div>
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                        Publicar
                    </button>
                </form>
            </div>
        </div>
        <a href="{{ route('rutinas.index') }}"
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
            📋 Mis Rutinas
        </a>
        <script>
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    document.getElementById('createPostModal').classList.add('hidden');
                }
            });
        </script>
    @endif
@endsection

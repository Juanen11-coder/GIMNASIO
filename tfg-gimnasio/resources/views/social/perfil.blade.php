@extends('layouts.app')

@section('title', isset($user) && $user ? $user['name'] . ' - Perfil' : 'Perfil')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">

    {{-- Tarjeta de perfil --}}
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex items-center gap-6">
            {{-- Avatar --}}
            @if(isset($user) && $user)
                <img src="{{ $user['avatar'] }}"
                     alt="{{ $user['name'] }}"
                     class="w-24 h-24 rounded-full">
            @else
                <div class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center text-gray-500 text-2xl">
                    ?
                </div>
            @endif

            {{-- Información --}}
            <div class="flex-1">
                @if(isset($user) && $user)
                    <h1 class="text-2xl font-bold text-gray-800">{{ $user['name'] }}</h1>
                    <p class="text-gray-500">{{ $user['email'] }}</p>
                @else
                    <h1 class="text-2xl font-bold text-gray-500">Usuario no encontrado</h1>
                @endif

                {{-- Estadísticas --}}
                @if(isset($stats))
                    <div class="flex gap-4 mt-2 text-sm">
                        <span>📊 {{ $stats['total_posts'] ?? 0 }} publicaciones</span>
                        <span>❤️ {{ $stats['total_likes'] ?? 0 }} likes recibidos</span>
                        <span>💬 {{ $stats['total_comments'] ?? 0 }} comentarios</span>
                    </div>
                @endif
            </div>

            {{-- Botón seguir (por ahora no funcional) --}}
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                + Seguir
            </button>
        </div>
    </div>

    {{-- Publicaciones del usuario --}}
    <h2 class="text-xl font-bold text-gray-800 mb-4">Publicaciones</h2>

    @if(isset($userPosts) && count($userPosts) > 0)
        @foreach($userPosts as $post)
        <div class="bg-white rounded-xl shadow-md mb-4 overflow-hidden">
            <div class="p-4">
                <p class="text-gray-800 mb-2">{{ $post['content'] }}</p>

                @if(!empty($post['exercise']))
                    <div class="bg-gray-50 rounded-lg p-2 text-sm">
                        🏋️ {{ $post['exercise'] }}
                        @if(!empty($post['weight'])) · {{ $post['weight'] }}kg @endif
                        @if(!empty($post['reps'])) · {{ $post['reps'] }} reps @endif
                        @if(!empty($post['sets'])) · {{ $post['sets'] }} series @endif
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
            @if(isset($user) && $user)
                {{ $user['name'] }} aún no tiene publicaciones.
            @else
                No hay publicaciones para mostrar.
            @endif
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Feed de Entrenos')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">

    {{-- Botón para crear publicación --}}
    <div class="flex justify-end mb-6">
        <button onclick="document.getElementById('createPostModal').classList.remove('hidden')"
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            + Nueva Publicación
        </button>
    </div>

    {{-- MODAL --}}
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

    {{-- LISTA DE PUBLICACIONES --}}
    @if(count($validPosts) > 0)
        @foreach($validPosts as $post)
        <div class="bg-white rounded-xl shadow-md mb-6 overflow-hidden hover:shadow-lg transition">

            {{-- Cabecera: avatar + nombre + fecha --}}
            <div class="p-4 border-b">
                <div class="flex items-center">
                    @if($post['user'])
                        <img src="{{ $post['user']['avatar'] }}" alt="{{ $post['user']['name'] }}"
                            class="w-12 h-12 rounded-full mr-3">
                        <div>
                            <a href="{{ route('perfil.show', $post['user']['id']) }}"
                                class="font-semibold text-gray-800 hover:text-indigo-600">
                                {{ $post['user']['name'] }}
                            </a>
                            <p class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($post['created_at'])->diffForHumans() }}
                            </p>
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-full bg-gray-300 mr-3 flex items-center justify-center text-gray-500">
                            ?
                        </div>
                        <span class="font-semibold text-gray-500">Usuario desconocido</span>
                    @endif
                </div>
            </div>

            {{-- Contenido del post --}}
            <div class="p-4">
                <p class="text-gray-800 mb-3">{{ $post['content'] }}</p>

                {{-- Datos del ejercicio --}}
                @if(!empty($post['exercise']))
                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                        <div class="flex items-center gap-4 text-sm">
                            <span class="font-medium text-gray-700">🏋️ {{ $post['exercise'] }}</span>
                            @if(!empty($post['weight']))
                                <span>⚡ {{ $post['weight'] }} kg</span>
                            @endif
                            @if(!empty($post['reps']))
                                <span>🔄 {{ $post['reps'] }} reps</span>
                            @endif
                            @if(!empty($post['sets']))
                                <span>📊 {{ $post['sets'] }} series</span>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Botones de interacción --}}
                <div class="flex gap-4 text-gray-500 text-sm">
                    <button class="hover:text-red-500 transition">
                        ❤️ {{ $post['likes'] }} Me gusta
                    </button>
                    <button class="hover:text-indigo-500 transition">
                        💬 {{ $post['comments_count'] }} Comentarios
                    </button>
                </div>
            </div>

        </div>
        @endforeach
    @else
        <div class="bg-white rounded-xl shadow-md p-8 text-center text-gray-500">
            No hay publicaciones aún. ¡Sé el primero en compartir tu entreno!
        </div>
    @endif

</div>

<script>
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.getElementById('createPostModal').classList.add('hidden');
        }
    });
</script>
@endsection

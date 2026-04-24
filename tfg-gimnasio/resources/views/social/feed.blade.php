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

    {{-- MODAL AVANZADO PARA PUBLICAR ENTRENAMIENTO --}}
    <div id="createPostModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white mb-10">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">🏋️ Compartir entrenamiento</h3>
                <button onclick="document.getElementById('createPostModal').classList.add('hidden')"
                        class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            <form action="{{ route('post.create') }}" method="POST" id="entrenamientoForm">
                @csrf

                {{-- Descripción general --}}
                <textarea name="content" rows="2" class="w-full border rounded-lg p-2 mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                          placeholder="¿Qué tal fue tu entrenamiento? (opcional)"></textarea>

                {{-- Sección de ejercicios dinámicos --}}
                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                    <h4 class="font-semibold mb-2">📋 Ejercicios realizados</h4>
                    <div id="ejercicios-container">
                        <div class="ejercicio-item bg-white rounded-lg p-3 mb-2 shadow-sm">
                            <div class="grid grid-cols-12 gap-2 items-center">
                                {{-- Selección de músculo --}}
                                <div class="col-span-3">
                                    <select name="ejercicios[0][musculo_id]" class="musculo-select w-full border rounded-lg p-2 text-sm">
                                        <option value="">-- Músculo --</option>
                                        @foreach($musculos as $musculo)
                                            <option value="{{ $musculo->id }}">{{ $musculo->nombre }}</option>
                                        @endforeach
                                        <option value="otro">+ Otro músculo</option>
                                    </select>
                                    <input type="text" name="ejercicios[0][musculo_otro]" class="musculo-otro hidden w-full border rounded-lg p-2 text-sm mt-1" placeholder="Nombre del músculo">
                                </div>

                                {{-- Selección de ejercicio --}}
                                <div class="col-span-3">
                                    <select name="ejercicios[0][ejercicio_id]" class="ejercicio-select w-full border rounded-lg p-2 text-sm" disabled>
                                        <option value="">-- Seleccionar ejercicio --</option>
                                    </select>
                                    <input type="text" name="ejercicios[0][ejercicio_otro]" class="ejercicio-otro hidden w-full border rounded-lg p-2 text-sm mt-1" placeholder="Ejercicio personalizado">
                                </div>

                                {{-- Series --}}
                                <div class="col-span-2">
                                    <input type="number" name="ejercicios[0][series]" placeholder="Series" class="w-full border rounded-lg p-2 text-sm">
                                </div>

                                {{-- Repeticiones --}}
                                <div class="col-span-2">
                                    <input type="number" name="ejercicios[0][repeticiones]" placeholder="Reps" class="w-full border rounded-lg p-2 text-sm">
                                </div>

                                {{-- Peso --}}
                                <div class="col-span-1">
                                    <input type="number" step="0.5" name="ejercicios[0][peso]" placeholder="Kg" class="w-full border rounded-lg p-2 text-sm">
                                </div>

                                {{-- Botón eliminar --}}
                                <div class="col-span-1 text-center">
                                    <button type="button" class="remove-ejercicio text-red-500 hover:text-red-700">🗑️</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="addEjercicioBtn" class="mt-2 text-indigo-600 hover:text-indigo-800 text-sm">
                        + Añadir ejercicio
                    </button>
                </div>

                {{-- Botón de publicar --}}
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                    📤 Publicar entrenamiento
                </button>
            </form>
        </div>
    </div>

    {{-- LISTA DE PUBLICACIONES --}}
    @if(isset($posts) && count($posts) > 0)
        @foreach($posts as $post)
        <div class="bg-white rounded-xl shadow-md mb-6 overflow-hidden hover:shadow-lg transition">

            {{-- Cabecera: avatar + nombre + fecha --}}
            <div class="p-4 border-b">
                <div class="flex items-center">
                    @if($post->user->avatar)
                        <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="w-12 h-12 rounded-full mr-3">
                    @else
                        <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold mr-3">
                            {{ substr($post->user->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <a href="{{ route('perfil.show', $post->user->id) }}" class="font-semibold text-gray-800 hover:text-indigo-600">
                            {{ $post->user->name }}
                        </a>
                        <p class="text-xs text-gray-400">
                            {{ $post->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Contenido del post --}}
            <div class="p-4">
                @if($post->content)
                    <p class="text-gray-800 mb-3">{{ $post->content }}</p>
                @endif

                {{-- Detalles del entrenamiento (ejercicios) --}}
                @if($post->detalles && $post->detalles->count() > 0)
                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                        <h4 class="font-semibold text-sm text-gray-700 mb-2">📊 Detalles del entrenamiento:</h4>
                        @foreach($post->detalles->groupBy('musculo.nombre') as $musculoNombre => $ejercicios)
                            <div class="mb-3">
                                <p class="font-medium text-indigo-600 text-sm">{{ $musculoNombre }}</p>
                                @foreach($ejercicios as $detalle)
                                    <p class="text-sm text-gray-600 ml-2">
                                        • {{ $detalle->ejercicio }}
                                        @if($detalle->series) · {{ $detalle->series }} series @endif
                                        @if($detalle->repeticiones) · {{ $detalle->repeticiones }} reps @endif
                                        @if($detalle->peso) · {{ $detalle->peso }} kg @endif
                                    </p>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Botones de interacción --}}
                <div class="flex gap-4 text-gray-500 text-sm">
                    <button class="hover:text-red-500 transition">
                        ❤️ {{ $post->likes }} Me gusta
                    </button>
                    <button class="hover:text-indigo-500 transition">
                        💬 {{ $post->comments_count }} Comentarios
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
    // Función para cargar ejercicios
    function cargarEjercicios(musculoSelect) {
        // Obtener el contenedor del ejercicio
        const ejercicioItem = musculoSelect.closest('.ejercicio-item');
        const ejercicioSelect = ejercicioItem.querySelector('.ejercicio-select');
        const musculoOtro = ejercicioItem.querySelector('.musculo-otro');

        const musculoId = musculoSelect.value;

        console.log('Músculo seleccionado:', musculoId);

        // Si selecciona "otro"
        if (musculoId === 'otro') {
            musculoOtro.classList.remove('hidden');
            ejercicioSelect.innerHTML = '<option value="">-- Seleccionar ejercicio --</option><option value="otro">+ Otro ejercicio</option>';
            ejercicioSelect.disabled = false;
            return;
        }

        // Ocultar campo "otro músculo"
        musculoOtro.classList.add('hidden');

        // Si no hay músculo seleccionado
        if (!musculoId) {
            ejercicioSelect.innerHTML = '<option value="">-- Seleccionar ejercicio --</option>';
            ejercicioSelect.disabled = true;
            return;
        }

        // Cargar ejercicios desde la API
        ejercicioSelect.innerHTML = '<option>Cargando...</option>';

        fetch(`/api/ejercicios-por-musculo/${musculoId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Ejercicios:', data);
                ejercicioSelect.innerHTML = '<option value="">-- Seleccionar ejercicio --</option>';
                data.forEach(ejercicio => {
                    ejercicioSelect.innerHTML += `<option value="${ejercicio.id}">${ejercicio.nombre}</option>`;
                });
                ejercicioSelect.innerHTML += '<option value="otro">+ Otro ejercicio</option>';
                ejercicioSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                ejercicioSelect.innerHTML = '<option value="">Error al cargar</option>';
            });
    }

    // Configurar eventos cuando la página carga
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Página cargada');

        // Configurar todos los selects de músculo
        document.querySelectorAll('.musculo-select').forEach(select => {
            select.addEventListener('change', function() {
                cargarEjercicios(this);
            });
        });

        // Configurar botones eliminar
        document.querySelectorAll('.remove-ejercicio').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.ejercicio-item').remove();
            });
        });
    });

    // Añadir nuevo ejercicio
    document.getElementById('addEjercicioBtn').addEventListener('click', function() {
        const container = document.getElementById('ejercicios-container');
        const newIndex = document.querySelectorAll('.ejercicio-item').length;

        const nuevoItem = document.createElement('div');
        nuevoItem.className = 'ejercicio-item bg-white rounded-lg p-3 mb-2 shadow-sm';
        nuevoItem.innerHTML = `
            <div class="grid grid-cols-12 gap-2 items-center">
                <div class="col-span-3">
                    <select name="ejercicios[${newIndex}][musculo_id]" class="musculo-select w-full border rounded-lg p-2 text-sm">
                        <option value="">-- Músculo --</option>
                        @foreach($musculos as $musculo)
                            <option value="{{ $musculo->id }}">{{ $musculo->nombre }}</option>
                        @endforeach
                        <option value="otro">+ Otro músculo</option>
                    </select>
                    <input type="text" name="ejercicios[${newIndex}][musculo_otro]" class="musculo-otro hidden w-full border rounded-lg p-2 text-sm mt-1" placeholder="Nombre del músculo">
                </div>
                <div class="col-span-3">
                    <select name="ejercicios[${newIndex}][ejercicio_id]" class="ejercicio-select w-full border rounded-lg p-2 text-sm" disabled>
                        <option value="">-- Seleccionar ejercicio --</option>
                    </select>
                    <input type="text" name="ejercicios[${newIndex}][ejercicio_otro]" class="ejercicio-otro hidden w-full border rounded-lg p-2 text-sm mt-1" placeholder="Ejercicio personalizado">
                </div>
                <div class="col-span-2">
                    <input type="number" name="ejercicios[${newIndex}][series]" placeholder="Series" class="w-full border rounded-lg p-2 text-sm">
                </div>
                <div class="col-span-2">
                    <input type="number" name="ejercicios[${newIndex}][repeticiones]" placeholder="Reps" class="w-full border rounded-lg p-2 text-sm">
                </div>
                <div class="col-span-1">
                    <input type="number" step="0.5" name="ejercicios[${newIndex}][peso]" placeholder="Kg" class="w-full border rounded-lg p-2 text-sm">
                </div>
                <div class="col-span-1 text-center">
                    <button type="button" class="remove-ejercicio text-red-500 hover:text-red-700">🗑️</button>
                </div>
            </div>
        `;

        container.appendChild(nuevoItem);

        // Configurar eventos del nuevo item
        nuevoItem.querySelector('.musculo-select').addEventListener('change', function() {
            cargarEjercicios(this);
        });
        nuevoItem.querySelector('.remove-ejercicio').addEventListener('click', function() {
            nuevoItem.remove();
        });
    });
</script>
@endsection

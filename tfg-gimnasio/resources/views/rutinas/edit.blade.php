@extends('layouts.app')

@section('title', 'Editar Rutina: ' . $rutina->nombre)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('rutinas.index') }}" class="text-indigo-600 mr-4 hover:text-indigo-800">← Volver</a>
            <h1 class="text-2xl font-bold text-gray-800">📝 Editar Rutina: {{ $rutina->nombre }}</h1>
        </div>
        <button onclick="toggleModal('addDiaModal')" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            + Añadir Día
        </button>
    </div>

    {{-- Mostrar días existentes --}}
    @foreach($rutina->diasEntreno as $index => $dia)
        <div class="bg-white rounded-xl shadow-md mb-6 overflow-hidden">
            <div class="bg-gray-50 px-6 py-3 border-b flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">📅 {{ $dia->nombre }}</h2>
                <button onclick="eliminarDia({{ $dia->id }})" class="text-red-600 hover:text-red-800 text-sm">
                    🗑️ Eliminar día
                </button>
            </div>

            <div class="p-6">
                {{-- Grupos musculares de este día --}}
                @foreach($dia->gruposMusculares as $grupo)
                    <div class="mb-6 border rounded-lg p-4">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-md font-semibold text-indigo-600">💪 {{ $grupo->nombre }}</h3>
                            <button onclick="eliminarGrupo({{ $grupo->id }})" class="text-red-500 text-sm hover:text-red-700">
                                Eliminar grupo
                            </button>
                        </div>

                        {{-- Ejercicios de este grupo muscular --}}
                        <div class="space-y-3 mb-3">
                            @foreach($grupo->ejercicios as $ejercicio)
                                <div class="bg-gray-50 rounded-lg p-3 flex justify-between items-center">
                                    <div class="flex-1">
                                        <div class="font-medium">{{ $ejercicio->nombre }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ $ejercicio->series }} series · {{ $ejercicio->repeticiones }} reps
                                            @if($ejercicio->peso) · {{ $ejercicio->peso }} kg @endif
                                            @if($ejercicio->descanso) · ⏱️ {{ $ejercicio->descanso }}s @endif
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button onclick="editarEjercicio({{ $ejercicio->id }})" class="text-indigo-600 hover:text-indigo-800">
                                            ✏️
                                        </button>
                                        <button onclick="eliminarEjercicio({{ $ejercicio->id }})" class="text-red-600 hover:text-red-800">
                                            🗑️
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Botón añadir ejercicio --}}
                        <button onclick="openAddEjercicioModal({{ $grupo->id }}, '{{ $grupo->nombre }}')"
                                class="text-sm bg-green-100 text-green-700 px-3 py-1 rounded-lg hover:bg-green-200">
                            + Añadir ejercicio
                        </button>
                    </div>
                @endforeach

                {{-- Botón añadir grupo muscular --}}
                <button onclick="openAddGrupoModal({{ $dia->id }})"
                        class="text-sm bg-indigo-100 text-indigo-700 px-3 py-1 rounded-lg hover:bg-indigo-200">
                    + Añadir grupo muscular
                </button>
            </div>
        </div>
    @endforeach

    @if($rutina->diasEntreno->count() == 0)
        <div class="bg-white rounded-xl shadow-md p-12 text-center text-gray-500">
            <p>No hay días creados. ¡Añade tu primer día de entrenamiento!</p>
        </div>
    @endif

</div>

{{-- ========== MODALES ========== --}}

{{-- Modal añadir día --}}
<div id="addDiaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center h-full">
        <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-4">Añadir día de entrenamiento</h3>
        <form action="{{ route('rutinas.add-dia', $rutina->id) }}" method="POST">
            @csrf
            <input type="text" name="nombre" placeholder="Ej: Día 1 - Pecho/Hombro/Tríceps"
                   class="w-full border rounded-lg p-2 mb-3" required>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggleModal('addDiaModal')" class="px-4 py-2 bg-gray-300 rounded-lg">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Añadir</button>
            </div>
        </form>
    </div>
    </div>
</div>

{{-- Modal añadir grupo muscular --}}
<div id="addGrupoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center h-full">
        <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-4">Añadir grupo muscular</h3>
        <form id="addGrupoForm" method="POST">
            @csrf
            <label class="block text-sm mb-1">Seleccionar o añadir</label>
            <div class="flex gap-2 mb-3">
                <select id="grupoSelect" class="flex-1 border rounded-lg p-2">
                    <option value="">-- Seleccionar --</option>
                    @foreach($gruposPredefinidos ?? [] as $grupo)
                        <option value="{{ $grupo }}">{{ $grupo }}</option>
                    @endforeach
                    <option value="otro">+ Añadir nuevo...</option>
                </select>
                <input type="text" id="grupoNuevo" placeholder="Nuevo grupo" class="hidden flex-1 border rounded-lg p-2">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggleModal('addGrupoModal')" class="px-4 py-2 bg-gray-300 rounded-lg">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Añadir</button>
            </div>
        </form>
    </div>
    </div>
</div>

{{-- Modal añadir ejercicio --}}
<div id="addEjercicioModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 overflow-y-auto">
    <div class="bg-white rounded-lg p-6 max-w-lg mx-auto my-8">
        <h3 id="ejercicioModalTitle" class="text-lg font-bold mb-4">Añadir ejercicio</h3>
        <form id="addEjercicioForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="grupoMuscularId" name="grupo_muscular_id">

            <label class="block text-sm font-medium mb-1">Ejercicio</label>
            <div class="flex gap-2 mb-3">
                <select id="ejercicioSelect" class="flex-1 border rounded-lg p-2">
                    <option value="">-- Seleccionar ejercicio --</option>
                </select>
                <input type="text" id="ejercicioNuevo" placeholder="Nuevo ejercicio" class="hidden flex-1 border rounded-lg p-2">
                <button type="button" onclick="toggleEjercicioInput()" class="text-indigo-600 hover:text-indigo-800">+ Nuevo</button>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-sm font-medium">Series</label>
                    <input type="number" name="series" class="w-full border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Repeticiones</label>
                    <input type="number" name="repeticiones" class="w-full border rounded-lg p-2">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-sm font-medium">Peso (kg)</label>
                    <input type="number" name="peso" step="0.5" class="w-full border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Descanso (seg)</label>
                    <input type="number" name="descanso" class="w-full border rounded-lg p-2">
                </div>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium">Foto (opcional)</label>
                <input type="file" name="foto" accept="image/*" class="w-full">
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium">Notas</label>
                <textarea name="notas" rows="2" class="w-full border rounded-lg p-2" placeholder="Consejos, técnica, etc."></textarea>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="toggleModal('addEjercicioModal')" class="px-4 py-2 bg-gray-300 rounded-lg">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">Guardar ejercicio</button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentGrupoId = null;

    function toggleModal(modalId) {
        document.getElementById(modalId).classList.toggle('hidden');
    }

    function openAddGrupoModal(diaId) {
        let form = document.getElementById('addGrupoForm');
        form.action = `/rutinas/dia/${diaId}/add-grupo`;
        toggleModal('addGrupoModal');

        document.getElementById('grupoSelect').addEventListener('change', function() {
            let nuevoInput = document.getElementById('grupoNuevo');
            if (this.value === 'otro') {
                nuevoInput.classList.remove('hidden');
                nuevoInput.name = 'nombre';
                document.querySelector('#addGrupoForm select').name = '';
            } else {
                nuevoInput.classList.add('hidden');
                nuevoInput.name = '';
                document.querySelector('#addGrupoForm select').name = 'nombre';
            }
        });
    }

    function openAddEjercicioModal(grupoId, grupoNombre) {
        currentGrupoId = grupoId;
        document.getElementById('grupoMuscularId').value = grupoId;
        document.getElementById('ejercicioModalTitle').innerHTML = `Añadir ejercicio a ${grupoNombre}`;

        // Cargar ejercicios predefinidos para este grupo muscular
        fetch(`/api/ejercicios-por-grupo/${grupoId}`)
            .then(response => response.json())
            .then(data => {
                let select = document.getElementById('ejercicioSelect');
                select.innerHTML = '<option value="">-- Seleccionar ejercicio --</option>';
                data.forEach(ejercicio => {
                    select.innerHTML += `<option value="${ejercicio.nombre}">${ejercicio.nombre}</option>`;
                });
            });

        toggleModal('addEjercicioModal');
    }

    function toggleEjercicioInput() {
        let select = document.getElementById('ejercicioSelect');
        let input = document.getElementById('ejercicioNuevo');
        if (select.classList.contains('hidden')) {
            select.classList.remove('hidden');
            input.classList.add('hidden');
            input.name = '';
            select.name = 'nombre';
        } else {
            select.classList.add('hidden');
            input.classList.remove('hidden');
            select.name = '';
            input.name = 'nombre';
        }
    }

    function eliminarDia(diaId) {
        if(confirm('¿Eliminar este día y todos sus ejercicios?')) {
            fetch(`/rutinas/dia/${diaId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(() => location.reload());
        }
    }

    function eliminarGrupo(grupoId) {
        if(confirm('¿Eliminar este grupo muscular y sus ejercicios?')) {
            fetch(`/rutinas/grupo/${grupoId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(() => location.reload());
        }
    }

    function eliminarEjercicio(ejercicioId) {
        if(confirm('¿Eliminar este ejercicio?')) {
            fetch(`/rutinas/ejercicio/${ejercicioId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(() => location.reload());
        }
    }
</script>
@endsection

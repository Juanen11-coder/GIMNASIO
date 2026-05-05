@extends('layouts.app')

@section('title', 'Crear Actividad')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('activities.index') }}" class="text-gray-400 hover:text-[#00E676] transition">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">Crear nueva actividad</h1>
    </div>

    <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-6">
        @if(session('error'))
            <div class="mb-6 bg-red-800 text-red-100 p-4 rounded-xl border border-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-800 text-red-100 p-4 rounded-xl border border-red-700">
                Revisa los campos del formulario.
            </div>
        @endif

        <form action="{{ route('activities.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Nombre de la actividad *</label>
                <input type="text" name="title" required value="{{ old('title') }}"
                       class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-[#00E676] transition"
                       placeholder="Ej: Spinning, Yoga, CrossFit...">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Sala *</label>
                <select name="space_id" required class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676] transition">
                    <option value="">-- Seleccionar sala --</option>
                    @foreach($spaces as $space)
                        <option value="{{ $space->id }}" {{ old('space_id') == $space->id ? 'selected' : '' }}>
                            {{ $space->name }} - {{ $space->capacity }} plazas
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Categoria *</label>
                <select name="category" required class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676] transition">
                    <option value="cardio" {{ old('category') === 'cardio' ? 'selected' : '' }}>Cardio</option>
                    <option value="strength" {{ old('category') === 'strength' ? 'selected' : '' }}>Fuerza</option>
                    <option value="relax" {{ old('category') === 'relax' ? 'selected' : '' }}>Relajacion</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de horario *</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white cursor-pointer">
                        <input type="radio" name="creation_type" value="single" class="accent-[#00E676]" {{ old('creation_type', 'single') === 'single' ? 'checked' : '' }}>
                        Clase puntual
                    </label>
                    <label class="flex items-center gap-3 bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white cursor-pointer">
                        <input type="radio" name="creation_type" value="weekly" class="accent-[#00E676]" {{ old('creation_type') === 'weekly' ? 'checked' : '' }}>
                        Horario semanal
                    </label>
                </div>
            </div>

            <div id="single-fields" class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Fecha y hora *</label>
                <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}"
                       class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676] transition">
            </div>

            <div id="weekly-fields" class="hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Desde *</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                               class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676] transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Hasta *</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                               class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676] transition">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Hora *</label>
                    <input type="time" name="time" value="{{ old('time') }}"
                           class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676] transition">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Dias de la semana *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach([1 => 'Lunes', 2 => 'Martes', 3 => 'Miercoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sabado', 7 => 'Domingo'] as $dayNumber => $dayName)
                            <label class="flex items-center gap-2 bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-3 py-2 text-white cursor-pointer">
                                <input type="checkbox" name="weekdays[]" value="{{ $dayNumber }}" class="accent-[#00E676]"
                                       {{ in_array($dayNumber, old('weekdays', [1, 2, 3, 4, 5])) ? 'checked' : '' }}>
                                {{ $dayName }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('activities.index') }}" class="bg-[#2A2A2A] hover:bg-[#3A3A3A] text-white px-6 py-3 rounded-xl transition">
                    Cancelar
                </a>
                <button type="submit" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-6 py-3 rounded-xl transition">
                    <i class="fas fa-save mr-2"></i> Crear actividad
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    const creationTypeInputs = document.querySelectorAll('input[name="creation_type"]');
    const singleFields = document.getElementById('single-fields');
    const weeklyFields = document.getElementById('weekly-fields');

    function updateScheduleFields() {
        const selectedType = document.querySelector('input[name="creation_type"]:checked').value;
        singleFields.classList.toggle('hidden', selectedType !== 'single');
        weeklyFields.classList.toggle('hidden', selectedType !== 'weekly');
    }

    creationTypeInputs.forEach(input => input.addEventListener('change', updateScheduleFields));
    updateScheduleFields();
</script>
@endsection

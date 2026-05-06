@extends('layouts.app')

@section('title', 'Editar actividad')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.activities') }}" class="text-gray-400 hover:text-[#00E676] transition"><i class="fas fa-arrow-left text-xl"></i></a>
        <h1 class="text-2xl font-bold text-white">Editar actividad</h1>
    </div>

    @if(session('error'))
        <div class="mb-6 bg-red-800 text-red-100 p-4 rounded-xl border border-red-700">{{ session('error') }}</div>
    @endif

    <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-6">
        <form action="{{ route('activities.update', $activity->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Nombre *</label>
                <input name="title" required value="{{ old('title', $activity->title) }}" class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676] transition">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Sala *</label>
                <select name="space_id" required class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676] transition">
                    @foreach($spaces as $space)
                        <option value="{{ $space->id }}" {{ old('space_id', $activity->space_id) == $space->id ? 'selected' : '' }}>{{ $space->name }} - {{ $space->capacity }} plazas</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Fecha y hora *</label>
                <input type="datetime-local" name="scheduled_at" required value="{{ old('scheduled_at', \Carbon\Carbon::parse($activity->scheduled_at)->format('Y-m-d\TH:i')) }}" class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676] transition">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Categoria *</label>
                <select name="category" required class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676] transition">
                    <option value="cardio" {{ old('category', $activity->category) === 'cardio' ? 'selected' : '' }}>Cardio</option>
                    <option value="strength" {{ old('category', $activity->category) === 'strength' ? 'selected' : '' }}>Fuerza</option>
                    <option value="relax" {{ old('category', $activity->category) === 'relax' ? 'selected' : '' }}>Relajacion</option>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.activities') }}" class="bg-[#2A2A2A] hover:bg-[#3A3A3A] text-white px-6 py-3 rounded-xl transition">Cancelar</a>
                <button class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-6 py-3 rounded-xl transition">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Crear Actividad')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('activities.index') }}" class="text-gray-400 hover:text-[#00E676] transition">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">➕ Crear Nueva Actividad</h1>
    </div>

    <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-6">
        <form action="{{ route('activities.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Nombre de la actividad *</label>
                <input type="text" name="title" required
                       class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-[#00E676] transition"
                       placeholder="Ej: Spinning, Yoga, CrossFit...">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Espacio *</label>
                <select name="space_id" required class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676] transition">
                    <option value="">-- Seleccionar espacio --</option>
                    @foreach($spaces as $space)
                        <option value="{{ $space->id }}">{{ $space->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Fecha y hora *</label>
                <input type="datetime-local" name="scheduled_at" required
                       class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676] transition">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Categoría</label>
                <select name="category" class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676] transition">
                    <option value="cardio">Cardio</option>
                    <option value="strength">Fuerza</option>
                    <option value="relax">Relajación</option>
                </select>
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
@endsection

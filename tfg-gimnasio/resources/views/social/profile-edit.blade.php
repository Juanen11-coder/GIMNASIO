@extends('layouts.app')

@section('title', 'Editar perfil')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('perfil.show', auth()->id()) }}" class="text-gray-400 hover:text-[#00E676] transition"><i class="fas fa-arrow-left text-xl"></i></a>
        <h1 class="text-2xl font-bold text-white">Editar perfil</h1>
    </div>

    <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-6">
        <form action="{{ route('perfil.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Nombre</label>
                <input name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676]">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Objetivo fitness</label>
                <input name="fitness_goal" value="{{ old('fitness_goal', $user->fitness_goal) }}" placeholder="Ganar fuerza, perder grasa, preparar una carrera..." class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676]">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">Nivel</label>
                <select name="fitness_level" class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676]">
                    <option value="">Sin indicar</option>
                    @foreach(['principiante' => 'Principiante', 'intermedio' => 'Intermedio', 'avanzado' => 'Avanzado'] as $value => $label)
                        <option value="{{ $value }}" {{ old('fitness_level', $user->fitness_level) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Altura (cm)</label>
                    <input name="height_cm" type="number" value="{{ old('height_cm', $user->height_cm) }}" class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Peso (kg)</label>
                    <input name="weight_kg" type="number" step="0.1" value="{{ old('weight_kg', $user->weight_kg) }}" class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676]">
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('perfil.show', auth()->id()) }}" class="bg-[#2A2A2A] hover:bg-[#3A3A3A] text-white px-6 py-3 rounded-xl transition">Cancelar</a>
                <button class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-6 py-3 rounded-xl transition">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

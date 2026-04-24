@extends('layouts.app')

@section('title', 'Crear Rutina')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">

    <div class="flex items-center mb-6">
        <a href="{{ route('rutinas.index') }}" class="text-indigo-600 mr-4">← Volver</a>
        <h1 class="text-2xl font-bold text-gray-800">➕ Crear Nueva Rutina</h1>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <form action="{{ route('rutinas.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 font-medium mb-2">Nombre de la rutina *</label>
                <input type="text" name="nombre" id="nombre" required
                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Ej: Rutina Fuerza, Rutina Volumen, etc.">
            </div>

            <div class="mb-6">
                <label for="descripcion" class="block text-gray-700 font-medium mb-2">Descripción (opcional)</label>
                <textarea name="descripcion" id="descripcion" rows="3"
                          class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                          placeholder="Describe tu rutina..."></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Crear Rutina
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

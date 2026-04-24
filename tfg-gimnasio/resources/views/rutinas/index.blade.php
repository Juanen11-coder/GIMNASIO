@extends('layouts.app')

@section('title', 'Mis Rutinas')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📋 Mis Rutinas</h1>
        <a href="{{ route('rutinas.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            + Crear Nueva Rutina
        </a>
    </div>

    @if($rutinas->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($rutinas as $rutina)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $rutina->nombre }}</h2>
                        <p class="text-gray-600 text-sm mb-4">
                            📅 Creada: {{ $rutina->created_at->format('d/m/Y') }}
                        </p>

                        {{-- Resumen de días --}}
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-gray-700">📌 Días de entrenamiento:</p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($rutina->diasEntreno as $dia)
                                    <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">
                                        {{ $dia->nombre }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Botones de acción --}}
                        <div class="flex gap-3 mt-4">
                            <a href="{{ route('rutinas.edit', $rutina->id) }}"
                               class="flex-1 text-center bg-gray-100 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-200 transition text-sm">
                                ✏️ Editar
                            </a>
                            <form action="{{ route('rutinas.publish', $rutina->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition text-sm">
                                    📤 Publicar en Feed
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-12 text-center text-gray-500">
            <p class="text-lg mb-4">No tienes rutinas creadas aún.</p>
            <a href="{{ route('rutinas.create') }}"
               class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                Crear mi primera rutina
            </a>
        </div>
    @endif

</div>
@endsection

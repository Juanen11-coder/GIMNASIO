@extends('layouts.app')

@section('title', 'Salas')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="flex items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-white">Salas</h1>
            <p class="text-gray-400">Define espacios y capacidad de plazas.</p>
        </div>
        <a href="{{ route('admin.activities') }}" class="bg-[#2A2A2A] hover:bg-[#3A3A3A] text-white px-5 py-3 rounded-xl transition">Panel</a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-800 text-green-100 p-4 rounded-xl border border-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-800 text-red-100 p-4 rounded-xl border border-red-700">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.spaces.store') }}" method="POST" class="bg-[#1E1E1E] border border-[#2A2A2A] rounded-2xl p-5 mb-8 grid grid-cols-1 md:grid-cols-[1fr_160px_auto] gap-3">
        @csrf
        <input name="name" placeholder="Nombre de la sala" required class="bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676]">
        <input name="capacity" type="number" min="1" placeholder="Plazas" required class="bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676]">
        <button class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-6 py-3 rounded-xl transition">Crear</button>
    </form>

    <div class="space-y-3">
        @foreach($spaces as $space)
            <div class="bg-[#1E1E1E] border border-[#2A2A2A] rounded-2xl p-4">
                <form action="{{ route('admin.spaces.update', $space->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-[1fr_140px_auto_auto] gap-3 items-center">
                    @csrf
                    @method('PUT')
                    <input name="name" value="{{ $space->name }}" required class="bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676]">
                    <input name="capacity" type="number" min="1" value="{{ $space->capacity }}" required class="bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00E676]">
                    <button class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-5 py-3 rounded-xl transition">Guardar</button>
                    <span class="text-sm text-gray-400">{{ $space->activities_count }} clases</span>
                </form>
                <form action="{{ route('admin.spaces.destroy', $space->id) }}" method="POST" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-400 hover:text-red-300 text-sm" onclick="return confirm('Eliminar esta sala?')">Eliminar sala</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection

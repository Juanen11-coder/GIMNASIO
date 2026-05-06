@extends('layouts.app')

@section('title', 'Mis clases')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-white">Mis clases</h1>
            <p class="text-gray-400">Tus actividades reservadas y avisos de lista de espera</p>
        </div>
        <a href="{{ route('activities.index') }}" class="bg-[#2A2A2A] hover:bg-[#3A3A3A] text-white px-5 py-3 rounded-xl transition text-center">
            Ver actividades
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-800 text-green-100 p-4 rounded-xl border border-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-800 text-red-100 p-4 rounded-xl border border-red-700">{{ session('error') }}</div>
    @endif

    <h2 class="text-xl font-bold text-white mb-4">Reservadas</h2>
    @if($enrolledActivities->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach($enrolledActivities as $activity)
                <div class="bg-[#1E1E1E] border border-[#2A2A2A] rounded-2xl p-5">
                    <h3 class="text-xl font-bold text-white mb-3">{{ $activity->title }}</h3>
                    <p class="text-gray-400 mb-1"><i class="fas fa-calendar-alt text-[#00E676] mr-2"></i>{{ \Carbon\Carbon::parse($activity->scheduled_at)->format('d/m/Y H:i') }} h</p>
                    <p class="text-gray-400 mb-4"><i class="fas fa-location-dot text-[#00E676] mr-2"></i>{{ $activity->space->name ?? 'Sala' }}</p>
                    <form action="{{ route('activities.unenroll', $activity->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-xl transition">Desapuntarse</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-[#1E1E1E] border border-[#2A2A2A] rounded-2xl p-8 text-center text-gray-500 mb-10">
            Aun no te has apuntado a ninguna clase.
        </div>
    @endif

    <h2 class="text-xl font-bold text-white mb-4">Lista de espera</h2>
    @if($waitlistedActivities->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($waitlistedActivities as $activity)
                <div class="bg-[#1E1E1E] border border-yellow-700 rounded-2xl p-5">
                    <h3 class="text-xl font-bold text-white mb-3">{{ $activity->title }}</h3>
                    <p class="text-gray-400 mb-1"><i class="fas fa-calendar-alt text-yellow-400 mr-2"></i>{{ \Carbon\Carbon::parse($activity->scheduled_at)->format('d/m/Y H:i') }} h</p>
                    <p class="text-gray-400 mb-4"><i class="fas fa-location-dot text-yellow-400 mr-2"></i>{{ $activity->space->name ?? 'Sala' }}</p>
                    <form action="{{ route('activities.waitlist.leave', $activity->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="w-full bg-yellow-600 hover:bg-yellow-700 text-black font-bold py-2 rounded-xl transition">Salir de espera</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-[#1E1E1E] border border-[#2A2A2A] rounded-2xl p-8 text-center text-gray-500">
            No estas en ninguna lista de espera.
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Alumnos - ' . $activity->title)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('activities.index') }}" class="text-gray-400 hover:text-[#00E676] transition">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">👥 Alumnos apuntados</h1>
    </div>

    <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-white">{{ $activity->title }}</h2>
            <span class="bg-[#00E676]/20 text-[#00E676] text-sm font-semibold px-3 py-1 rounded-full">
                {{ $students->count() }} alumnos
            </span>
        </div>
        <p class="text-gray-400">
            <i class="fas fa-calendar-alt mr-2 text-[#00E676]"></i>
            {{ \Carbon\Carbon::parse($activity->scheduled_at)->format('d/m/Y H:i') }} h
        </p>
    </div>

    @if($students->count() > 0)
        <div class="space-y-3">
            @foreach($students as $student)
                <a href="{{ route('perfil.show', $student->id) }}"
                   class="block bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] hover:border-[#00E676] transition-all overflow-hidden">
                    <div class="flex items-center p-4 gap-4">
                        @if($student->avatar)
                            <img src="{{ $student->avatar }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-[#00E676] flex items-center justify-center text-black font-bold text-lg">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-white">{{ $student->name }}</p>
                            <p class="text-xs text-gray-500">{{ $student->email }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-[#1E1E1E] rounded-2xl p-12 text-center border border-[#2A2A2A]">
            <i class="fas fa-user-slash text-5xl text-gray-600 mb-4"></i>
            <p class="text-gray-500">No hay alumnos apuntados a esta actividad.</p>
        </div>
    @endif

    <h2 class="text-xl font-bold text-white mt-8 mb-4">Lista de espera</h2>
    @if(isset($waitlistEntries) && $waitlistEntries->count() > 0)
        <div class="space-y-3">
            @foreach($waitlistEntries as $entry)
                <a href="{{ route('perfil.show', $entry->user->id) }}"
                   class="block bg-[#1E1E1E] rounded-2xl border border-yellow-700 hover:border-yellow-400 transition-all overflow-hidden">
                    <div class="flex items-center p-4 gap-4">
                        @if($entry->user->avatar)
                            <img src="{{ $entry->user->avatar }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-yellow-500 flex items-center justify-center text-black font-bold text-lg">
                                {{ substr($entry->user->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-white">{{ $entry->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $entry->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-[#1E1E1E] rounded-2xl p-8 text-center border border-[#2A2A2A]">
            <p class="text-gray-500">No hay alumnos en lista de espera.</p>
        </div>
    @endif

</div>
@endsection

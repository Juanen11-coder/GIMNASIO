@extends('layouts.app')

@section('title', 'Panel admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-white">Panel de actividades</h1>
            <p class="text-gray-400">Gestiona clases, salas, alumnos y listas de espera.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('activities.create') }}" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-5 py-3 rounded-xl transition">Nueva clase</a>
            <a href="{{ route('admin.spaces') }}" class="bg-[#2A2A2A] hover:bg-[#3A3A3A] text-white px-5 py-3 rounded-xl transition">Salas</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-800 text-green-100 p-4 rounded-xl border border-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-800 text-red-100 p-4 rounded-xl border border-red-700">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach(['Clases' => $stats['activities'], 'Salas' => $stats['spaces'], 'Reservas' => $stats['enrollments'], 'Espera' => $stats['waitlist']] as $label => $value)
            <div class="bg-[#1E1E1E] border border-[#2A2A2A] rounded-2xl p-5">
                <p class="text-gray-400 text-sm">{{ $label }}</p>
                <p class="text-3xl font-black text-[#00E676]">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="bg-[#1E1E1E] border border-[#2A2A2A] rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-[#2A2A2A]">
            <h2 class="text-xl font-bold text-white">Proximas clases</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[720px]">
                <thead class="text-gray-400 text-sm bg-[#161616]">
                    <tr>
                        <th class="p-4">Clase</th>
                        <th class="p-4">Sala</th>
                        <th class="p-4">Horario</th>
                        <th class="p-4">Plazas</th>
                        <th class="p-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                        <tr class="border-t border-[#2A2A2A]">
                            <td class="p-4 text-white font-semibold">{{ $activity->title }}</td>
                            <td class="p-4 text-gray-300">{{ $activity->space->name ?? 'Sala' }}</td>
                            <td class="p-4 text-gray-300">{{ \Carbon\Carbon::parse($activity->scheduled_at)->format('d/m/Y H:i') }}</td>
                            <td class="p-4 text-gray-300">{{ $activity->students_count }}/{{ $activity->space->capacity ?? 0 }}</td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('activities.edit', $activity->id) }}" class="bg-[#2A2A2A] hover:bg-[#3A3A3A] text-white px-3 py-2 rounded-lg"><i class="fas fa-pen"></i></a>
                                    <a href="{{ route('activities.students', $activity->id) }}" class="bg-[#2A2A2A] hover:bg-[#3A3A3A] text-white px-3 py-2 rounded-lg"><i class="fas fa-users"></i></a>
                                    <form action="{{ route('activities.destroy', $activity->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg" onclick="return confirm('Eliminar esta actividad?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

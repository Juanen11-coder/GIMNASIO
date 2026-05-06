@extends('layouts.app')

@section('title', 'Actividades')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <div class="inline-flex items-center gap-2 bg-[#00E676]/10 px-4 py-2 rounded-full mb-4">
            <span class="w-2 h-2 bg-[#00E676] rounded-full animate-pulse"></span>
            <span class="text-[#00E676] text-sm font-semibold">CLASES Y ACTIVIDADES</span>
        </div>
        <h1 class="text-3xl md:text-5xl font-black text-white mb-4">ENCUENTRA TU <span class="text-[#00E676]">ACTIVIDAD</span></h1>
        <p class="text-gray-400 max-w-2xl mx-auto">Elige entre nuestras actividades y alcanza tus objetivos</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-800 text-green-100 p-4 rounded-xl border border-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-800 text-red-100 p-4 rounded-xl border border-red-700">{{ session('error') }}</div>
    @endif

    @auth
        <div class="flex flex-wrap justify-center gap-3 mb-8">
            <a href="{{ route('activities.mine') }}" class="inline-flex items-center justify-center bg-[#2A2A2A] text-white px-6 py-3 rounded-xl font-semibold transition hover:bg-[#3A3A3A]">
                <i class="fas fa-calendar-check mr-2"></i> Mis clases
            </a>
            @if(in_array(auth()->user()->role, ['admin', 'teacher'], true))
                <a href="{{ route('activities.create') }}" class="inline-flex items-center justify-center bg-[#00E676] text-black px-6 py-3 rounded-xl font-semibold transition hover:bg-[#00c853]">
                    <i class="fas fa-plus mr-2"></i> Crear actividad
                </a>
                <a href="{{ route('admin.activities') }}" class="inline-flex items-center justify-center bg-[#2A2A2A] text-white px-6 py-3 rounded-xl font-semibold transition hover:bg-[#3A3A3A]">
                    <i class="fas fa-gauge mr-2"></i> Panel admin
                </a>
            @endif
        </div>
    @endauth

    <div class="flex flex-wrap justify-center gap-3 mb-12">
        <button class="filter-btn active bg-[#00E676] text-black px-5 py-2 rounded-full font-semibold transition hover:bg-[#00c853]" data-filter="all">Todas</button>
        <button class="filter-btn bg-[#1E1E1E] text-white px-5 py-2 rounded-full font-semibold transition hover:bg-[#00E676] hover:text-black" data-filter="cardio">Cardio</button>
        <button class="filter-btn bg-[#1E1E1E] text-white px-5 py-2 rounded-full font-semibold transition hover:bg-[#00E676] hover:text-black" data-filter="strength">Fuerza</button>
        <button class="filter-btn bg-[#1E1E1E] text-white px-5 py-2 rounded-full font-semibold transition hover:bg-[#00E676] hover:text-black" data-filter="relax">Relajacion</button>
    </div>

    @if(isset($activities) && count($activities) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($activities as $activity)
                @php
                    $capacity = $activity->space->capacity ?? 0;
                    $enrolledCount = $activity->students_count ?? 0;
                    $availableSeats = max($capacity - $enrolledCount, 0);
                    $isEnrolled = in_array($activity->id, $enrolledActivityIds ?? [], true);
                    $isWaitlisted = in_array($activity->id, $waitlistedActivityIds ?? [], true);
                    $isFull = $capacity > 0 && $availableSeats === 0;
                @endphp
                <div class="activity-card bg-[#1E1E1E] rounded-2xl overflow-hidden border border-[#2A2A2A] hover:border-[#00E676] transition-all group" data-category="{{ strtolower($activity->category ?? 'cardio') }}">
                    <div class="h-36 bg-gradient-to-r from-[#00E676] to-[#00c853] relative">
                        @php
                            $icons = [
                                'Spinning' => 'fa-bicycle',
                                'Yoga' => 'fa-hand-peace',
                                'CrossFit' => 'fa-dumbbell',
                                'Pilates' => 'fa-person-walking',
                                'Boxing' => 'fa-fist-raised',
                                'default' => 'fa-running'
                            ];
                            $icon = $icons[$activity->title] ?? $icons['default'];
                        @endphp
                        <i class="fas {{ $icon }} absolute bottom-3 right-3 text-white text-5xl opacity-30"></i>
                    </div>

                    <div class="p-5">
                        <div class="flex justify-between items-start gap-3 mb-2">
                            <h3 class="text-xl font-bold text-white">{{ $activity->title }}</h3>
                            <span class="bg-[#00E676]/20 text-[#00E676] text-xs font-semibold px-2 py-1 rounded-full whitespace-nowrap">
                                {{ $availableSeats }}/{{ $capacity }} plazas
                            </span>
                        </div>

                        @if(($activity->waitlist_entries_count ?? 0) > 0)
                            <p class="text-xs text-yellow-400 mb-3">{{ $activity->waitlist_entries_count }} en lista de espera</p>
                        @endif

                        <div class="space-y-2 text-sm text-gray-400 mb-4">
                            <p class="flex items-center gap-2"><i class="fas fa-calendar-alt w-4 text-[#00E676]"></i>{{ \Carbon\Carbon::parse($activity->scheduled_at)->format('d/m/Y') }}</p>
                            <p class="flex items-center gap-2"><i class="fas fa-clock w-4 text-[#00E676]"></i>{{ \Carbon\Carbon::parse($activity->scheduled_at)->format('H:i') }} h</p>
                            <p class="flex items-center gap-2"><i class="fas fa-location-dot w-4 text-[#00E676]"></i>{{ $activity->space->name ?? 'Sala principal' }}</p>
                            <p class="flex items-center gap-2"><i class="fas fa-user w-4 text-[#00E676]"></i>{{ $activity->teacher->name ?? 'Entrenador asignado' }}</p>
                        </div>

                        <div class="flex flex-wrap gap-3 mt-4">
                            @auth
                                @if(in_array(auth()->user()->role, ['user', 'student'], true))
                                    @if($isEnrolled)
                                        <form action="{{ route('activities.unenroll', $activity->id) }}" method="POST" class="flex-1 min-w-40">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-xl transition flex items-center justify-center gap-2">
                                                <i class="fas fa-xmark"></i> Desapuntarse
                                            </button>
                                        </form>
                                    @elseif($isWaitlisted)
                                        <form action="{{ route('activities.waitlist.leave', $activity->id) }}" method="POST" class="flex-1 min-w-40">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-black font-bold py-2 rounded-xl transition flex items-center justify-center gap-2">
                                                <i class="fas fa-clock"></i> Salir espera
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('activities.enroll', $activity->id) }}" method="POST" class="flex-1 min-w-40">
                                            @csrf
                                            <button type="submit" class="w-full {{ $isFull ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-[#00E676] hover:bg-[#00c853]' }} text-black font-bold py-2 rounded-xl transition flex items-center justify-center gap-2">
                                                <i class="fas {{ $isFull ? 'fa-list' : 'fa-check-circle' }}"></i> {{ $isFull ? 'Lista espera' : 'Apuntarse' }}
                                            </button>
                                        </form>
                                    @endif
                                @endif

                                @if(in_array(auth()->user()->role, ['admin', 'teacher'], true))
                                    <a href="{{ route('activities.edit', $activity->id) }}" class="bg-[#2A2A2A] hover:bg-[#3A3A3A] text-white px-4 py-2 rounded-xl transition flex items-center justify-center"><i class="fas fa-pen"></i></a>
                                    <a href="{{ route('activities.students', $activity->id) }}" class="bg-[#2A2A2A] hover:bg-[#3A3A3A] text-white px-4 py-2 rounded-xl transition flex items-center justify-center"><i class="fas fa-users"></i></a>
                                    <form action="{{ route('activities.destroy', $activity->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl transition flex items-center justify-center" onclick="return confirm('Eliminar esta actividad?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="w-full bg-[#00E676] hover:bg-[#00c853] text-black font-bold py-2 rounded-xl transition flex items-center justify-center gap-2">
                                    <i class="fas fa-sign-in-alt"></i> Inicia sesion para apuntarte
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-[#1E1E1E] rounded-2xl p-12 text-center border border-[#2A2A2A]">
            <i class="fas fa-calendar-times text-5xl text-gray-600 mb-4"></i>
            <p class="text-gray-500">No hay actividades disponibles en este momento.</p>
        </div>
    @endif
</div>

<script>
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('bg-[#00E676]', 'text-black');
                b.classList.add('bg-[#1E1E1E]', 'text-white');
            });
            this.classList.remove('bg-[#1E1E1E]', 'text-white');
            this.classList.add('bg-[#00E676]', 'text-black');

            document.querySelectorAll('.activity-card').forEach(card => {
                card.style.display = filter === 'all' || card.dataset.category === filter ? 'block' : 'none';
            });
        });
    });
</script>
@endsection

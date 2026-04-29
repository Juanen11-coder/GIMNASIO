@extends('layouts.app')

@section('title', 'Actividades')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- Cabecera --}}
    <div class="text-center mb-12">
        <div class="inline-flex items-center gap-2 bg-[#00E676]/10 px-4 py-2 rounded-full mb-4">
            <span class="w-2 h-2 bg-[#00E676] rounded-full animate-pulse"></span>
            <span class="text-[#00E676] text-sm font-semibold">CLASES Y ACTIVIDADES</span>
        </div>
        <h1 class="text-3xl md:text-5xl font-black text-white mb-4">ENCUENTRA TU <span class="text-[#00E676]">ACTIVIDAD</span></h1>
        <p class="text-gray-400 max-w-2xl mx-auto">Elige entre nuestras actividades y alcanza tus objetivos</p>
    </div>

    {{-- Filtros (opcional) --}}
    <div class="flex flex-wrap justify-center gap-3 mb-12">
        <button class="filter-btn active bg-[#00E676] text-black px-5 py-2 rounded-full font-semibold transition hover:bg-[#00c853]" data-filter="all">Todas</button>
        <button class="filter-btn bg-[#1E1E1E] text-white px-5 py-2 rounded-full font-semibold transition hover:bg-[#00E676] hover:text-black" data-filter="cardio">Cardio</button>
        <button class="filter-btn bg-[#1E1E1E] text-white px-5 py-2 rounded-full font-semibold transition hover:bg-[#00E676] hover:text-black" data-filter="strength">Fuerza</button>
        <button class="filter-btn bg-[#1E1E1E] text-white px-5 py-2 rounded-full font-semibold transition hover:bg-[#00E676] hover:text-black" data-filter="relax">Relajación</button>
    </div>

    {{-- Lista de actividades --}}
    @if(isset($activities) && count($activities) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($activities as $activity)
                <div class="activity-card bg-[#1E1E1E] rounded-2xl overflow-hidden border border-[#2A2A2A] hover:border-[#00E676] transition-all hover:transform hover:-translate-y-1 group" data-category="{{ strtolower($activity->category ?? 'cardio') }}">

                    {{-- Imagen o gradiente --}}
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
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-white">{{ $activity->title }}</h3>
                            <span class="bg-[#00E676]/20 text-[#00E676] text-xs font-semibold px-2 py-1 rounded-full">12 plazas</span>
                        </div>

                        <div class="space-y-2 text-sm text-gray-400 mb-4">
                            <p class="flex items-center gap-2">
                                <i class="fas fa-calendar-alt w-4 text-[#00E676]"></i>
                                {{ \Carbon\Carbon::parse($activity->scheduled_at)->format('d/m/Y') }}
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fas fa-clock w-4 text-[#00E676]"></i>
                                {{ \Carbon\Carbon::parse($activity->scheduled_at)->format('H:i') }} h
                            </p>
                            @if($activity->space)
                                <p class="flex items-center gap-2">
                                    <i class="fas fa-location-dot w-4 text-[#00E676]"></i>
                                    {{ $activity->space->name ?? 'Sala principal' }}
                                </p>
                            @endif
                            <p class="flex items-center gap-2">
                                <i class="fas fa-user w-4 text-[#00E676]"></i>
                                {{ $activity->teacher->name ?? 'Entrenador asignado' }}
                            </p>
                        </div>

                        <div class="flex gap-3 mt-4">
                            <form action="{{ route('activities.enroll', $activity->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-[#00E676] hover:bg-[#00c853] text-black font-bold py-2 rounded-xl transition flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i> Apuntarse
                                </button>
                            </form>
                            @auth
                                @if(auth()->user()->id == ($activity->user_id ?? null))
                                    <a href="{{ route('activities.students', $activity->id) }}" class="bg-[#2A2A2A] hover:bg-[#3A3A3A] text-white px-4 py-2 rounded-xl transition flex items-center justify-center">
                                        <i class="fas fa-users"></i>
                                    </a>
                                @endif
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
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('activities.create') }}" class="inline-block mt-4 text-[#00E676] hover:text-[#00c853]">
                        Crear primera actividad →
                    </a>
                @endif
            @endauth
        </div>
    @endif

    {{-- Ver más --}}
    @if(isset($activities) && count($activities) > 6)
        <div class="text-center mt-12">
            <button class="bg-transparent border border-[#00E676] text-[#00E676] hover:bg-[#00E676] hover:text-black font-semibold px-8 py-3 rounded-xl transition">
                Ver más actividades
            </button>
        </div>
    @endif
</div>

<script>
    // Filtros
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;

            // Actualizar estilo activo
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('bg-[#00E676]', 'text-black');
                b.classList.add('bg-[#1E1E1E]', 'text-white');
            });
            this.classList.remove('bg-[#1E1E1E]', 'text-white');
            this.classList.add('bg-[#00E676]', 'text-black');

            // Filtrar tarjetas
            document.querySelectorAll('.activity-card').forEach(card => {
                if (filter === 'all' || card.dataset.category === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection

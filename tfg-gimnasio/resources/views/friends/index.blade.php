@extends('layouts.app')

@section('title', 'Mis Amigos')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">

    {{-- Cabecera --}}
    <div class="text-center mb-10">
        <div class="inline-flex items-center gap-2 bg-[#00E676]/10 px-4 py-2 rounded-full mb-4">
            <span class="w-2 h-2 bg-[#00E676] rounded-full animate-pulse"></span>
            <span class="text-[#00E676] text-sm font-semibold">COMUNIDAD</span>
        </div>
        <h1 class="text-3xl md:text-4xl font-black text-white mb-3">MIS <span class="text-[#00E676]">AMIGOS</span></h1>
        <p class="text-gray-400">Conecta con otros miembros y comparte tus entrenos</p>
    </div>

    {{-- Botón buscar --}}
    <div class="flex justify-end mb-6">
        <a href="{{ route('friends.search') }}" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-4 py-2 rounded-xl transition flex items-center gap-2">
            <i class="fas fa-search"></i> Buscar personas
        </a>
    </div>

    {{-- TABS --}}
    <div class="flex border-b border-[#2A2A2A] mb-6">
        <button class="tab-btn active px-6 py-3 text-white font-semibold border-b-2 border-[#00E676] transition" data-tab="friends">
            <i class="fas fa-users mr-2"></i> Amigos
            @if(isset($friends) && $friends->count() > 0)
                <span class="ml-1 bg-[#00E676] text-black text-xs px-2 py-0.5 rounded-full">{{ $friends->count() }}</span>
            @endif
        </button>
        <button class="tab-btn px-6 py-3 text-gray-400 hover:text-white font-semibold transition" data-tab="received">
            <i class="fas fa-inbox mr-2"></i> Recibidas
            @if(isset($receivedRequests) && $receivedRequests->count() > 0)
                <span class="ml-1 bg-[#00E676] text-black text-xs px-2 py-0.5 rounded-full">{{ $receivedRequests->count() }}</span>
            @endif
        </button>
        <button class="tab-btn px-6 py-3 text-gray-400 hover:text-white font-semibold transition" data-tab="sent">
            <i class="fas fa-paper-plane mr-2"></i> Enviadas
            @if(isset($sentRequests) && $sentRequests->count() > 0)
                <span class="ml-1 bg-gray-600 text-white text-xs px-2 py-0.5 rounded-full">{{ $sentRequests->count() }}</span>
            @endif
        </button>
    </div>

    {{-- TAB: MIS AMIGOS --}}
    <div id="tab-friends" class="tab-content">
        @if(isset($friends) && $friends->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($friends as $friend)
                    <a href="{{ route('perfil.show', $friend->id) }}"
                       class="flex items-center gap-4 bg-[#1E1E1E] rounded-2xl p-4 border border-[#2A2A2A] hover:border-[#00E676] transition-all group">
                        @if($friend->avatar)
                            <img src="{{ $friend->avatar }}" class="w-14 h-14 rounded-full object-cover">
                        @else
                            <div class="w-14 h-14 rounded-full bg-[#00E676] flex items-center justify-center text-black font-bold text-xl">
                                {{ substr($friend->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-semibold text-white group-hover:text-[#00E676] transition">{{ $friend->name }}</p>
                            <p class="text-xs text-gray-500">{{ $friend->email }}</p>
                        </div>
                        <div class="text-gray-500 group-hover:text-[#00E676] transition">
                            <i class="fas fa-comment"></i>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-[#1E1E1E] rounded-2xl p-12 text-center border border-[#2A2A2A]">
                <i class="fas fa-user-friends text-5xl text-gray-600 mb-4"></i>
                <p class="text-gray-500">No tienes amigos aún.</p>
                <p class="text-gray-500 text-sm mt-2">¡Envía solicitudes a otros usuarios para empezar!</p>
            </div>
        @endif
    </div>

    {{-- TAB: SOLICITUDES RECIBIDAS --}}
    <div id="tab-received" class="tab-content hidden">
        @if(isset($receivedRequests) && $receivedRequests->count() > 0)
            <div class="space-y-3">
                @foreach($receivedRequests as $request)
                    <div class="flex items-center justify-between bg-[#1E1E1E] rounded-2xl p-4 border border-[#2A2A2A]">
                        <div class="flex items-center gap-4">
                            @if($request->user->avatar)
                                <img src="{{ $request->user->avatar }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-[#00E676] flex items-center justify-center text-black font-bold text-lg">
                                    {{ substr($request->user->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-white">{{ $request->user->name }}</p>
                                <p class="text-xs text-gray-500">te ha enviado una solicitud</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <form action="{{ route('friends.accept', $request) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-4 py-2 rounded-xl transition">
                                    ✅ Aceptar
                                </button>
                            </form>
                            <form action="{{ route('friends.decline', $request) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-gray-700 hover:bg-red-600 text-white px-4 py-2 rounded-xl transition">
                                    ❌ Rechazar
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-[#1E1E1E] rounded-2xl p-12 text-center border border-[#2A2A2A]">
                <i class="fas fa-inbox text-5xl text-gray-600 mb-4"></i>
                <p class="text-gray-500">No tienes solicitudes pendientes.</p>
            </div>
        @endif
    </div>

    {{-- TAB: SOLICITUDES ENVIADAS --}}
    <div id="tab-sent" class="tab-content hidden">
        @if(isset($sentRequests) && $sentRequests->count() > 0)
            <div class="space-y-3">
                @foreach($sentRequests as $request)
                    <div class="flex items-center justify-between bg-[#1E1E1E] rounded-2xl p-4 border border-[#2A2A2A] opacity-75">
                        <div class="flex items-center gap-4">
                            @if($request->friend->avatar)
                                <img src="{{ $request->friend->avatar }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gray-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($request->friend->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-white">{{ $request->friend->name }}</p>
                                <p class="text-xs text-gray-500">solicitud enviada</p>
                            </div>
                        </div>
                        <span class="bg-gray-600 text-white text-xs px-3 py-1 rounded-full">
                            <i class="fas fa-clock mr-1"></i> Pendiente
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-[#1E1E1E] rounded-2xl p-12 text-center border border-[#2A2A2A]">
                <i class="fas fa-paper-plane text-5xl text-gray-600 mb-4"></i>
                <p class="text-gray-500">No has enviado ninguna solicitud.</p>
            </div>
        @endif
    </div>

    {{-- SECCIÓN DE SUGERENCIAS --}}
    @if(isset($suggestions) && $suggestions->count() > 0)
        <div class="mt-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-white">
                    <i class="fas fa-lightbulb text-[#00E676] mr-2"></i> Sugerencias para ti
                </h2>
                <a href="{{ route('friends.search') }}" class="text-sm text-[#00E676] hover:text-[#00c853] transition">
                    Ver más →
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($suggestions as $suggestion)
                    <div class="flex items-center justify-between bg-[#1E1E1E] rounded-2xl p-3 border border-[#2A2A2A] hover:border-[#00E676] transition-all">
                        <div class="flex items-center gap-3">
                            @if($suggestion->avatar)
                                <img src="{{ $suggestion->avatar }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-[#00E676]/20 flex items-center justify-center text-[#00E676] font-bold text-lg">
                                    {{ substr($suggestion->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-white">{{ $suggestion->name }}</p>
                                <p class="text-xs text-gray-500">{{ $suggestion->posts->count() }} publicaciones</p>
                            </div>
                        </div>
                        <form action="{{ route('friends.request', $suggestion) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-4 py-2 rounded-xl transition text-sm">
                                <i class="fas fa-user-plus mr-1"></i> Seguir
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<script>
    // Tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;

            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('active', 'border-[#00E676]', 'text-white');
                b.classList.add('text-gray-400', 'border-b-0');
            });
            this.classList.add('active', 'border-[#00E676]', 'text-white');
            this.classList.remove('text-gray-400');

            document.getElementById('tab-friends').classList.add('hidden');
            document.getElementById('tab-received').classList.add('hidden');
            document.getElementById('tab-sent').classList.add('hidden');

            document.getElementById(`tab-${tab}`).classList.remove('hidden');
        });
    });
</script>
@endsection

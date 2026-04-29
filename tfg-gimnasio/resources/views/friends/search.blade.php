@extends('layouts.app')

@section('title', 'Buscar Amigos')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">

    {{-- Cabecera --}}
    <div class="text-center mb-10">
        <div class="inline-flex items-center gap-2 bg-[#00E676]/10 px-4 py-2 rounded-full mb-4">
            <span class="w-2 h-2 bg-[#00E676] rounded-full animate-pulse"></span>
            <span class="text-[#00E676] text-sm font-semibold">DESCUBRE</span>
        </div>
        <h1 class="text-3xl md:text-4xl font-black text-white mb-3">BUSCAR <span class="text-[#00E676]">MIEMBROS</span></h1>
        <p class="text-gray-400">Encuentra personas y amplía tu red fitness</p>
    </div>

    {{-- Barra de búsqueda --}}
    <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-4 mb-8">
        <form action="{{ route('friends.search') }}" method="GET" class="flex gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-500"></i>
                </div>
                <input type="text"
                       name="q"
                       value="{{ $query ?? '' }}"
                       placeholder="Buscar por nombre o email..."
                       class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl pl-12 pr-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-[#00E676] transition">
            </div>
            <button type="submit" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-6 py-3 rounded-xl transition flex items-center gap-2">
                <i class="fas fa-search"></i> Buscar
            </button>
        </form>
    </div>

    {{-- Resultados --}}
    @if(isset($users))
        <div class="space-y-3">
            @forelse($users as $user)
                <div class="flex items-center justify-between bg-[#1E1E1E] rounded-2xl p-4 border border-[#2A2A2A] hover:border-[#00E676] transition-all">
                    <div class="flex items-center gap-4">
                        {{-- Avatar --}}
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" class="w-14 h-14 rounded-full object-cover">
                        @else
                            <div class="w-14 h-14 rounded-full bg-[#00E676] flex items-center justify-center text-black font-bold text-xl">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif

                        <div>
                            <p class="font-semibold text-white text-lg">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            <p class="text-xs text-gray-600 mt-1">
                                <i class="fas fa-dumbbell mr-1"></i> {{ $user->posts->count() }} publicaciones
                            </p>
                        </div>
                    </div>

                    <div>
                        {{-- Estado de amistad --}}
                        @php
                            $friendship = \App\Models\Friendship::where(function($q) use ($user) {
                                $q->where('user_id', auth()->id())->where('friend_id', $user->id);
                            })->orWhere(function($q) use ($user) {
                                $q->where('user_id', $user->id)->where('friend_id', auth()->id());
                            })->first();
                        @endphp

                        @if($friendship)
                            @if($friendship->status == 'accepted')
                                <span class="bg-[#00E676]/20 text-[#00E676] px-4 py-2 rounded-xl text-sm font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i> Amigos
                                </span>
                            @elseif($friendship->status == 'pending')
                                @if($friendship->user_id == auth()->id())
                                    <span class="bg-gray-600 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                                        <i class="fas fa-clock mr-1"></i> Enviada
                                    </span>
                                @else
                                    <div class="flex gap-2">
                                        <form action="{{ route('friends.accept', $friendship) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-[#00E676] hover:bg-[#00c853] text-black px-4 py-2 rounded-xl transition">
                                                <i class="fas fa-check mr-1"></i> Aceptar
                                            </button>
                                        </form>
                                        <form action="{{ route('friends.decline', $friendship) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-[#2A2A2A] hover:bg-red-600 text-white px-4 py-2 rounded-xl transition">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        @else
                            <form action="{{ route('friends.request', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-6 py-2 rounded-xl transition flex items-center gap-2">
                                    <i class="fas fa-user-plus"></i> Agregar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-[#1E1E1E] rounded-2xl p-12 text-center border border-[#2A2A2A]">
                    <i class="fas fa-user-slash text-5xl text-gray-600 mb-4"></i>
                    <p class="text-gray-500">No se encontraron usuarios.</p>
                    @if(isset($query) && $query)
                        <p class="text-gray-500 text-sm mt-2">No hay resultados para "{{ $query }}"</p>
                    @endif
                    <a href="{{ route('friends.search') }}" class="inline-block mt-4 text-[#00E676] hover:text-[#00c853]">
                        Limpiar búsqueda →
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if(isset($users) && method_exists($users, 'links'))
            <div class="mt-8">
                {{ $users->links() }}
            </div>
        @endif
    @else
        {{-- Sin búsqueda aún --}}
        <div class="bg-[#1E1E1E] rounded-2xl p-12 text-center border border-[#2A2A2A]">
            <i class="fas fa-search text-5xl text-gray-600 mb-4"></i>
            <p class="text-gray-500">Escribe un nombre o email para buscar personas</p>
            <p class="text-gray-500 text-sm mt-2">Conecta con otros miembros de la comunidad</p>
        </div>
    @endif

</div>
@endsection

@extends('layouts.app')

@section('title', 'Mis Chats')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">

    <h1 class="text-2xl font-bold text-white mb-6">💬 Mis Chats</h1>

    @if(isset($conversations) && count($conversations) > 0)
        <div class="space-y-3">
            @foreach($conversations as $conv)
                <a href="{{ route('chat.show', $conv['friend']['id']) }}"
                   class="block bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] hover:border-[#00E676] transition-all overflow-hidden">
                    <div class="flex items-center p-4 gap-4">

                        {{-- Avatar --}}
                        @if($conv['friend']['avatar'])
                            <img src="{{ $conv['friend']['avatar'] }}"
                                 alt="{{ $conv['friend']['name'] }}"
                                 class="w-14 h-14 rounded-full object-cover">
                        @else
                            <div class="w-14 h-14 rounded-full bg-[#00E676] flex items-center justify-center text-black font-bold text-xl">
                                {{ substr($conv['friend']['name'], 0, 1) }}
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <h3 class="font-semibold text-white text-lg truncate">
                                    {{ $conv['friend']['name'] }}
                                </h3>
                                <span class="text-xs text-gray-500 whitespace-nowrap ml-2">
                                    {{ $conv['last_message']->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-400 truncate">
                                {{ $conv['last_message']->message }}
                            </p>
                        </div>

                        @if($conv['unread_count'] > 0)
                            <span class="bg-[#00E676] text-black text-xs font-bold rounded-full px-2 py-1 min-w-[20px] text-center">
                                {{ $conv['unread_count'] }}
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-[#1E1E1E] rounded-2xl p-12 text-center border border-[#2A2A2A]">
            <i class="fas fa-comments text-5xl text-gray-600 mb-4"></i>
            <p class="text-gray-500">No tienes conversaciones activas.</p>
            <p class="text-gray-500 text-sm mt-2">¡Envía solicitudes de amistad para empezar a chatear!</p>
            <a href="{{ route('friends.index') }}" class="inline-block mt-4 text-[#00E676] hover:text-[#00c853]">
                Ver amigos →
            </a>
        </div>
    @endif

</div>
@endsection

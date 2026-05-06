@extends('layouts.app')

@section('title', isset($otherUser) && $otherUser ? 'Chat con ' . $otherUser['name'] : 'Chat')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">

    {{-- Cabecera del chat --}}
    <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] mb-4 p-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('chats.index') }}"
               class="text-gray-400 hover:text-[#00E676] transition text-2xl">
                ←
            </a>

            @if(isset($otherUser) && $otherUser)
                @if($otherUser['avatar'])
                    <img src="{{ $otherUser['avatar'] }}"
                         alt="{{ $otherUser['name'] }}"
                         class="w-12 h-12 rounded-full object-cover">
                @else
                    <div class="w-12 h-12 rounded-full bg-[#00E676] flex items-center justify-center text-black font-bold text-lg">
                        {{ substr($otherUser['name'], 0, 1) }}
                    </div>
                @endif
                <h1 class="text-xl font-bold text-white">{{ $otherUser['name'] }}</h1>
            @else
                <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center text-gray-400">
                    ?
                </div>
                <h1 class="text-xl font-bold text-gray-500">Usuario desconocido</h1>
            @endif
        </div>
    </div>

    {{-- Mensajes de éxito/error --}}
    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-500 px-4 py-3 rounded-xl mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/20 border border-red-500 text-red-500 px-4 py-3 rounded-xl mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Contenedor de mensajes --}}
    <div id="messages-container"
         class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-4 mb-4 h-[500px] overflow-y-auto">

        @if(isset($messages) && count($messages) > 0)
            @foreach($messages as $message)
                @php
                    $isMine = ($message->sender_id == auth()->id());
                    $user = $isMine ? $message->receiver : $message->sender;
                @endphp

                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} mb-3">

                    {{-- Avatar del otro (solo si no es mío) --}}
                    @if(!$isMine)
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" class="w-8 h-8 rounded-full object-cover mr-2 self-end">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-white text-xs font-bold mr-2 self-end">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    @endif

                    {{-- Burbuja del mensaje --}}
                    <div class="max-w-[70%]">
                        <div class="{{ $isMine ? 'bg-[#00E676] text-black' : 'bg-[#2A2A2A] text-white' }}
                                    rounded-2xl px-4 py-2">
                            <p class="text-sm">{{ $message->message }}</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 text-{{ $isMine ? 'right' : 'left' }}">
                            {{ $message->created_at->format('H:i') }}
                        </p>
                    </div>

                    {{-- Avatar mío (solo si es mío) --}}
                    @if($isMine)
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" class="w-8 h-8 rounded-full object-cover ml-2 self-end">
                        @else
                            <div class="w-8 h-8 rounded-full bg-[#00E676] flex items-center justify-center text-black text-xs font-bold ml-2 self-end">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
        @else
            <div class="text-center text-gray-500 py-12">
                <i class="fas fa-comment-dots text-4xl mb-3"></i>
                <p>No hay mensajes aún. ¡Escribe el primero!</p>
            </div>
        @endif
    </div>

    {{-- Formulario para enviar mensaje --}}
    <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-4">
        <form action="{{ route('chat.send', $conversationId) }}" method="POST" class="flex gap-3">
            @csrf
            <input type="text"
                   name="message"
                   class="flex-1 bg-[#2A2A2A] border border-[#3A3A2A] rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-[#00E676] transition"
                   placeholder="Escribe un mensaje..."
                   autocomplete="off"
                   required>
            <button type="submit"
                    class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-6 py-3 rounded-xl transition transform hover:scale-105">
                <i class="fas fa-paper-plane mr-2"></i> Enviar
            </button>
        </form>
    </div>
</div>

<script>
    // Auto-scroll al final del chat
    const container = document.getElementById('messages-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
</script>
@endsection

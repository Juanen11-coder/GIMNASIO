@extends('layouts.app')

@section('title', isset($otherUser) && $otherUser ? 'Chat con ' . $otherUser['name'] : 'Chat')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">

    {{-- Cabecera del chat --}}
    <div class="bg-white rounded-xl shadow-md mb-4 p-4">
        <div class="flex items-center">
            <a href="{{ route('chats.index') }}"
               class="text-gray-500 mr-3 hover:text-gray-700 text-xl">
                ←
            </a>

            @if(isset($otherUser) && $otherUser)
                <img src="{{ $otherUser['avatar'] }}"
                     alt="{{ $otherUser['name'] }}"
                     class="w-10 h-10 rounded-full mr-3">
                <h1 class="text-xl font-bold text-gray-800">{{ $otherUser['name'] }}</h1>
            @else
                <div class="w-10 h-10 rounded-full bg-gray-300 mr-3 flex items-center justify-center text-gray-500">
                    ?
                </div>
                <h1 class="text-xl font-bold text-gray-500">Usuario desconocido</h1>
            @endif
        </div>
    </div>

    {{-- Mensajes de éxito/error --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Contenedor de mensajes --}}
    <div id="messages-container"
         class="bg-white rounded-xl shadow-md p-4 mb-4 h-96 overflow-y-auto">

        @if(isset($messages) && count($messages) > 0)
            @foreach($messages as $message)
                @php
                    $isMine = ($message->sender_id == auth()->id());
                    $user = $isMine ? $message->receiver : $message->sender;
                @endphp

                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} mb-3">

                    {{-- Avatar del otro usuario (solo si no es mío) --}}
                    @if(!$isMine)
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?background=gray&color=fff&name=' . urlencode($user->name) }}"
                             class="w-8 h-8 rounded-full mr-2 self-end">
                    @endif

                    {{-- Burbuja del mensaje --}}
                    <div class="max-w-[70%]">
                        <div class="{{ $isMine ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-800' }}
                                    rounded-lg px-4 py-2">
                            <p class="text-sm">{{ $message->message }}</p>
                        </div>
                        <p class="text-xs text-gray-400 mt-1 text-{{ $isMine ? 'right' : 'left' }}">
                            {{ $message->created_at->format('H:i') }}
                        </p>
                    </div>

                    {{-- Avatar mío (solo si es mío) --}}
                    @if($isMine)
                        <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?background=6366f1&color=fff&name=' . urlencode(auth()->user()->name) }}"
                             class="w-8 h-8 rounded-full ml-2 self-end">
                    @endif
                </div>
            @endforeach
        @else
            <div class="text-center text-gray-500 py-8">
                No hay mensajes aún. ¡Escribe el primero!
            </div>
        @endif
    </div>

    {{-- Formulario para enviar mensaje --}}
    <div class="bg-white rounded-xl shadow-md p-4">
        <form action="{{ route('chat.send', $conversationId) }}"
              method="POST"
              class="flex gap-2">
            @csrf
            <input type="text"
                   name="message"
                   class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   placeholder="Escribe un mensaje..."
                   required>
            <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                Enviar
            </button>
        </form>
    </div>
</div>

<script>
    const container = document.getElementById('messages-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
</script>
@endsection

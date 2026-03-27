@extends('layouts.app')

@section('title', 'Mis Chats')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">💬 Mis Chats</h1>

    @if(isset($myConversations) && count($myConversations) > 0)
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @foreach($myConversations as $conv)
            <a href="{{ route('chat.show', $conv['id']) }}"
               class="block border-b hover:bg-gray-50 transition">
                <div class="flex items-center p-4">

                    {{-- Avatar del otro usuario (con comprobación) --}}
                    @if(isset($conv['other_user']) && $conv['other_user'])
                        <img src="{{ $conv['other_user']['avatar'] }}"
                             alt="{{ $conv['other_user']['name'] }}"
                             class="w-12 h-12 rounded-full mr-4">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gray-300 mr-4 flex items-center justify-center text-gray-500">
                            ?
                        </div>
                    @endif

                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            {{-- Nombre del otro usuario (con comprobación) --}}
                            @if(isset($conv['other_user']) && $conv['other_user'])
                                <h3 class="font-semibold text-gray-800">
                                    {{ $conv['other_user']['name'] }}
                                </h3>
                            @else
                                <h3 class="font-semibold text-gray-500">
                                    Usuario desconocido
                                </h3>
                            @endif

                            {{-- Fecha del último mensaje --}}
                            @if(isset($conv['last_message_time']))
                                <span class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($conv['last_message_time'])->diffForHumans() }}
                                </span>
                            @endif
                        </div>

                        {{-- Último mensaje --}}
                        <p class="text-sm text-gray-500 truncate">
                            {{ $conv['last_message'] ?? 'Sin mensajes' }}
                        </p>
                    </div>

                    {{-- Mensajes no leídos --}}
                    @if(isset($conv['unread']) && $conv['unread'] > 0)
                        <span class="bg-indigo-600 text-white text-xs rounded-full px-2 py-1 ml-2">
                            {{ $conv['unread'] }}
                        </span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-8 text-center text-gray-500">
            No tienes conversaciones activas.
        </div>
    @endif
</div>
@endsection

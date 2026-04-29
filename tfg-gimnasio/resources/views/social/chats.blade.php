@extends('layouts.app')

@section('title', 'Mis Chats')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">💬 Mis Chats</h1>

    @if(isset($conversations) && count($conversations) > 0)
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @foreach($conversations as $conv)
                <a href="{{ route('chat.show', $conv['friend']['id']) }}"
                   class="block border-b hover:bg-gray-50 transition">
                    <div class="flex items-center p-4">

                        {{-- Avatar --}}
                        @if($conv['friend']['avatar'])
                            <img src="{{ $conv['friend']['avatar'] }}"
                                 alt="{{ $conv['friend']['name'] }}"
                                 class="w-12 h-12 rounded-full mr-4">
                        @else
                            <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold mr-4">
                                {{ substr($conv['friend']['name'], 0, 1) }}
                            </div>
                        @endif

                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-800">
                                    {{ $conv['friend']['name'] }}
                                </h3>
                                <span class="text-xs text-gray-400">
                                    {{ $conv['last_message']->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 truncate">
                                {{ $conv['last_message']->message }}
                            </p>
                        </div>

                        @if($conv['unread_count'] > 0)
                            <span class="bg-indigo-600 text-white text-xs rounded-full px-2 py-1 ml-2">
                                {{ $conv['unread_count'] }}
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

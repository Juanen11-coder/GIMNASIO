@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold">Resultados de búsqueda: "{{ $query }}"</h1>
                    <a href="{{ route('friends.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Volver</a>
                </div>

                @if($users->count() > 0)
                    <div class="space-y-4">
                        @foreach($users as $user)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" alt="Avatar" class="w-12 h-12 rounded-full mr-4">
                                        <div>
                                            <h3 class="text-lg font-semibold">{{ $user->name }}</h3>
                                            <p class="text-gray-600">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        @if($user->is_friend)
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Amigo</span>
                                            <a href="{{ route('chat.show', ['conversationId' => $user->id]) }}" class="ml-2 bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">Chat</a>
                                        @elseif($user->has_pending)
                                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">Pendiente</span>
                                        @else
                                            <form action="{{ route('friends.request', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Enviar solicitud</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No se encontraron usuarios con ese nombre.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-6">Amigos</h1>

                <!-- Buscador -->
                <div class="mb-6">
                    <form action="{{ route('friends.search') }}" method="GET" class="flex gap-2">
                        <input type="text" name="query" placeholder="Buscar personas..." class="flex-1 border rounded-lg px-3 py-2" required>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Buscar</button>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Amigos -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Mis Amigos</h2>
                        @if($friends->count() > 0)
                            @foreach($friends as $friend)
                                <div class="bg-gray-50 p-4 rounded-lg mb-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img src="{{ $friend->avatar ? asset('storage/' . $friend->avatar) : asset('images/default-avatar.png') }}" alt="Avatar" class="w-10 h-10 rounded-full mr-3">
                                            <span>{{ $friend->name }}</span>
                                        </div>
                                        <a href="{{ route('chat.show', ['conversationId' => $friend->id]) }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">Chat</a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500">No tienes amigos aún.</p>
                        @endif
                    </div>

                    <!-- Solicitudes pendientes -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Solicitudes Pendientes</h2>
                        @if($receivedRequests->count() > 0)
                            @foreach($receivedRequests as $request)
                                <div class="bg-yellow-50 p-4 rounded-lg mb-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img src="{{ $request->user->avatar ? asset('storage/' . $request->user->avatar) : asset('images/default-avatar.png') }}" alt="Avatar" class="w-10 h-10 rounded-full mr-3">
                                            <span>{{ $request->user->name }}</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <form action="{{ route('friends.accept', $request) }}" method="POST" class="inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded text-sm hover:bg-green-700">Aceptar</button>
                                            </form>
                                            <form action="{{ route('friends.reject', $request) }}" method="POST" class="inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded text-sm hover:bg-red-700">Rechazar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500">No hay solicitudes pendientes.</p>
                        @endif

                        @if($sentRequests->count() > 0)
                            <h3 class="text-lg font-semibold mt-4 mb-2">Enviadas</h3>
                            @foreach($sentRequests as $request)
                                <div class="bg-blue-50 p-4 rounded-lg mb-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img src="{{ $request->friend->avatar ? asset('storage/' . $request->friend->avatar) : asset('images/default-avatar.png') }}" alt="Avatar" class="w-10 h-10 rounded-full mr-3">
                                            <span>{{ $request->friend->name }}</span>
                                        </div>
                                        <form action="{{ route('friends.cancel', $request) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-gray-600 text-white px-2 py-1 rounded text-sm hover:bg-gray-700">Cancelar</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Sugerencias -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Sugerencias</h2>
                        @if($suggestedUsers->count() > 0)
                            @foreach($suggestedUsers as $user)
                                <div class="bg-gray-50 p-4 rounded-lg mb-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" alt="Avatar" class="w-10 h-10 rounded-full mr-3">
                                            <span>{{ $user->name }}</span>
                                        </div>
                                        <form action="{{ route('friends.request', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Seguir</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500">No hay sugerencias disponibles.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

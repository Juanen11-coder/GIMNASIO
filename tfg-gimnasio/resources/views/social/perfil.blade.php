@extends('layouts.app')

@section('title', isset($user) && $user ? $user->name . ' - Perfil' : 'Perfil')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    @if(session('success'))
        <div class="mb-6 bg-green-800 text-green-100 p-4 rounded-xl border border-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tarjeta de perfil --}}
    <div class="bg-[#1E1E1E] rounded-2xl border border-[#2A2A2A] p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center gap-6">

            {{-- Avatar --}}
            @if (isset($user) && $user)
                @if ($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                        class="w-28 h-28 rounded-full object-cover border-4 border-[#00E676]">
                @else
                    <div class="w-28 h-28 rounded-full bg-[#00E676] flex items-center justify-center text-black text-4xl font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
            @else
                <div class="w-28 h-28 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 text-2xl">
                    ?
                </div>
            @endif

            {{-- Información --}}
            <div class="flex-1 text-center md:text-left">
                @if (isset($user) && $user)
                    <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                    <p class="text-gray-400">{{ $user->email }}</p>
                    <div class="grid grid-cols-2 gap-3 mt-4 text-sm">
                        <div class="bg-[#2A2A2A] rounded-xl p-3">
                            <p class="text-gray-500">Objetivo</p>
                            <p class="text-white">{{ $user->fitness_goal ?: 'Sin indicar' }}</p>
                        </div>
                        <div class="bg-[#2A2A2A] rounded-xl p-3">
                            <p class="text-gray-500">Nivel</p>
                            <p class="text-white">{{ $user->fitness_level ? ucfirst($user->fitness_level) : 'Sin indicar' }}</p>
                        </div>
                        <div class="bg-[#2A2A2A] rounded-xl p-3">
                            <p class="text-gray-500">Altura</p>
                            <p class="text-white">{{ $user->height_cm ? $user->height_cm . ' cm' : 'Sin indicar' }}</p>
                        </div>
                        <div class="bg-[#2A2A2A] rounded-xl p-3">
                            <p class="text-gray-500">Peso</p>
                            <p class="text-white">{{ $user->weight_kg ? $user->weight_kg . ' kg' : 'Sin indicar' }}</p>
                        </div>
                    </div>
                @else
                    <h1 class="text-2xl font-bold text-gray-400">Usuario no encontrado</h1>
                @endif

                {{-- Estadísticas --}}
                @if (isset($stats))
                    <div class="flex justify-center md:justify-start gap-6 mt-4">
                        <div class="text-center">
                            <div class="text-xl font-bold text-[#00E676]">{{ $stats['total_posts'] ?? 0 }}</div>
                            <div class="text-xs text-gray-500">Publicaciones</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-[#00E676]">{{ $stats['total_likes'] ?? 0 }}</div>
                            <div class="text-xs text-gray-500">Likes recibidos</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-[#00E676]">{{ $stats['total_comments'] ?? 0 }}</div>
                            <div class="text-xs text-gray-500">Comentarios</div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Botones de acción --}}
            <div class="flex flex-col gap-2 w-full md:w-auto">
                @auth
                    @if (auth()->id() != $user->id)
                        @php
                            $friendship = \App\Models\Friendship::where(function ($q) use ($user) {
                                $q->where('user_id', auth()->id())->where('friend_id', $user->id);
                            })->orWhere(function ($q) use ($user) {
                                $q->where('user_id', $user->id)->where('friend_id', auth()->id());
                            })->first();
                        @endphp

                        @if ($friendship)
                            @if ($friendship->status == 'pending')
                                @if ($friendship->user_id == auth()->id())
                                    <button disabled class="bg-gray-600 text-white px-4 py-2 rounded-xl cursor-not-allowed">
                                        ⏳ Solicitud enviada
                                    </button>
                                @else
                                    <div class="flex gap-2">
                                        <form action="{{ route('friends.accept', $friendship) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-4 py-2 rounded-xl transition">
                                                ✅ Aceptar
                                            </button>
                                        </form>
                                        <form action="{{ route('friends.decline', $friendship) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-gray-700 hover:bg-red-600 text-white px-4 py-2 rounded-xl transition">
                                                ❌ Rechazar
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @elseif($friendship->status == 'accepted')
                                <button disabled class="bg-[#00E676] text-black font-bold px-4 py-2 rounded-xl cursor-not-allowed">
                                    ✓ Amigos
                                </button>
                                <a href="{{ route('chat.show', $user->id) }}" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-4 py-2 rounded-xl transition text-center">
                                    💬 Enviar mensaje
                                </a>
                            @endif
                        @else
                            <form action="{{ route('friends.request', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-4 py-2 rounded-xl transition">
                                    + Agregar amigo
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('feed') }}" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-4 py-2 rounded-xl transition text-center">
                            📱 Mi feed
                        </a>
                        <a href="{{ route('friends.index') }}" class="bg-[#2A2A2A] hover:bg-[#3A3A3A] text-white px-4 py-2 rounded-xl transition text-center">
                            👥 Mis amigos
                        </a>
                        <a href="{{ route('perfil.edit') }}" class="bg-[#2A2A2A] hover:bg-[#3A3A3A] text-white px-4 py-2 rounded-xl transition text-center">
                            Editar perfil
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    {{-- Publicaciones del usuario --}}
    <h2 class="text-xl font-bold text-white mb-4">📝 Publicaciones</h2>

    @if (isset($userPosts) && count($userPosts) > 0)
        @foreach ($userPosts as $post)
            <div class="bg-[#1E1E1E] rounded-2xl mb-6 overflow-hidden border border-[#2A2A2A] hover:border-[#00E676] transition-all">

                {{-- Cabecera --}}
                <div class="p-4 border-b border-[#2A2A2A]">
                    <div class="flex items-center gap-3">
                        @if ($post->user->avatar)
                            <img src="{{ $post->user->avatar }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-[#00E676] flex items-center justify-center text-black font-bold text-lg">
                                {{ substr($post->user->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <a href="{{ route('perfil.show', $post->user->id) }}" class="font-semibold text-white hover:text-[#00E676]">
                                {{ $post->user->name }}
                            </a>
                            <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                {{-- Contenido --}}
                <div class="p-4">
                    @if ($post->content)
                        <p class="text-gray-200 mb-3">{{ $post->content }}</p>
                    @endif

                    @if ($post->image)
                        <div class="mb-3 rounded-xl overflow-hidden">
                            <img src="{{ Storage::url($post->image) }}" class="w-full object-cover max-h-96">
                        </div>
                    @endif

                    @if ($post->detalles && $post->detalles->count() > 0)
                        <div class="bg-[#2A2A2A] rounded-xl p-3 mb-3">
                            <h4 class="font-semibold text-sm text-gray-300 mb-2">📊 Detalles del entrenamiento:</h4>
                            @foreach ($post->detalles->groupBy('musculo.nombre') as $musculoNombre => $ejercicios)
                                <div class="mb-3">
                                    <p class="font-medium text-[#00E676] text-sm">{{ $musculoNombre }}</p>
                                    @foreach ($ejercicios as $detalle)
                                        <p class="text-sm text-gray-400 ml-2">
                                            • {{ $detalle->ejercicio }}
                                            @if ($detalle->series) · {{ $detalle->series }} series @endif
                                            @if ($detalle->repeticiones) · {{ $detalle->repeticiones }} reps @endif
                                            @if ($detalle->peso) · {{ $detalle->peso }} kg @endif
                                        </p>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Botones de interacción --}}
                    <div class="flex gap-6 text-gray-400 text-sm border-t border-[#2A2A2A] pt-3 mt-2">
                        <button class="like-btn hover:text-[#00E676] transition flex items-center gap-1" data-post-id="{{ $post->id }}">
                            <i class="fa-{{ $post->isLikedByUser() ? 'solid' : 'regular' }} fa-heart"></i>
                            <span class="like-count">{{ $post->likes }}</span>
                            <span>Me gusta</span>
                        </button>
                        <button class="comment-btn hover:text-[#00E676] transition flex items-center gap-1" data-post-id="{{ $post->id }}">
                            <i class="fa-regular fa-comment"></i>
                            <span class="comment-count">{{ $post->comments_count }}</span>
                            <span>Comentarios</span>
                        </button>
                        @if (auth()->check() && auth()->id() == $user->id)
                            <button onclick="eliminarPost({{ $post->id }})" class="text-red-500 hover:text-red-400 transition">
                                🗑️ Eliminar
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="bg-[#1E1E1E] rounded-2xl p-12 text-center border border-[#2A2A2A]">
            <i class="fas fa-dumbbell text-5xl text-gray-600 mb-4"></i>
            <p class="text-gray-500">{{ $user->name ?? 'El usuario' }} aún no tiene publicaciones.</p>
        </div>
    @endif

</div>

<script>
    // Likes
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const icon = this.querySelector('i');
            const countSpan = this.querySelector('.like-count');

            fetch(`/post/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    icon.className = data.liked ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
                    countSpan.textContent = data.likes_count;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Eliminar post
    function eliminarPost(postId) {
        if (confirm('¿Estás seguro de que quieres eliminar esta publicación?')) {
            fetch(`/post/${postId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al eliminar la publicación');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar la publicación');
            });
        }
    }

    // Comentarios
document.getElementById('submitCommentBtn')?.addEventListener('click', function() {
    const input = document.getElementById('commentInput');
    const comment = input.value.trim();
    if (!comment) return;

    fetch(`/post/${currentPostId}/comment`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ comment: comment })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            loadComments(currentPostId);
            const commentBtn = document.querySelector(`.comment-btn[data-post-id="${currentPostId}"] .comment-count`);
            if (commentBtn) commentBtn.textContent = data.comments_count;
        }
    });
});
</script>

{{-- MODAL DE COMENTARIOS --}}
{{-- MODAL DE COMENTARIOS --}}
<div class="p-4 border-t border-[#2A2A2A]">
    <div class="flex gap-2">
        <input type="text" id="commentInput" placeholder="Escribe un comentario..."
            class="flex-1 bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-[#00E676] transition">
        <button id="submitCommentBtn" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-4 py-2 rounded-xl transition">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>
@endsection

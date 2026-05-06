@extends('layouts.app')

@section('title', 'Feed de Entrenos')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">

    {{-- Botón para crear publicación --}}
    <div class="flex justify-end mb-6">
        <button onclick="document.getElementById('createPostModal').classList.remove('hidden')"
            class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-6 py-3 rounded-xl transition transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i> Nueva Publicación
        </button>
    </div>

    {{-- MODAL PARA PUBLICAR ENTRENAMIENTO --}}
    <div id="createPostModal" class="hidden fixed inset-0 bg-black bg-opacity-90 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border-0 w-full max-w-2xl shadow-2xl rounded-2xl bg-[#1E1E1E] mb-10">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">🏋️ Compartir entrenamiento</h3>
                <button onclick="document.getElementById('createPostModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-white text-3xl">&times;</button>
            </div>

            <form action="{{ route('post.create') }}" method="POST" id="entrenamientoForm" enctype="multipart/form-data">
                @csrf

                <textarea name="content" rows="2"
                    class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl p-3 mb-4 text-white placeholder-gray-500 focus:outline-none focus:border-[#00E676]"
                    placeholder="¿Qué tal fue tu entrenamiento? (opcional)"></textarea>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">📷 Subir foto (opcional)</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-xl p-2 text-white file:mr-2 file:py-1 file:px-3 file:rounded-lg file:bg-[#00E676] file:text-black file:border-0">
                    <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, GIF. Máx 2MB</p>
                </div>

                {{-- Sección de ejercicios dinámicos --}}
                <div class="bg-[#2A2A2A] rounded-xl p-4 mb-4">
                    <h4 class="font-semibold text-white mb-3">📋 Ejercicios realizados</h4>
                    <div id="ejercicios-container">
                        <div class="ejercicio-item bg-[#1E1E1E] rounded-lg p-3 mb-3 border border-[#3A3A3A]">
                            <div class="grid grid-cols-12 gap-2 items-center">
                                <div class="col-span-3">
                                    <select name="ejercicios[0][musculo_id]"
                                        class="musculo-select w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm">
                                        <option value="">-- Músculo --</option>
                                        @foreach($musculos as $musculo)
                                            <option value="{{ $musculo->id }}">{{ $musculo->nombre }}</option>
                                        @endforeach
                                        <option value="otro">+ Otro músculo</option>
                                    </select>
                                    <input type="text" name="ejercicios[0][musculo_otro]"
                                        class="musculo-otro hidden w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm mt-1"
                                        placeholder="Nombre del músculo">
                                </div>
                                <div class="col-span-3">
                                    <select name="ejercicios[0][ejercicio_id]"
                                        class="ejercicio-select w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm" disabled>
                                        <option value="">-- Seleccionar ejercicio --</option>
                                    </select>
                                    <input type="text" name="ejercicios[0][ejercicio_otro]"
                                        class="ejercicio-otro hidden w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm mt-1"
                                        placeholder="Ejercicio personalizado">
                                </div>
                                <div class="col-span-2">
                                    <input type="number" name="ejercicios[0][series]" placeholder="Series"
                                        class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm">
                                </div>
                                <div class="col-span-2">
                                    <input type="number" name="ejercicios[0][repeticiones]" placeholder="Reps"
                                        class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm">
                                </div>
                                <div class="col-span-1">
                                    <input type="number" step="0.5" name="ejercicios[0][peso]" placeholder="Kg"
                                        class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm">
                                </div>
                                <div class="col-span-1 text-center">
                                    <button type="button" class="remove-ejercicio text-red-500 hover:text-red-400">🗑️</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="addEjercicioBtn"
                        class="mt-2 text-[#00E676] hover:text-[#00c853] text-sm font-semibold">
                        + Añadir ejercicio
                    </button>
                </div>

                <button type="submit"
                    class="w-full bg-[#00E676] hover:bg-[#00c853] text-black font-bold py-3 rounded-xl transition transform hover:scale-[1.02]">
                    📤 Publicar entrenamiento
                </button>
            </form>
        </div>
    </div>

    {{-- LISTA DE PUBLICACIONES --}}
    @if(isset($posts) && count($posts) > 0)
        @foreach($posts as $post)
            <div class="bg-[#1E1E1E] rounded-2xl mb-6 overflow-hidden border border-[#2A2A2A] hover:border-[#00E676] transition-all">

                {{-- Cabecera --}}
                <div class="p-4 border-b border-[#2A2A2A]">
                    <div class="flex items-center gap-3">
                        @if($post->user->avatar)
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
                    @if($post->content)
                        <p class="text-gray-200 mb-3">{{ $post->content }}</p>
                    @endif

                    @if($post->image)
                        <div class="mb-3 rounded-xl overflow-hidden">
                            <img src="{{ Storage::url($post->image) }}" class="w-full object-cover max-h-96">
                        </div>
                    @endif

                    @if($post->detalles && $post->detalles->count() > 0)
                        <div class="bg-[#2A2A2A] rounded-xl p-3 mb-3">
                            <h4 class="font-semibold text-sm text-gray-300 mb-2">📊 Detalles del entrenamiento:</h4>
                            @foreach($post->detalles->groupBy('musculo.nombre') as $musculoNombre => $ejercicios)
                                <div class="mb-3">
                                    <p class="font-medium text-[#00E676] text-sm">{{ $musculoNombre }}</p>
                                    @foreach($ejercicios as $detalle)
                                        <p class="text-sm text-gray-400 ml-2">
                                            • {{ $detalle->ejercicio }}
                                            @if($detalle->series) · {{ $detalle->series }} series @endif
                                            @if($detalle->repeticiones) · {{ $detalle->repeticiones }} reps @endif
                                            @if($detalle->peso) · {{ $detalle->peso }} kg @endif
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
                            <span class="like-count">{{ $post->likes()->count() }}</span>
                            <span>Me gusta</span>
                        </button>
                        <button class="comment-btn hover:text-[#00E676] transition flex items-center gap-1" data-post-id="{{ $post->id }}">
                            <i class="fa-regular fa-comment"></i>
                            <span class="comment-count">{{ $post->comments_count }}</span>
                            <span>Comentarios</span>
                        </button>
                    </div>

                    {{-- Sección de comentarios --}}
                    <div class="comments-section hidden mt-4 border-t border-[#2A2A2A] pt-4" data-post-id="{{ $post->id }}">
                        <div class="comments-list space-y-3 mb-4">
                            <!-- Comentarios se cargarán aquí -->
                        </div>

                        {{-- Formulario para nuevo comentario --}}
                        <form class="comment-form flex gap-3" data-post-id="{{ $post->id }}">
                            @csrf
                            <input type="text" name="content" placeholder="Escribe un comentario..."
                                   class="flex-1 bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-[#00E676]"
                                   maxlength="500" required>
                            <button type="submit"
                                    class="bg-[#00E676] text-black px-4 py-2 rounded-lg hover:bg-[#00CC5A] transition">
                                Comentar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="bg-[#1E1E1E] rounded-2xl p-12 text-center border border-[#2A2A2A]">
            <i class="fas fa-dumbbell text-5xl text-gray-600 mb-4"></i>
            <p class="text-gray-500 text-lg mb-2">No hay publicaciones de amigos aún.</p>
            <p class="text-gray-500 text-sm">
                @auth
                    Añade amigos para ver sus entrenamientos aquí, o
                    <button onclick="document.getElementById('createPostModal').classList.remove('hidden')"
                        class="text-[#00E676] hover:text-[#00c853] font-semibold">
                        sé el primero en publicar
                    </button>.
                @else
                    Inicia sesión para ver las publicaciones de tus amigos.
                @endauth
            </p>
            @auth
                <div class="mt-6">
                    <a href="{{ route('friends.index') }}" class="bg-[#00E676] hover:bg-[#00c853] text-black font-bold px-6 py-3 rounded-xl transition inline-flex items-center gap-2">
                        <i class="fas fa-users"></i> Buscar amigos
                    </a>
                </div>
            @endauth
        </div>
    @endif

</div>

<script>
    let ejercicioIndex = {{ isset($posts) ? $posts->first()?->detalles->count() ?? 1 : 1 }};

    function cargarEjercicios(musculoSelect) {
        const ejercicioItem = musculoSelect.closest('.ejercicio-item');
        const ejercicioSelect = ejercicioItem.querySelector('.ejercicio-select');
        const musculoOtro = ejercicioItem.querySelector('.musculo-otro');
        const musculoId = musculoSelect.value;

        if (musculoId === 'otro') {
            musculoOtro.classList.remove('hidden');
            ejercicioSelect.innerHTML = '<option value="">-- Seleccionar ejercicio --</option><option value="otro">+ Otro ejercicio</option>';
            ejercicioSelect.disabled = false;
            return;
        }

        musculoOtro.classList.add('hidden');

        if (!musculoId) {
            ejercicioSelect.innerHTML = '<option value="">-- Seleccionar ejercicio --</option>';
            ejercicioSelect.disabled = true;
            return;
        }

        ejercicioSelect.innerHTML = '<option>Cargando...</option>';
        fetch(`/api/ejercicios-por-musculo/${musculoId}`)
            .then(response => response.json())
            .then(data => {
                ejercicioSelect.innerHTML = '<option value="">-- Seleccionar ejercicio --</option>';
                data.forEach(ejercicio => {
                    ejercicioSelect.innerHTML += `<option value="${ejercicio.id}">${ejercicio.nombre}</option>`;
                });
                ejercicioSelect.innerHTML += '<option value="otro">+ Otro ejercicio</option>';
                ejercicioSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                ejercicioSelect.innerHTML = '<option value="">Error al cargar</option>';
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Selects de músculo
        document.querySelectorAll('.musculo-select').forEach(select => {
            select.addEventListener('change', function() {
                cargarEjercicios(this);
            });
        });

        // Botones eliminar ejercicio
        document.querySelectorAll('.remove-ejercicio').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.ejercicio-item').remove();
            });
        });

        // Botón añadir ejercicio
        const addBtn = document.getElementById('addEjercicioBtn');
        if (addBtn) {
            addBtn.addEventListener('click', function() {
                const container = document.getElementById('ejercicios-container');
                const newIndex = document.querySelectorAll('.ejercicio-item').length;

                const nuevoItem = document.createElement('div');
                nuevoItem.className = 'ejercicio-item bg-[#1E1E1E] rounded-lg p-3 mb-3 border border-[#3A3A3A]';
                nuevoItem.innerHTML = `
                    <div class="grid grid-cols-12 gap-2 items-center">
                        <div class="col-span-3">
                            <select name="ejercicios[${newIndex}][musculo_id]" class="musculo-select w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm">
                                <option value="">-- Músculo --</option>
                                @foreach($musculos as $musculo)
                                    <option value="{{ $musculo->id }}">{{ $musculo->nombre }}</option>
                                @endforeach
                                <option value="otro">+ Otro músculo</option>
                            </select>
                            <input type="text" name="ejercicios[${newIndex}][musculo_otro]" class="musculo-otro hidden w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm mt-1" placeholder="Nombre del músculo">
                        </div>
                        <div class="col-span-3">
                            <select name="ejercicios[${newIndex}][ejercicio_id]" class="ejercicio-select w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm" disabled>
                                <option value="">-- Seleccionar ejercicio --</option>
                            </select>
                            <input type="text" name="ejercicios[${newIndex}][ejercicio_otro]" class="ejercicio-otro hidden w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm mt-1" placeholder="Ejercicio personalizado">
                        </div>
                        <div class="col-span-2">
                            <input type="number" name="ejercicios[${newIndex}][series]" placeholder="Series" class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm">
                        </div>
                        <div class="col-span-2">
                            <input type="number" name="ejercicios[${newIndex}][repeticiones]" placeholder="Reps" class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm">
                        </div>
                        <div class="col-span-1">
                            <input type="number" step="0.5" name="ejercicios[${newIndex}][peso]" placeholder="Kg" class="w-full bg-[#2A2A2A] border border-[#3A3A3A] rounded-lg p-2 text-white text-sm">
                        </div>
                        <div class="col-span-1 text-center">
                            <button type="button" class="remove-ejercicio text-red-500 hover:text-red-400">🗑️</button>
                        </div>
                    </div>
                `;
                container.appendChild(nuevoItem);

                nuevoItem.querySelector('.musculo-select').addEventListener('change', function() {
                    cargarEjercicios(this);
                });
                nuevoItem.querySelector('.remove-ejercicio').addEventListener('click', function() {
                    nuevoItem.remove();
                });
            });
        }

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

        // Comments
        document.querySelectorAll('.comment-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const postId = this.dataset.postId;
                const commentsSection = document.querySelector(`.comments-section[data-post-id="${postId}"]`);

                if (commentsSection.classList.contains('hidden')) {
                    // Load comments
                    loadComments(postId);
                    commentsSection.classList.remove('hidden');
                } else {
                    commentsSection.classList.add('hidden');
                }
            });
        });

        // Comment forms
        document.querySelectorAll('.comment-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const postId = this.dataset.postId;
                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/post/${postId}/comments`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(`Error ${response.status}: ${text}`); });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.id) {
                        addCommentToList(postId, data);
                        this.querySelector('input[name="content"]').value = '';
                        const countSpan = document.querySelector(`.comment-btn[data-post-id="${postId}"] .comment-count`);
                        countSpan.textContent = parseInt(countSpan.textContent) + 1;
                    } else {
                        console.error('Comment submit response:', data);
                    }
                })
                .catch(error => console.error('Error submitting comment:', error));
            });
        });

        function loadComments(postId) {
            fetch(`/post/${postId}/comments`)
                .then(response => response.json())
                .then(comments => {
                    const commentsList = document.querySelector(`.comments-section[data-post-id="${postId}"] .comments-list`);
                    commentsList.innerHTML = '';
                    comments.forEach(comment => {
                        addCommentToList(postId, comment);
                    });
                })
                .catch(error => console.error('Error loading comments:', error));
        }

        function addCommentToList(postId, comment) {
            const commentsList = document.querySelector(`.comments-section[data-post-id="${postId}"] .comments-list`);
            const commentDiv = document.createElement('div');
            commentDiv.className = 'bg-[#2A2A2A] rounded-lg p-3';
            commentDiv.innerHTML = `
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-medium text-white">${comment.user.name}</span>
                    <span class="text-gray-400 text-xs">${new Date().toLocaleDateString()}</span>
                </div>
                <p class="text-gray-300">${comment.content}</p>
            `;
            commentsList.appendChild(commentDiv);
        }
    });
</script>
@endsection

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GYM TONIC - @yield('title', 'Inicio')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }
        .menu-dropdown {
            transition: all 0.3s ease;
        }
        .menu-dropdown.hidden {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">

                {{-- Botón menú desplegable (izquierda) --}}
                <div class="relative">
                    <button id="menuBtn" class="text-gray-700 hover:text-indigo-600 text-2xl focus:outline-none">
                        ☰
                    </button>

                    {{-- Menú desplegable según si está logueado o no --}}
                    <div id="dropdownMenu" class="menu-dropdown hidden absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50">
                        @guest
                            {{-- Menú para invitados (no logueados) --}}
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                🏠 Inicio
                            </a>
                            <a href="{{ route('inscribete') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                📝 Inscríbete
                            </a>
                            <a href="{{ route('sobre-nosotros') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                📖 Sobre nosotros
                            </a>
                            <a href="{{ route('ofertas') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                🏷️ Ofertas
                            </a>
                        @else
                            {{-- Menú para usuarios logueados --}}
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                🏠 Inicio
                            </a>
                            <a href="{{ route('activities.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                📅 Reservas
                            </a>
                            <a href="{{ route('chats.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                💬 Chat
                            </a>
                            <a href="{{ route('feed') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                💪 Entrenamientos
                            </a>
                            <a href="{{ route('tienda') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                🛒 Tienda
                            </a>
                        @endguest
                    </div>
                </div>

                {{-- Título del gimnasio (centro) --}}
                <div class="absolute left-1/2 transform -translate-x-1/2">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">
                        🏋️ GYM TONIC
                    </a>
                </div>

                {{-- Foto de perfil (derecha) --}}
                <div class="relative">
                    @auth
                        <button id="perfilBtn" class="focus:outline-none">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Perfil" class="w-10 h-10 rounded-full object-cover border-2 border-indigo-600">
                            @else
                                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                        </button>

                        <div id="perfilMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                            <a href="{{ route('perfil.show', auth()->id()) }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                👤 Mi Perfil
                            </a>
                            <a href="{{ route('feed') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                📱 Mi Feed
                            </a>
                            <a href="{{ route('chats.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                                💬 Chats
                            </a>
                            <hr class="my-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                    🚪 Cerrar sesión
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Iniciar sesión
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex gap-6">
                    <a href="#" class="text-gray-400 hover:text-indigo-400 text-2xl transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-indigo-400 text-2xl transition"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-gray-400 hover:text-indigo-400 text-2xl transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-400 hover:text-indigo-400 text-2xl transition"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="text-gray-400 hover:text-indigo-400 text-2xl transition"><i class="fab fa-tiktok"></i></a>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-gray-400"><i class="fas fa-phone mr-2"></i> +34 123 456 789</p>
                    <p class="text-gray-500 text-sm mt-1">📍 C/ Ejemplo, 1 - Madrid</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-6 pt-6 text-center text-gray-500 text-sm">
                <p>&copy; 2025 GYM TONIC. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Menú desplegable izquierdo
        const menuBtn = document.getElementById('menuBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (menuBtn) {
            menuBtn.addEventListener('click', () => {
                dropdownMenu.classList.toggle('hidden');
            });
        }

        // Menú de perfil
        const perfilBtn = document.getElementById('perfilBtn');
        const perfilMenu = document.getElementById('perfilMenu');

        if (perfilBtn) {
            perfilBtn.addEventListener('click', () => {
                perfilMenu.classList.toggle('hidden');
            });
        }

        // Cerrar menús al hacer clic fuera
        document.addEventListener('click', (event) => {
            if (menuBtn && !menuBtn.contains(event.target) && dropdownMenu) {
                dropdownMenu.classList.add('hidden');
            }
            if (perfilBtn && !perfilBtn.contains(event.target) && perfilMenu) {
                perfilMenu.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

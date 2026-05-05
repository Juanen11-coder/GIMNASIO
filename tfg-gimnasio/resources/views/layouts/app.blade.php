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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #121212;
            color: #FFFFFF;
        }

        /* Scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1E1E1E;
        }
        ::-webkit-scrollbar-thumb {
            background: #00E676;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #00c853;
        }

        /* Navbar */
        .navbar {
            background: #0a0a0a;
            border-bottom: 1px solid #2a2a2a;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }

        .nav-link {
            color: #888;
            transition: color 0.2s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #00E676;
        }

        .nav-link.active {
            color: #00E676;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00E676 0%, #00c853 100%);
            color: #000;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: inline-block;
            text-align: center;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 230, 118, 0.3);
        }

        .btn-outline {
            border: 1px solid #00E676;
            color: #00E676;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: inline-block;
            text-align: center;
            background: transparent;
        }

        .btn-outline:hover {
            background: #00E676;
            color: #000;
        }

        /* Tarjetas */
        .card {
            background: #1E1E1E;
            border-radius: 16px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid #2a2a2a;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            border-color: #00E676;
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease forwards;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="text-2xl font-black tracking-tight">
                    GYM<span class="text-[#00E676]">TONIC</span>
                </a>

                {{-- Menú central --}}
                <div class="hidden md:flex gap-8">
                    @auth
                        <a href="{{ route('home') }}" class="nav-link">Inicio</a>
                        <a href="{{ route('feed') }}" class="nav-link">Feed</a>
                        <a href="{{ route('chats.index') }}" class="nav-link">Chats</a>
                        <a href="{{ route('activities.index') }}" class="nav-link">Actividades</a>
                        <a href="{{ route('friends.index') }}" class="nav-link">Amigos</a>
                    @else
                        <a href="{{ route('home') }}" class="nav-link">Inicio</a>
                        <a href="{{ route('inscribete') }}" class="nav-link">Inscríbete</a>
                        <a href="{{ route('about') }}" class="nav-link">Sobre nosotros</a>
                        <a href="{{ route('tienda') }}" class="nav-link">Ofertas</a>
                    @endauth
                </div>

                {{-- Botones derecha --}}
                <div class="flex gap-3">
                    @auth
                        <a href="{{ route('perfil.show', auth()->id()) }}" class="flex items-center gap-2">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" class="w-10 h-10 rounded-full object-cover border-2 border-[#00E676]">
                            @else
                                <div class="w-10 h-10 rounded-full bg-[#00E676] flex items-center justify-center text-black font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn-outline text-sm py-2 px-4">Salir</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline text-sm py-2 px-4">Iniciar sesión</a>
                        <a href="{{ route('register.show') }}" class="btn-primary text-sm py-2 px-4">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-8">
        @yield('content')
    </main>

    <footer class="bg-[#0a0a0a] border-t border-[#2a2a2a] mt-12 py-8">
        <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
            <p>© 2025 <span class="text-[#00E676] font-semibold">GYM TONIC</span>. Proyecto desarrollado por Juanen, Pablo y Miguel Ángel</p>
        </div>
    </footer>

    <script>
        // Marcar enlace activo
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('active');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

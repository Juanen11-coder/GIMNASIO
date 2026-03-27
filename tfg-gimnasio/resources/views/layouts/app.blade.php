<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GYM TFG - @yield('title', 'Gimnasio Social')</title>

    {{-- Tailwind CSS (para estilos rápidos) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Estilos personalizados (opcional) --}}
    <style>
        .hover-scale:hover {
            transform: scale(1.02);
            transition: transform 0.2s;
        }
    </style>
</head>
<body class="bg-gray-100">

    {{-- BARRA DE NAVEGACIÓN --}}
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                {{-- Logo / Inicio --}}
                <a href="{{ route('feed') }}" class="text-xl font-bold text-indigo-600">
                    🏋️ GYM Social
                </a>

                {{-- Enlaces principales --}}
                <div class="flex gap-6">
                    <a href="{{ route('feed') }}" class="text-gray-600 hover:text-indigo-600 transition">
                        Feed
                    </a>
                    <a href="{{ route('chats.index') }}" class="text-gray-600 hover:text-indigo-600 transition">
                        Chats
                    </a>
                    <a href="{{ route('perfil.show', 1) }}" class="text-gray-600 hover:text-indigo-600 transition">
                        Mi Perfil
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- CONTENIDO PRINCIPAL --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white border-t mt-12 py-6">
        <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
            GYM TFG - Proyecto desarrollado por Juanen, Pablo y Miguel Ángel
        </div>
    </footer>

    {{-- MENSAJES FLASH (para mostrar éxito/error) --}}
    @if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('error') }}
    </div>
    @endif

</body>
</html>

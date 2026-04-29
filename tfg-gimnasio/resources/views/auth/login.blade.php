@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">

        {{-- Logo y título --}}
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-[#00E676] rounded-2xl flex items-center justify-center mb-6">
                <i class="fas fa-dumbbell text-3xl text-black"></i>
            </div>
            <h2 class="text-3xl font-black text-white">
                BIENVENIDO DE VUELTA
            </h2>
            <p class="mt-2 text-gray-400">
            Inicia sesión para continuar
            </p>
        </div>

        {{-- Formulario de login --}}
        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                        Correo electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-500 text-sm"></i>
                        </div>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                               class="block w-full pl-10 pr-3 py-3 bg-[#1E1E1E] border border-[#2A2A2A] rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-[#00E676] transition"
                               placeholder="tu@email.com">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contraseña --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                        Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-500 text-sm"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                               class="block w-full pl-10 pr-10 py-3 bg-[#1E1E1E] border border-[#2A2A2A] rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-[#00E676] transition"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="passwordIcon" class="fas fa-eye text-gray-500 hover:text-gray-300 text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Recordarme y olvidé contraseña --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-600 bg-[#1E1E1E] text-[#00E676] focus:ring-[#00E676]">
                    <span class="ml-2 text-sm text-gray-400">Recordarme</span>
                </label>
                <a href="#" class="text-sm text-[#00E676] hover:text-[#00c853] transition">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            {{-- Botón de login --}}
            <button type="submit"
                    class="w-full bg-[#00E676] hover:bg-[#00c853] text-black font-bold py-3 rounded-xl transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                <i class="fas fa-sign-in-alt"></i> Iniciar sesión
            </button>

            {{-- Registro --}}
            <div class="text-center">
                <p class="text-gray-400">
                    ¿No tienes cuenta?
                    <a href="{{ route('register.show') }}" class="text-[#00E676] hover:text-[#00c853] font-semibold transition">
                        Regístrate gratis
                    </a>
                </p>
            </div>
        </form>

        {{-- Separador --}}
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-[#2A2A2A]"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-[#121212] text-gray-500">O continúa con</span>
            </div>
        </div>

        {{-- Redes sociales (opcional) --}}
        <div class="grid grid-cols-2 gap-3">
            <button class="flex items-center justify-center gap-2 bg-[#1E1E1E] border border-[#2A2A2A] py-3 rounded-xl text-white hover:border-[#00E676] transition">
                <i class="fab fa-google text-red-500"></i> Google
            </button>
            <button class="flex items-center justify-center gap-2 bg-[#1E1E1E] border border-[#2A2A2A] py-3 rounded-xl text-white hover:border-[#00E676] transition">
                <i class="fab fa-apple"></i> Apple
            </button>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('passwordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection

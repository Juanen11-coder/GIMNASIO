@extends('layouts.app')

@section('title', 'Crear cuenta')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">

        {{-- Logo y título --}}
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-[#00E676] rounded-2xl flex items-center justify-center mb-6">
                <i class="fas fa-user-plus text-3xl text-black"></i>
            </div>
            <h2 class="text-3xl font-black text-white">
                ÚNETE A GYM TONIC
            </h2>
            <p class="mt-2 text-gray-400">
                Crea tu cuenta y comienza tu viaje fitness
            </p>
        </div>

        {{-- Formulario de registro --}}
        <form class="mt-8 space-y-6" method="POST" action="{{ route('register.post') }}">
            @csrf

            @if ($errors->any())
                <div class="bg-red-800 text-red-100 p-4 rounded-xl border border-red-700">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Nombre --}}
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        Nombre completo
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-500 text-sm"></i>
                        </div>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}"
                               class="block w-full pl-10 pr-3 py-3 bg-[#1E1E1E] border border-[#2A2A2A] rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-[#00E676] transition"
                               placeholder="Tu nombre completo">
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
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

                {{-- Rol --}}
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-300 mb-2">
                        Tipo de cuenta
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-graduation-cap text-gray-500 text-sm"></i>
                        </div>
                        <select id="role" name="role" required
                                class="block w-full pl-10 pr-3 py-3 bg-[#1E1E1E] border border-[#2A2A2A] rounded-xl text-white focus:outline-none focus:border-[#00E676] transition">
                            <option value="student"{{ old('role') === 'student' ? ' selected' : '' }}>Alumno</option>
                            <option value="teacher"{{ old('role') === 'teacher' ? ' selected' : '' }}>Profesor</option>
                        </select>
                    </div>
                    @error('role')
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
                               class="block w-full pl-10 pr-3 py-3 bg-[#1E1E1E] border border-[#2A2A2A] rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-[#00E676] transition"
                               placeholder="Mínimo 8 caracteres">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirmar contraseña --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                        Confirmar contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-500 text-sm"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="block w-full pl-10 pr-3 py-3 bg-[#1E1E1E] border border-[#2A2A2A] rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-[#00E676] transition"
                               placeholder="Repite tu contraseña">
                    </div>
                </div>
            </div>

            {{-- Botón de registro --}}
            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-black bg-[#00E676] hover:bg-[#00c853] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#00E676] transition transform hover:scale-[1.02]">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus text-black group-hover:text-gray-800"></i>
                    </span>
                    Crear cuenta
                </button>
            </div>

            {{-- Enlace a login --}}
            <div class="text-center">
                <p class="text-gray-400">
                    ¿Ya tienes cuenta?
                    <a href="{{ route('login') }}" class="text-[#00E676] hover:text-[#00c853] font-medium transition">
                        Inicia sesión
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection

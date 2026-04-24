@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6">Crear cuenta</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nombre</label>
                <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name') }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email') }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Contraseña</label>
                <input type="password" name="password" class="w-full border rounded p-2">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Repetir contraseña</label>
                <input type="password" name="password_confirmation" class="w-full border rounded p-2">
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                Registrarse
            </button>
        </form>

        <p class="mt-4 text-center text-sm">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-indigo-600">Inicia sesión</a>
        </p>
    </div>
</div>
@endsection

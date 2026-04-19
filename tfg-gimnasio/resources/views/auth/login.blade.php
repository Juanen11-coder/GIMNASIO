<!DOCTYPE html>
<html>
<head>
    <title>Login de Prueba</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 p-20 text-center">
    <h1 class="text-3xl font-bold mb-6">Selecciona un usuario para entrar</h1>
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow-lg">
        @foreach($users as $user)
            <a href="{{ route('login.as', $user->id) }}" class="block p-3 border-b hover:bg-gray-100">
                <strong>{{ $user->name }}</strong> ({{ $user->role == 'teacher' ? 'Profesor' : 'Alumno' }})
            </a>
        @endforeach
    </div>
</body>
</html>
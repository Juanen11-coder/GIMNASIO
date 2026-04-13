<!DOCTYPE html>
<html>
<head>
    <title>Alumnos Apuntados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow-md">
        <a href="{{ route('activities.index') }}" class="text-blue-500 underline mb-4 inline-block">← Volver</a>
        
        <h1 class="text-2xl font-bold mb-2 underline">Actividad: {{ $activity->title }}</h1>
        <p class="text-gray-600 mb-6">Espacio: {{ $activity->space->name }}</p>

        <h2 class="text-xl font-semibold mb-4 text-gray-800">Lista de Alumnos Inscritos</h2>
        
        <ul class="divide-y divide-gray-200">
            @forelse($activity->students as $student)
                <li class="py-3 flex justify-between">
                    <span class="font-medium text-gray-700">{{ $student->name }}</span>
                    <span class="text-gray-500">{{ $student->email }}</span>
                </li>
            @empty
                <li class="py-3 text-gray-500 italic">Aún no hay alumnos apuntados a esta actividad.</li>
            @endforelse
        </ul>
    </div>
</body>
</html>
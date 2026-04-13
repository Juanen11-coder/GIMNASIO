<!DOCTYPE html>
<html>
<head>
    <title>Reservar Espacio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-lg mx-auto bg-white p-8 rounded shadow-md">
        <h1 class="text-2xl font-bold mb-6">Nueva Reserva de Actividad</h1>

        <form action="{{ route('activities.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-2">Nombre de la Actividad</label>
                <input type="text" name="title" class="w-full border p-2 rounded" placeholder="Ej: Clase de Karate" required>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Seleccionar Espacio</label>
                <select name="space_id" class="w-full border p-2 rounded">
                    @foreach($spaces as $space)
                        <option value="{{ $space->id }}">{{ $space->name }} (Capacidad: {{ $space->capacity }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2">Fecha y Hora</label>
                <input type="datetime-local" name="scheduled_at" class="w-full border p-2 rounded" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-bold">Confirmar Reserva</button>
        </form>
    </div>
</body>
</html>
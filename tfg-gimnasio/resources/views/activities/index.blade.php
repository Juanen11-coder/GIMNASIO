<!DOCTYPE html>
<html>

<head>
    <title>Actividades Disponibles</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Actividades y Espacios</h1>
            @if(Auth::user() && Auth::user()->role == 'teacher')
                <a href="{{ route('activities.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Crear
                    Actividad</a>
            @endif


        </div>

        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-3 mb-4 rounded">{{ session('success') }}</div>
        @endif

        <div class="grid gap-4">
            @foreach($activities as $activity)
                <div class="bg-white p-6 rounded shadow-md flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold">{{ $activity->title }}</h2>
                        <p class="text-gray-600">Lugar: <strong>{{ $activity->space->name }}</strong></p>
                        <p class="text-gray-500 text-sm">Profesor: {{ $activity->teacher->name }}</p>
                    </div>

                    <form action="{{ route('activities.enroll', $activity->id) }}" method="POST">
                        @if(Auth::user() && Auth::user()->role == 'student')
                            <form action="{{ route('activities.enroll', $activity->id) }}" method="POST">
                                @csrf
                                <button class="bg-green-500 text-white px-4 py-2 rounded">Apuntarme</button>
                            </form>
                        @endif
                        @if(Auth::user() && Auth::user()->id == $activity->user_id)
                            <a href="{{ route('activities.students', $activity->id) }}"
                                class="bg-purple-500 text-white px-3 py-1 rounded text-sm hover:bg-purple-600">
                                Ver {{ $activity->students->count() }} alumnos
                            </a>
                        @endif
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
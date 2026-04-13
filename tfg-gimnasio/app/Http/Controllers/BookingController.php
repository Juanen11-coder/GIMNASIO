<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Space;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Listado de actividades para los alumnos
    public function index()
    {
        $activities = Activity::with(['space', 'teacher'])->get();
        return view('activities.index', compact('activities'));
    }

    // Mostrar formulario de reserva (Solo para profesores)
    public function createActivity()
    {
        $spaces = Space::all();
        return view('activities.create', compact('spaces'));
    }

    // Guardar la actividad y reserva de espacio
    public function storeActivity(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'space_id' => 'required|exists:spaces,id',
            'scheduled_at' => 'required|date|after:now',
        ]);

        // Lógica: Un profesor crea la actividad vinculada a un espacio
        Activity::create([
            'title' => $request->title,
            'user_id' => Auth::id() ?? 1,
            'space_id' => $request->space_id,
            'scheduled_at' => $request->scheduled_at,
        ]);

        return redirect()->route('activities.index')->with('success', 'Espacio reservado con éxito.');
    }

    // Alumno se apunta a una actividad
    public function enroll(Activity $activity)
    {
        // Verificar si ya está apuntado
        $exists = Enrollment::where('user_id', Auth::id())
            ->where('activity_id', $activity->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Ya estás inscrito en esta actividad.');
        }

        Enrollment::create([
            'user_id' => Auth::id() ?? 1,
            'activity_id' => $activity->id,
        ]);

        return back()->with('success', 'Te has apuntado correctamente.');
    }

    public function showStudents(Activity $activity)
    {
        // Seguridad: Solo el profesor que creó la actividad puede ver la lista
        if (Auth::id() !== $activity->user_id) {
            return abort(403, 'No tienes permiso para ver esta actividad.');
        }

        // Cargamos los alumnos usando la relación que definimos en el modelo
        $students = $activity->students;

        return view('activities.students', compact('activity', $students));
    }

}
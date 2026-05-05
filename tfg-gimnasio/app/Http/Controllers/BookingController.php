<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\Space;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $activities = Activity::with(['space', 'teacher'])
            ->withCount('students')
            ->orderBy('scheduled_at')
            ->get();

        $enrolledActivityIds = [];

        if (Auth::check()) {
            $enrolledActivityIds = Enrollment::where('user_id', Auth::id())
                ->pluck('activity_id')
                ->toArray();
        }

        return view('activities.index', compact('activities', 'enrolledActivityIds'));
    }

    public function createActivity()
    {
        if (! $this->canCreateActivities(Auth::user()->role)) {
            return redirect()->route('activities.index')->with('error', 'Solo los administradores pueden crear actividades.');
        }

        $spaces = Space::all();

        return view('activities.create', compact('spaces'));
    }

    public function storeActivity(Request $request)
    {
        if (! $this->canCreateActivities(Auth::user()->role)) {
            return redirect()->route('activities.index')->with('error', 'Solo los administradores pueden crear actividades.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'space_id' => 'required|exists:spaces,id',
            'category' => 'required|in:cardio,strength,relax',
            'creation_type' => 'required|in:single,weekly',
            'scheduled_at' => 'required_if:creation_type,single|nullable|date|after:now',
            'start_date' => 'required_if:creation_type,weekly|nullable|date',
            'end_date' => 'required_if:creation_type,weekly|nullable|date|after_or_equal:start_date',
            'time' => 'required_if:creation_type,weekly|nullable|date_format:H:i',
            'weekdays' => 'required_if:creation_type,weekly|array',
            'weekdays.*' => 'integer|between:1,7',
        ]);

        $scheduledDates = $this->scheduledDatesFromRequest($request);

        if (count($scheduledDates) === 0) {
            return back()
                ->withInput()
                ->with('error', 'No hay ningun horario futuro para crear.');
        }

        foreach ($scheduledDates as $scheduledAt) {
            Activity::create([
                'title' => $request->title,
                'user_id' => Auth::id(),
                'space_id' => $request->space_id,
                'scheduled_at' => $scheduledAt,
                'category' => $request->category,
            ]);
        }

        return redirect()->route('activities.index')->with('success', count($scheduledDates) === 1
            ? 'Clase creada correctamente.'
            : 'Clases de la semana creadas correctamente.');
    }

    public function enroll(Activity $activity)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (! $this->canEnrollInActivities(Auth::user()->role)) {
            return back()->with('error', 'Solo los alumnos pueden apuntarse a actividades.');
        }

        $exists = Enrollment::where('user_id', Auth::id())
            ->where('activity_id', $activity->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Ya estas apuntado a esta actividad.');
        }

        if ($activity->students()->count() >= ($activity->space->capacity ?? 0)) {
            return back()->with('error', 'No quedan plazas disponibles en esta actividad.');
        }

        Enrollment::create([
            'user_id' => Auth::id(),
            'activity_id' => $activity->id,
        ]);

        return back()->with('success', 'Te has apuntado correctamente a la actividad.');
    }

    public function unenroll(Activity $activity)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (! $this->canEnrollInActivities(Auth::user()->role)) {
            return back()->with('error', 'Solo los alumnos pueden desapuntarse de actividades.');
        }

        $deleted = Enrollment::where('user_id', Auth::id())
            ->where('activity_id', $activity->id)
            ->delete();

        if (! $deleted) {
            return back()->with('error', 'No estabas apuntado a esta actividad.');
        }

        return back()->with('success', 'Te has desapuntado correctamente de la actividad.');
    }

    public function showStudents(Activity $activity)
    {
        if (Auth::id() !== $activity->user_id && ! $this->canCreateActivities(Auth::user()->role)) {
            return abort(403, 'No tienes permiso para ver esta actividad.');
        }

        $students = $activity->students;

        return view('activities.students', compact('activity', 'students'));
    }

    private function canCreateActivities(string $role): bool
    {
        return in_array($role, ['admin', 'teacher'], true);
    }

    private function canEnrollInActivities(string $role): bool
    {
        return in_array($role, ['student', 'user'], true);
    }

    private function scheduledDatesFromRequest(Request $request): array
    {
        if ($request->creation_type === 'single') {
            return [Carbon::parse($request->scheduled_at)];
        }

        $selectedWeekdays = array_map('intval', $request->weekdays ?? []);
        $dates = [];

        foreach (CarbonPeriod::create($request->start_date, $request->end_date) as $date) {
            if (! in_array($date->dayOfWeekIso, $selectedWeekdays, true)) {
                continue;
            }

            $scheduledAt = Carbon::parse($date->format('Y-m-d').' '.$request->time);

            if ($scheduledAt->isFuture()) {
                $dates[] = $scheduledAt;
            }
        }

        return $dates;
    }
}

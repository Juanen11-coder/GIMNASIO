<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\Space;
use App\Models\WaitlistEntry;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $activities = $this->activityQuery()->get();
        $enrolledActivityIds = [];
        $waitlistedActivityIds = [];

        if (Auth::check()) {
            $enrolledActivityIds = Enrollment::where('user_id', Auth::id())->pluck('activity_id')->toArray();
            $waitlistedActivityIds = WaitlistEntry::where('user_id', Auth::id())->pluck('activity_id')->toArray();
        }

        return view('activities.index', compact('activities', 'enrolledActivityIds', 'waitlistedActivityIds'));
    }

    public function myActivities()
    {
        $enrolledActivities = $this->activityQuery()
            ->whereHas('students', fn ($query) => $query->where('users.id', Auth::id()))
            ->get();

        $waitlistedActivities = $this->activityQuery()
            ->whereHas('waitlistedUsers', fn ($query) => $query->where('users.id', Auth::id()))
            ->get();

        return view('activities.mine', compact('enrolledActivities', 'waitlistedActivities'));
    }

    public function admin()
    {
        $this->authorizeActivityAdmin();

        $activities = $this->activityQuery()->limit(8)->get();
        $spaces = Space::withCount('activities')->orderBy('name')->get();
        $stats = [
            'activities' => Activity::count(),
            'spaces' => Space::count(),
            'enrollments' => Enrollment::count(),
            'waitlist' => WaitlistEntry::count(),
        ];

        return view('admin.dashboard', compact('activities', 'spaces', 'stats'));
    }

    public function createActivity()
    {
        $this->authorizeActivityAdmin();

        $spaces = Space::orderBy('name')->get();

        return view('activities.create', compact('spaces'));
    }

    public function storeActivity(Request $request)
    {
        $this->authorizeActivityAdmin();

        $data = $this->validateActivityRequest($request);
        $scheduledDates = $this->scheduledDatesFromRequest($request);

        if (count($scheduledDates) === 0) {
            return back()->withInput()->with('error', 'No hay ningun horario futuro para crear.');
        }

        $conflict = $this->firstScheduleConflict($data['space_id'], $scheduledDates);
        if ($conflict) {
            return back()->withInput()->with('error', 'La sala ya esta ocupada el '.$conflict->format('d/m/Y H:i').'.');
        }

        foreach ($scheduledDates as $scheduledAt) {
            Activity::create([
                'title' => $data['title'],
                'user_id' => Auth::id(),
                'space_id' => $data['space_id'],
                'scheduled_at' => $scheduledAt,
                'category' => $data['category'],
            ]);
        }

        return redirect()->route('activities.index')->with('success', count($scheduledDates) === 1
            ? 'Clase creada correctamente.'
            : 'Horario semanal creado correctamente.');
    }

    public function editActivity(Activity $activity)
    {
        $this->authorizeActivityAdmin();

        $spaces = Space::orderBy('name')->get();

        return view('activities.edit', compact('activity', 'spaces'));
    }

    public function updateActivity(Request $request, Activity $activity)
    {
        $this->authorizeActivityAdmin();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'space_id' => 'required|exists:spaces,id',
            'scheduled_at' => 'required|date|after:now',
            'category' => 'required|in:cardio,strength,relax',
        ]);

        $scheduledAt = Carbon::parse($data['scheduled_at']);
        $conflict = $this->firstScheduleConflict($data['space_id'], [$scheduledAt], $activity->id);
        if ($conflict) {
            return back()->withInput()->with('error', 'La sala ya esta ocupada en ese horario.');
        }

        $activity->update($data);

        return redirect()->route('admin.activities')->with('success', 'Actividad actualizada correctamente.');
    }

    public function destroyActivity(Activity $activity)
    {
        $this->authorizeActivityAdmin();

        $activity->students()->detach();
        $activity->waitlistEntries()->delete();
        $activity->delete();

        return back()->with('success', 'Actividad eliminada correctamente.');
    }

    public function enroll(Activity $activity)
    {
        if (! $this->canEnrollInActivities(Auth::user()->role)) {
            return back()->with('error', 'Solo los alumnos pueden apuntarse a actividades.');
        }

        if ($this->isUserEnrolled($activity)) {
            return back()->with('error', 'Ya estas apuntado a esta actividad.');
        }

        if ($this->isActivityFull($activity)) {
            WaitlistEntry::firstOrCreate([
                'user_id' => Auth::id(),
                'activity_id' => $activity->id,
            ]);

            return back()->with('success', 'La clase esta completa. Te hemos puesto en lista de espera.');
        }

        Enrollment::create([
            'user_id' => Auth::id(),
            'activity_id' => $activity->id,
        ]);

        WaitlistEntry::where('user_id', Auth::id())->where('activity_id', $activity->id)->delete();

        return back()->with('success', 'Te has apuntado correctamente a la actividad.');
    }

    public function unenroll(Activity $activity)
    {
        if (! $this->canEnrollInActivities(Auth::user()->role)) {
            return back()->with('error', 'Solo los alumnos pueden desapuntarse de actividades.');
        }

        $deleted = Enrollment::where('user_id', Auth::id())->where('activity_id', $activity->id)->delete();
        WaitlistEntry::where('user_id', Auth::id())->where('activity_id', $activity->id)->delete();

        if (! $deleted) {
            return back()->with('error', 'No estabas apuntado a esta actividad.');
        }

        $this->promoteFirstWaitlistedUser($activity);

        return back()->with('success', 'Te has desapuntado correctamente de la actividad.');
    }

    public function leaveWaitlist(Activity $activity)
    {
        WaitlistEntry::where('user_id', Auth::id())->where('activity_id', $activity->id)->delete();

        return back()->with('success', 'Has salido de la lista de espera.');
    }

    public function showStudents(Activity $activity)
    {
        $this->authorizeActivityAdmin();

        $students = $activity->students;
        $waitlistEntries = $activity->waitlistEntries()->with('user')->oldest()->get();

        return view('activities.students', compact('activity', 'students', 'waitlistEntries'));
    }

    public function spaces()
    {
        $this->authorizeActivityAdmin();

        $spaces = Space::withCount('activities')->orderBy('name')->get();

        return view('admin.spaces', compact('spaces'));
    }

    public function storeSpace(Request $request)
    {
        $this->authorizeActivityAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:spaces,name',
            'capacity' => 'required|integer|min:1|max:500',
        ]);

        Space::create($data);

        return back()->with('success', 'Sala creada correctamente.');
    }

    public function updateSpace(Request $request, Space $space)
    {
        $this->authorizeActivityAdmin();

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:spaces,name,'.$space->id,
            'capacity' => 'required|integer|min:1|max:500',
        ]);

        $space->update($data);

        return back()->with('success', 'Sala actualizada correctamente.');
    }

    public function destroySpace(Space $space)
    {
        $this->authorizeActivityAdmin();

        if ($space->activities()->exists()) {
            return back()->with('error', 'No puedes eliminar una sala con actividades asociadas.');
        }

        $space->delete();

        return back()->with('success', 'Sala eliminada correctamente.');
    }

    private function activityQuery()
    {
        return Activity::with(['space', 'teacher'])
            ->withCount(['students', 'waitlistEntries'])
            ->orderBy('scheduled_at');
    }

    private function validateActivityRequest(Request $request): array
    {
        return $request->validate([
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

    private function firstScheduleConflict(int $spaceId, array $scheduledDates, ?int $ignoreActivityId = null): ?Carbon
    {
        foreach ($scheduledDates as $scheduledAt) {
            $query = Activity::where('space_id', $spaceId)->where('scheduled_at', $scheduledAt);

            if ($ignoreActivityId) {
                $query->where('id', '!=', $ignoreActivityId);
            }

            if ($query->exists()) {
                return $scheduledAt;
            }
        }

        return null;
    }

    private function isActivityFull(Activity $activity): bool
    {
        return $activity->students()->count() >= ($activity->space->capacity ?? 0);
    }

    private function isUserEnrolled(Activity $activity): bool
    {
        return Enrollment::where('user_id', Auth::id())->where('activity_id', $activity->id)->exists();
    }

    private function promoteFirstWaitlistedUser(Activity $activity): void
    {
        if ($this->isActivityFull($activity)) {
            return;
        }

        $entry = $activity->waitlistEntries()->oldest()->first();
        if (! $entry) {
            return;
        }

        Enrollment::firstOrCreate([
            'user_id' => $entry->user_id,
            'activity_id' => $activity->id,
        ]);

        $entry->delete();
    }

    private function authorizeActivityAdmin(): void
    {
        if (! $this->canCreateActivities(Auth::user()->role)) {
            abort(403, 'No tienes permiso para gestionar actividades.');
        }
    }

    private function canCreateActivities(string $role): bool
    {
        return in_array($role, ['admin', 'teacher'], true);
    }

    private function canEnrollInActivities(string $role): bool
    {
        return in_array($role, ['student', 'user'], true);
    }
}

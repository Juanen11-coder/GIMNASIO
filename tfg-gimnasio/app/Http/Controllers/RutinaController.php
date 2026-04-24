<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rutina;
use App\Models\DiaEntreno;
use App\Models\GrupoMuscular;
use App\Models\Ejercicio;

class RutinaController extends Controller
{
    // Mostrar mis rutinas
    public function index()
    {
        $rutinas = Rutina::where('user_id', auth()->id())
            ->with('diasEntreno.gruposMusculares.ejercicios')
            ->get();

        return view('rutinas.index', compact('rutinas'));
    }

    // Formulario crear rutina
    public function create()
    {
        return view('rutinas.create');
    }

    // Guardar rutina
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $rutina = Rutina::create([
            'user_id' => auth()->id(),
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('rutinas.edit', $rutina->id)
            ->with('success', 'Rutina creada. Ahora añade los días y ejercicios.');
    }

    // Editar rutina
    public function edit(Rutina $rutina)
    {
        // Verificar que el usuario es dueño de la rutina
        if ($rutina->user_id != auth()->id()) {
            abort(403, 'No tienes permiso para editar esta rutina');
        }

        $rutina->load('diasEntreno.gruposMusculares.ejercicios');

        return view('rutinas.edit', compact('rutina'));
    }

    // Añadir día de entreno
    public function addDia(Request $request, Rutina $rutina)
    {
        $request->validate([
            'nombre' => 'required|string',
            'orden' => 'nullable|integer',
        ]);

        $rutina->diasEntreno()->create([
            'nombre' => $request->nombre,
            'orden' => $request->orden ?? 0,
        ]);

        return back()->with('success', 'Día añadido');
    }

    // Añadir grupo muscular a un día
    public function addGrupo(Request $request, DiaEntreno $dia)
    {
        $request->validate(['nombre' => 'required|string']);

        $dia->gruposMusculares()->create([
            'nombre' => $request->nombre,
            'orden' => 0,
        ]);

        return back()->with('success', 'Grupo muscular añadido');
    }

    // Añadir ejercicio a un grupo muscular
    public function addEjercicio(Request $request, GrupoMuscular $grupo)
    {
        $request->validate([
            'nombre' => 'required|string',
            'series' => 'nullable|integer',
            'repeticiones' => 'nullable|integer',
            'peso' => 'nullable|numeric',
            'descanso' => 'nullable|integer',
        ]);

        $grupo->ejercicios()->create([
            'nombre' => $request->nombre,
            'series' => $request->series,
            'repeticiones' => $request->repeticiones,
            'peso' => $request->peso,
            'descanso' => $request->descanso,
            'orden' => 0,
        ]);

        return back()->with('success', 'Ejercicio añadido');
    }

    // Publicar rutina en el feed
    public function publish(Rutina $rutina)
    {
        $rutina->load('diasEntreno.gruposMusculares.ejercicios');

        // Crear un post con el contenido de la rutina
        $post = \App\Models\Post::create([
            'user_id' => auth()->id(),
            'content' => "📋 Mi rutina: " . $rutina->nombre . "\n\n" . $this->formatRutinaForPost($rutina),
            'likes' => 0,
            'comments_count' => 0,
        ]);

        return redirect()->route('feed')
            ->with('success', '¡Rutina publicada en el feed!');
    }

    // Eliminar día
    public function deleteDia(DiaEntreno $dia)
    {
        $dia->delete();
        return back()->with('success', 'Día eliminado');
    }

    // Eliminar grupo muscular
    public function deleteGrupo(GrupoMuscular $grupo)
    {
        $grupo->delete();
        return back()->with('success', 'Grupo eliminado');
    }

    // Eliminar ejercicio
    public function deleteEjercicio(Ejercicio $ejercicio)
    {
        $ejercicio->delete();
        return back()->with('success', 'Ejercicio eliminado');
    }

    private function formatRutinaForPost($rutina)
    {
        $texto = "";
        foreach ($rutina->diasEntreno as $dia) {
            $texto .= "**【" . $dia->nombre . "】**\n";
            foreach ($dia->gruposMusculares as $grupo) {
                $texto .= "  🏋️ *" . $grupo->nombre . "*\n";
                foreach ($grupo->ejercicios as $ejercicio) {
                    $texto .= "    - " . $ejercicio->nombre;
                    if ($ejercicio->series) $texto .= " · " . $ejercicio->series . " series";
                    if ($ejercicio->repeticiones) $texto .= " · " . $ejercicio->repeticiones . " reps";
                    if ($ejercicio->peso) $texto .= " · " . $ejercicio->peso . " kg";
                    $texto .= "\n";
                }
            }
            $texto .= "\n";
        }
        return $texto;
    }
}

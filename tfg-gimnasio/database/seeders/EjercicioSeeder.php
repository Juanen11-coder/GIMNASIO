<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EjercicioSeeder extends Seeder
{
    public function run()
    {
        $ejercicios = [
            // Pecho
            ['grupo_muscular_nombre' => 'Pecho', 'nombre' => 'Press banca plano', 'imagen' => null],
            ['grupo_muscular_nombre' => 'Pecho', 'nombre' => 'Press banca inclinado', 'imagen' => null],
            ['grupo_muscular_nombre' => 'Pecho', 'nombre' => 'Aperturas con mancuernas', 'imagen' => null],
            ['grupo_muscular_nombre' => 'Pecho', 'nombre' => 'Fondos en paralelas', 'imagen' => null],
            ['grupo_muscular_nombre' => 'Pecho', 'nombre' => 'Pullover', 'imagen' => null],
            // Espalda
            ['grupo_muscular_nombre' => 'Espalda', 'nombre' => 'Dominadas', 'imagen' => null],
            ['grupo_muscular_nombre' => 'Espalda', 'nombre' => 'Remo con barra', 'imagen' => null],
            ['grupo_muscular_nombre' => 'Espalda', 'nombre' => 'Jalón al pecho', 'imagen' => null],
            ['grupo_muscular_nombre' => 'Espalda', 'nombre' => 'Peso muerto', 'imagen' => null],
            // Piernas
            ['grupo_muscular_nombre' => 'Cuádriceps', 'nombre' => 'Sentadilla', 'imagen' => null],
            ['grupo_muscular_nombre' => 'Cuádriceps', 'nombre' => 'Prensa', 'imagen' => null],
            ['grupo_muscular_nombre' => 'Isquiotibiales', 'nombre' => 'Peso muerto rumano', 'imagen' => null],
            ['grupo_muscular_nombre' => 'Isquiotibiales', 'nombre' => 'Curl femoral', 'imagen' => null],
        ];

        foreach ($ejercicios as $ejercicio) {
            $grupo = DB::table('grupos_musculares_predefinidos')
                ->where('nombre', $ejercicio['grupo_muscular_nombre'])
                ->first();

            if ($grupo) {
                DB::table('ejercicios_predefinidos')->insert([
                    'grupo_muscular_id' => $grupo->id,
                    'nombre' => $ejercicio['nombre'],
                    'imagen' => $ejercicio['imagen'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

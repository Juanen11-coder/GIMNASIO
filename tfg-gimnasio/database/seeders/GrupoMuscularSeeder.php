<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrupoMuscularSeeder extends Seeder
{
    public function run()
    {
        $grupos = [
            ['nombre' => 'Pecho', 'orden' => 1],
            ['nombre' => 'Espalda', 'orden' => 2],
            ['nombre' => 'Hombro', 'orden' => 3],
            ['nombre' => 'Bíceps', 'orden' => 4],
            ['nombre' => 'Tríceps', 'orden' => 5],
            ['nombre' => 'Cuádriceps', 'orden' => 6],
            ['nombre' => 'Isquiotibiales', 'orden' => 7],
            ['nombre' => 'Glúteos', 'orden' => 8],
            ['nombre' => 'Gemelos', 'orden' => 9],
            ['nombre' => 'Abdominales', 'orden' => 10],
            ['nombre' => 'Lumbares', 'orden' => 11],
            ['nombre' => 'Trapecio', 'orden' => 12],
            ['nombre' => 'Antebrazo', 'orden' => 13],
        ];

        foreach ($grupos as $grupo) {
            DB::table('grupos_musculares_predefinidos')->insert($grupo);
        }
    }
}

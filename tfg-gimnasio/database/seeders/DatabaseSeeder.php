<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear Espacios
        \App\Models\Space::create(['name' => 'Gimnasio Principal', 'capacity' => 30]);
        \App\Models\Space::create(['name' => 'Sala de Yoga', 'capacity' => 15]);
        \App\Models\Space::create(['name' => 'Pista de Pádel', 'capacity' => 4]);

        // Crear un Profesor de prueba
        \App\Models\User::create([
            'name' => 'Profesor Juan',
            'email' => 'juan@profe.com',
            'password' => bcrypt('123456'),
            'role' => 'teacher'
        ]);

        // Crear un Alumno de prueba
        foreach (['Pedro', 'Ana', 'Luis'] as $nombre) {
            \App\Models\User::create([
                'name' => 'Alumno ' . $nombre,
                'email' => strtolower($nombre) . '@alumno.com',
                'password' => bcrypt('123456'),
                'role' => 'student'
            ]);
        }

    }
}

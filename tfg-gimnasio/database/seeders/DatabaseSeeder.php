<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Space;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $spaces = collect([
            ['name' => 'Gimnasio Principal', 'capacity' => 30],
            ['name' => 'Sala de Yoga', 'capacity' => 15],
            ['name' => 'Sala Funcional', 'capacity' => 18],
            ['name' => 'Pista de Padel', 'capacity' => 4],
        ])->map(fn ($space) => Space::updateOrCreate(['name' => $space['name']], $space));

        $admin = User::updateOrCreate(
            ['email' => 'admin@gymtonic.com'],
            [
                'name' => 'Admin GymTonic',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'fitness_goal' => 'Gestionar clases y salas',
                'fitness_level' => 'avanzado',
            ]
        );

        $teacher = User::updateOrCreate(
            ['email' => 'juan@profe.com'],
            [
                'name' => 'Profesor Juan',
                'password' => Hash::make('12345678'),
                'role' => 'teacher',
                'fitness_goal' => 'Dirigir clases de fuerza y movilidad',
                'fitness_level' => 'avanzado',
            ]
        );

        foreach (['Pedro', 'Ana', 'Luis'] as $name) {
            User::updateOrCreate(
                ['email' => strtolower($name).'@alumno.com'],
                [
                    'name' => 'Alumno '.$name,
                    'password' => Hash::make('12345678'),
                    'role' => 'student',
                    'fitness_goal' => 'Mejorar condicion fisica',
                    'fitness_level' => 'principiante',
                ]
            );
        }

        $classes = [
            ['title' => 'Spinning', 'space' => 'Gimnasio Principal', 'category' => 'cardio', 'days' => 1, 'time' => '18:00'],
            ['title' => 'Yoga', 'space' => 'Sala de Yoga', 'category' => 'relax', 'days' => 2, 'time' => '10:00'],
            ['title' => 'CrossFit', 'space' => 'Sala Funcional', 'category' => 'strength', 'days' => 3, 'time' => '19:00'],
            ['title' => 'Pilates', 'space' => 'Sala de Yoga', 'category' => 'relax', 'days' => 4, 'time' => '17:30'],
        ];

        foreach ($classes as $class) {
            $date = Carbon::now()->addDays($class['days'])->format('Y-m-d').' '.$class['time'];
            $space = $spaces->firstWhere('name', $class['space']);

            Activity::updateOrCreate(
                ['title' => $class['title'], 'scheduled_at' => Carbon::parse($date)],
                [
                    'user_id' => $teacher->id ?: $admin->id,
                    'space_id' => $space->id,
                    'category' => $class['category'],
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
        ]);

        User::factory()->create([
            'name' => 'María García',
            'email' => 'maria@example.com',
        ]);

        User::factory()->create([
            'name' => 'Carlos López',
            'email' => 'carlos@example.com',
        ]);

        User::factory()->create([
            'name' => 'Ana Rodríguez',
            'email' => 'ana@example.com',
        ]);
    }
}

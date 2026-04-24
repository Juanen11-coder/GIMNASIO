<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    public function run()
    {
        // Asegúrate de que hay usuarios creados
        $users = User::all();

        if ($users->count() == 0) {
            $this->command->info('Primero crea usuarios ejecutando php artisan db:seed --class=UserSeeder');
            return;
        }

        // Datos de prueba copiados de tu posts.json
        $posts = [
            [
                'user_id' => 1,
                'content' => '🔥 ¡Nuevo récord personal! 100kg en press banca. ¡Vamos a por más! 💪',
                'exercise' => 'Press banca',
                'weight' => 100,
                'reps' => 8,
                'sets' => 3,
                'likes' => 12,
                'comments_count' => 3,
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => 1,
                'content' => '🏃‍♂️ Entrenamiento de cardio completado. 5km en 25 minutos.',
                'exercise' => 'Running',
                'weight' => null,
                'reps' => null,
                'sets' => null,
                'likes' => 8,
                'comments_count' => 2,
                'created_at' => now()->subDays(1),
            ],
            // Añade más posts según necesites
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}

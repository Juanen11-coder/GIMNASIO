<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_entrenamiento', function (Blueprint $table) {
            $table->id();
            // Relación con la tabla posts
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            
            // Relación con grupos_musculares (que es como llamaste a tu tabla)
            $table->foreignId('musculo_id')->constrained('grupos_musculares')->onDelete('cascade');
            
            $table->string('ejercicio');
            $table->integer('series')->nullable();
            $table->integer('repeticiones')->nullable();
            $table->decimal('peso', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_entrenamiento');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ejercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_muscular_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->integer('series')->nullable();
            $table->integer('repeticiones')->nullable();
            $table->decimal('peso', 5, 1)->nullable();
            $table->integer('descanso')->nullable(); // segundos
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ejercicios');
    }
};

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
        Schema::create('grupos_musculares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dia_entreno_id')->constrained()->onDelete('cascade');
            $table->string('nombre'); // "Pecho", "Hombro", "Tríceps"
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos_musculares');
    }
};

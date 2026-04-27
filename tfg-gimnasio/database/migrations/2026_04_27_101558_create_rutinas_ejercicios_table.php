<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rutinas_ejercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rutina_guardada_id')->constrained()->onDelete('cascade');
            $table->foreignId('musculo_id')->constrained();
            $table->string('ejercicio');
            $table->integer('series')->nullable();
            $table->integer('repeticiones')->nullable();
            $table->decimal('peso', 5, 1)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rutinas_ejercicios');
    }
};

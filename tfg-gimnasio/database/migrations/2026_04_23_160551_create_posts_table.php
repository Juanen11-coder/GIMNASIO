<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->string('exercise')->nullable();
            $table->decimal('weight', 5, 1)->nullable();
            $table->integer('reps')->nullable();
            $table->integer('sets')->nullable();
            $table->string('image')->nullable();
            $table->integer('likes')->default(0);
            $table->integer('comments_count')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('fitness_goal')->nullable()->after('role');
            $table->string('fitness_level')->nullable()->after('fitness_goal');
            $table->unsignedSmallInteger('height_cm')->nullable()->after('fitness_level');
            $table->decimal('weight_kg', 5, 2)->nullable()->after('height_cm');
        });

        DB::table('users')->where('role', 'user')->update(['role' => 'student']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['fitness_goal', 'fitness_level', 'height_cm', 'weight_kg']);
        });
    }
};

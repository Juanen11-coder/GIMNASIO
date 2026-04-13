<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;

    // Esto permite que Laravel deje escribir en estos campos
    protected $fillable = [
        'user_id',
        'activity_id'
    ];

    // Relación: Una inscripción pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Una inscripción pertenece a una actividad
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
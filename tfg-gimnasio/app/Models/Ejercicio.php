<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ejercicio extends Model
{
    protected $table = 'ejercicios';

    protected $fillable = [
        'grupo_muscular_id', 'nombre', 'series', 'repeticiones',
        'peso', 'descanso', 'imagen', 'orden'
    ];

    public function grupoMuscular(): BelongsTo
    {
        return $this->belongsTo(GrupoMuscular::class);
    }
}

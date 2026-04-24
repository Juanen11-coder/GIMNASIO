<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GrupoMuscular extends Model
{
    protected $table = 'grupos_musculares';

    protected $fillable = ['dia_entreno_id', 'nombre', 'orden'];

    public function diaEntreno(): BelongsTo
    {
        return $this->belongsTo(DiaEntreno::class);
    }

    public function ejercicios(): HasMany
    {
        return $this->hasMany(Ejercicio::class, 'grupo_muscular_id');
    }
}

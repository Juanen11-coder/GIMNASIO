<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiaEntreno extends Model
{
    protected $table = 'dias_entrenos';

    protected $fillable = ['rutina_id', 'nombre', 'orden'];

    public function rutina(): BelongsTo
    {
        return $this->belongsTo(Rutina::class);
    }

    public function gruposMusculares(): HasMany
    {
        return $this->hasMany(GrupoMuscular::class, 'dia_entreno_id');
    }
}

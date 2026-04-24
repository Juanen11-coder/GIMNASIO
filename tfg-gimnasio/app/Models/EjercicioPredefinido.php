<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EjercicioPredefinido extends Model
{
    protected $fillable = ['musculo_id', 'nombre'];
    protected $table = 'ejercicios_predefinidos'; 

    public function musculo(): BelongsTo
    {
        return $this->belongsTo(Musculo::class);
    }
}

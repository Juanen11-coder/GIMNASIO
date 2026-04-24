<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleEntrenamiento extends Model
{
    protected $table = 'detalles_entrenamiento';

    protected $fillable = [
        'post_id', 'musculo_id', 'ejercicio', 'series', 'repeticiones', 'peso'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function musculo(): BelongsTo
    {
        return $this->belongsTo(Musculo::class);
    }
}

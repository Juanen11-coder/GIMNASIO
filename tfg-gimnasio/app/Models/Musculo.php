<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Musculo extends Model
{
    protected $fillable = ['nombre', 'orden'];

    public function ejerciciosPredefinidos(): HasMany
    {
        return $this->hasMany(EjercicioPredefinido::class);
    }
}

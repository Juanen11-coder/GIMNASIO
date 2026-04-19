<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    protected $fillable = ['name', 'capacity'];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
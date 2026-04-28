<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'content', 'exercise', 'weight', 'reps', 'sets',
        'image', 'likes', 'comments_count'
    ];

    protected $casts = [
        'weight' => 'decimal:1',
        'reps' => 'integer',
        'sets' => 'integer',
        'likes' => 'integer',
        'comments_count' => 'integer',
    ];

    // Relación: un post pertenece a un usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function detalles()
{
    return $this->hasMany(DetalleEntrenamiento::class);
}
public function likes()
{
    return $this->hasMany(Like::class);
}

public function isLikedByUser($userId = null)
{
    $userId = $userId ?? auth()->id();
    return $this->likes()->where('user_id', $userId)->exists();
}

public function getLikesCountAttribute()
{
    return $this->likes()->count();
}
}

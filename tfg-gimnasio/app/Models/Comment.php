<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'comment',
    ];

    protected $appends = ['content'];

    public function getContentAttribute()
    {
        return $this->comment;
    }

    // Relación: un comentario pertenece a un usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación: un comentario pertenece a un post
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}

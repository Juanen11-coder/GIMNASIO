<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Friendship;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
        'fitness_goal',
        'fitness_level',
        'height_cm',
        'weight_kg',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relaciones para el sistema de amigos
    public function sentFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'user_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'friend_id');
    }

    // Accessor para obtener amigos (se usa como $user->friends)
    public function getFriendsAttribute()
    {
        $friendIds = Friendship::where(function($query) {
            $query->where('user_id', $this->id)
                  ->orWhere('friend_id', $this->id);
        })->where('status', 'accepted')
          ->get()
          ->map(function($friendship) {
              return $friendship->user_id == $this->id ? $friendship->friend_id : $friendship->user_id;
          });

        return User::whereIn('id', $friendIds)->get();
    }

    // Método para verificar si son amigos
    public function isFriendWith($user)
    {
        return Friendship::where(function($query) use ($user) {
            $query->where('user_id', $this->id)->where('friend_id', $user->id);
        })->orWhere(function($query) use ($user) {
            $query->where('user_id', $user->id)->where('friend_id', $this->id);
        })->where('status', 'accepted')->exists();
    }

    // Método para verificar si hay una solicitud pendiente
    public function hasPendingRequestWith($user)
    {
        return Friendship::where(function($query) use ($user) {
            $query->where('user_id', $this->id)->where('friend_id', $user->id);
        })->orWhere(function($query) use ($user) {
            $query->where('user_id', $user->id)->where('friend_id', $this->id);
        })->where('status', 'pending')->exists();
    }



    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}

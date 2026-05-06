<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['title', 'user_id', 'space_id', 'scheduled_at', 'category'];

    public function space()
    {
        return $this->belongsTo(Space::class);
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }

    public function waitlistEntries()
    {
        return $this->hasMany(WaitlistEntry::class);
    }

    public function waitlistedUsers()
    {
        return $this->belongsToMany(User::class, 'waitlist_entries');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['from', 'title', 'message'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user')
                    ->withPivot('is_read', 'created_at', 'updated_at')
                    ->withTimestamps();
    }
}

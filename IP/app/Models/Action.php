<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'action_role')->withTimestamps();;
    }

}

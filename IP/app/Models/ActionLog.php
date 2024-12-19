<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    use HasFactory;

    protected $table = 'action_logs';

    protected $fillable = [
        'user_id',
        'action',
        'target',
        'status',
        'ip_address',
        'details',
    ];
}

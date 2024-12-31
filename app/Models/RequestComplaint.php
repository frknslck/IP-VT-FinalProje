<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RequestComplaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'RorC',
        'subject',
        'category',
        'message',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

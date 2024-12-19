<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'user_id', 'image_url', 'status'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
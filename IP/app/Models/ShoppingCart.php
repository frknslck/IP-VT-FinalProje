<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'session_id'];

    public function items()
    {
        return $this->hasMany(ShoppingCartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
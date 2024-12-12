<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'session_id', 'coupon_id'];

    public function items()
    {
        return $this->hasMany(ShoppingCartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->productVariant->getEffectivePrice();
        });
    }

    public function getDiscountAttribute()
    {
        if ($this->coupon) {
            return $this->coupon->calculateDiscount($this->subtotal);
        }
        return 0;
    }

    public function getTotalAttribute()
    {
        return max(0, $this->subtotal - $this->discount);
    }
}
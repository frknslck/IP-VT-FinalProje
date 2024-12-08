<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCartItem extends Model
{
    use HasFactory;

    protected $fillable = ['shopping_cart_id', 'product_variant_id', 'quantity'];

    public function shoppingCart()
    {
        return $this->belongsTo(ShoppingCart::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
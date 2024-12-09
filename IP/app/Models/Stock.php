<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['sku', 'quantity'];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'sku', 'sku');
    }
}
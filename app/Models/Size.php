<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}

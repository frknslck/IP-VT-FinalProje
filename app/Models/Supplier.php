<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
    ];

    public function productVariants()
    {
        return $this->belongsToMany(ProductVariant::class, 'supplied_product_variants')
                    ->withPivot('cost', 'quantity')
                    ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'brand_id',
        'is_active',
        'best_seller'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // public function getTotalStockAttribute()
    // {
    //     // dd($this->variants()->sum('stock'));
    //     return $this->variants->sum('stock');
    // }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function campaigns()
    {
        return $this->belongsTo(Campaign::class, 'campaign_product')
            ->withTimestamps();
    }
}
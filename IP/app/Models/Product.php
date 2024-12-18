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
        'image_url',
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
        return $this->belongsToMany(Campaign::class, 'campaign_product')
            ->withTimestamps();
    }

    public function getDiscountedPriceAttribute()
    {
        $activeCampaign = $this->campaigns()->where('is_active', true)->first();
        if ($activeCampaign) {
            if ($activeCampaign->type === 'fixed') {
                return max(0, $this->price - $activeCampaign->value);
            } else {
                return $this->price * (1 - $activeCampaign->value / 100);
            }
        }
        return $this->price;
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getRatingCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function getPrice()
    {
        return $this->variants->isNotEmpty() ? $this->variants->min('price') : $this->price;
    }

    public function getMinPriceAttribute()
    {
        return $this->variants->min('price');
    }

    public function getMaxPriceAttribute()
    {
        return $this->variants->max('price');
    }
}
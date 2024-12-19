<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'price',
        'color_id',
        'size_id',
        'material_id',
        'stock'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplied_product_variants')
                    ->withPivot('cost', 'quantity')
                    ->withTimestamps();
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'sku', 'sku');
    }

    public function getEffectivePrice()
    {
        $product = $this->product;
        $activeCampaign = $product->campaigns()->where('is_active', true)->first();
        
        if ($activeCampaign) {
            if ($activeCampaign->type === 'fixed') {
                return max(0, $this->price - $activeCampaign->value);
            } else {
                return $this->price * (1 - $activeCampaign->value / 100);
            }
        }
        return $this->price;
    }


}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartItem;
use App\Models\ProductVariant;

class ShoppingCartSeeder extends Seeder
{
    public function run()
    {
        ShoppingCart::create([
            'user_id' => 1,
        ]);

        for ($i = 0; $i < 5; $i++) {
            $productVariantId = ProductVariant::orderBy('id', 'DESC')->first()->id;
    
            ShoppingCartItem::create([
                'shopping_cart_id' => 1,
                'product_variant_id' => rand(1, $productVariantId),
                'quantity' => rand(1, 5)
            ]);
        }
    }
}


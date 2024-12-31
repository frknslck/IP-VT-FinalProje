<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i <12; $i++) {
            Wishlist::create([
                'user_id' => 1,
                'product_id' => rand(1, Product::count())
            ]);
        }
    }
}


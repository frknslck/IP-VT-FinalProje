<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BrandSeeder::class,
            CategorySeeder::class,
            AttributeSeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            AddressSeeder::class,
            WishlistSeeder::class,
            ShoppingCartSeeder::class,
            PaymentMethodSeeder::class,
            CouponSeeder::class,
            CampaignSeeder::class,
            ActionSeeder::class,
            BlogSeeder::class,
        ]);
    }
}


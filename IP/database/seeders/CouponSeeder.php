<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        $coupons = [
            [
                'code' => 'DISCOUNT10',
                'type' => 'percentage',
                'value' => 10.00,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'usage_limit' => 100,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'FIXED50',
                'type' => 'fixed',
                'value' => 50.00,
                'start_date' => now(),
                'end_date' => now()->addDays(15),
                'usage_limit' => 50,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'WELCOME5',
                'type' => 'fixed',
                'value' => 5.00,
                'start_date' => now(),
                'end_date' => now()->addDays(60),
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            $coupon = Coupon::create($coupon);
            $coupon->users()->attach([1]);
        }
    }
}


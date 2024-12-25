<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        $paymentmethods = [
            [
                'name' => 'Online Payment',
                'is_active' => true
            ],
            [
                'name' => 'Cash on Delivery',
                'is_active' => true
            ],
            [
                'name' => 'Credit Card on Delivery',
                'is_active' => true
            ],
        ];

        foreach($paymentmethods as $paymentmethod){
            PaymentMethod::create($paymentmethod);
        }
    }
}


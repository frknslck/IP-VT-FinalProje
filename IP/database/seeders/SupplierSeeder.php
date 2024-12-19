<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'name' => 'Supplier One',
                'contact_person' => 'John Doe',
                'email' => 'supplierone@example.com',
                'phone' => '123456789',
                'address' => '123 Supplier St, City, Country',
            ],
            [
                'name' => 'Supplier Two',
                'contact_person' => 'Jane Smith',
                'email' => 'suppliertwo@example.com',
                'phone' => '987654321',
                'address' => '456 Supplier Ave, City, Country',
            ],
            [
                'name' => 'Supplier Three',
                'contact_person' => 'Alice Johnson',
                'email' => 'supplierthree@example.com',
                'phone' => '555123456',
                'address' => '789 Supplier Rd, City, Country',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
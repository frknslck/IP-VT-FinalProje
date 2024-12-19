<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    public function run()
    {
        $addresses = [
            [
                'user_id' => 1,
                'country' => 'Turkey',
                'city' => 'Istanbul',
                'neighborhood' => 'Kadikoy',
                'building_no' => '12A',
                'apartment_no' => '5',
            ],
            [
                'user_id' => 1,
                'country' => 'Turkey',
                'city' => 'Ankara',
                'neighborhood' => 'Cankaya',
                'building_no' => '34',
                'apartment_no' => '10',
            ],
            [
                'user_id' => 1,
                'country' => 'Turkey',
                'city' => 'Izmir',
                'neighborhood' => 'Alsancak',
                'building_no' => '7B',
                'apartment_no' => '3',
            ],
            [
                'user_id' => 2,
                'country' => 'Turkey',
                'city' => 'Antalya',
                'neighborhood' => 'Lara',
                'building_no' => '5C',
                'apartment_no' => '8',
            ],
            [
                'user_id' => 2,
                'country' => 'Turkey',
                'city' => 'Adana',
                'neighborhood' => 'Seyhan',
                'building_no' => '15',
                'apartment_no' => '4',
            ],
            [
                'user_id' => 2,
                'country' => 'Turkey',
                'city' => 'Gaziantep',
                'neighborhood' => 'Sehitkamil',
                'building_no' => '9A',
                'apartment_no' => '1',
            ],
            [
                'user_id' => 3,
                'country' => 'Turkey',
                'city' => 'Bursa',
                'neighborhood' => 'Nilüfer',
                'building_no' => '18D',
                'apartment_no' => '6',
            ],
            [
                'user_id' => 3,
                'country' => 'Turkey',
                'city' => 'Mersin',
                'neighborhood' => 'Mezitli',
                'building_no' => '22',
                'apartment_no' => '9',
            ],
            [
                'user_id' => 3,
                'country' => 'Turkey',
                'city' => 'Eskisehir',
                'neighborhood' => 'Odunpazari',
                'building_no' => '10',
                'apartment_no' => '2',
            ],
        ];
        
        foreach($addresses as $address){
            Address::create($address);
        }
    }
}


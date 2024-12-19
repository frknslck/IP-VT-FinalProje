<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Action;

class ActionSeeder extends Seeder
{
    public function run()
    {
        $actions = [
            [
                'name' => 'Category Management'
            ],
            [
                'name' => 'Campaign Management'
            ],
            [
                'name' => 'Coupon Management'
            ],
            [
                'name' => 'Product Management'
            ],
            [
                'name' => 'Order Management'
            ],
            [
                'name' => 'Supply Management'
            ],
            [
                'name' => 'Notification Service'
            ],
            [
                'name' => 'Requests and Complaints'
            ],
            [
                'name' => 'Blog Management'
            ],
        ];
        
        foreach($actions as $action){
            Action::create($action);
        }

        $mappings = [
            ['role_id' => 1, 'action_id' => 1],
            ['role_id' => 1, 'action_id' => 2],
            ['role_id' => 1, 'action_id' => 3],
            ['role_id' => 1, 'action_id' => 4],
            ['role_id' => 1, 'action_id' => 5],
            ['role_id' => 1, 'action_id' => 6],
            ['role_id' => 1, 'action_id' => 7],
            ['role_id' => 1, 'action_id' => 8],
            ['role_id' => 1, 'action_id' => 9],
            ['role_id' => 2, 'action_id' => 4],
            ['role_id' => 4, 'action_id' => 9],
        ];
        
        foreach($mappings as $map){
            \DB::table('action_role')->insert([
                'role_id' => $map['role_id'],
                'action_id' => $map['action_id'],
            ]);
        }
    }
}


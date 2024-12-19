<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'furkan selçuk',
                'email' => 'fsb@gmail.com',
                'tel_no' => '05309191726',
                'password' => bcrypt('fsb12345'),
                'roles' => ['Admin']
            ],
            [
                'name' => 'nike',
                'email' => 'nike@gmail.com',
                'tel_no' => '05055055501',
                'password' => bcrypt('fsb12345'),
                'roles' => ['Corp']
            ],
            [
                'name' => 'yağmur kaya',
                'email' => 'ymrky@gmail.com',
                'tel_no' => '05055322682',
                'password' => bcrypt('fsb12345'),
                'roles' => ['Blogger']
            ]
        ];

        foreach ($users as $userData) {
            $roles = $userData['roles'];
            unset($userData['roles']);
            
            $user = User::create($userData);
            
            foreach ($roles as $roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $user->roles()->attach($role->id);
                }
            }
        }
    }
}


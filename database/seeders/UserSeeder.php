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
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'tel_no' => '05055055500',
                'password' => bcrypt('admin12345'),
                'roles' => ['Admin']
            ],
            [
                'name' => 'Nike',
                'email' => 'nike@gmail.com',
                'tel_no' => '05055055501',
                'password' => bcrypt('nike12345'),
                'roles' => ['Corp']
            ],
            [
                'name' => 'Blogger User',
                'email' => 'blogger@gmail.com',
                'tel_no' => '05055055502',
                'password' => bcrypt('blogger12345'),
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


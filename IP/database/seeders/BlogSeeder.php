<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;
use App\Models\Role;
use Faker\Factory as Faker;

class BlogSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $adminRoleId = Role::where('name', 'Admin')->first()->id;
        $bloggerRoleId = Role::where('name', 'Blogger')->first()->id;

        $eligibleUsers = User::whereHas('roles', function($query) use ($adminRoleId, $bloggerRoleId) {
            $query->whereIn('roles.id', [$adminRoleId, $bloggerRoleId]);
        })->get();

        if ($eligibleUsers->isEmpty()) {
            $this->command->info('No admin or blogger users found. Skipping blog creation.');
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            Blog::create([
                'title' => $faker->sentence,
                'content' => $faker->paragraphs(3, true),
                'user_id' => $eligibleUsers->random()->id,
                'image_url' => $faker->imageUrl(640, 480, 'blog', true),
                'status' => $faker->randomElement(['draft', 'published', 'archived']),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }

        // $this->command->info('10 blog posts have been created.');
    }
}


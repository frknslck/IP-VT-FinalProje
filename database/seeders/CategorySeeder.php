<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Men',
                'slug' => 'men',
                'children' => [
                    ['name' => 'Men Clothing', 'slug' => 'men-clothing', 'image_url' => 'https://i.ibb.co/k3QQQNQ/R0216-AZ-24-AU-BK27-01-01.jpg',],
                    ['name' => 'Men Shoes', 'slug' => 'men-shoes', 'image_url' => 'https://i.ibb.co/k3QQQNQ/R0216-AZ-24-AU-BK27-01-01.jpg',],
                    ['name' => 'Men Bags', 'slug' => 'men-bags', 'image_url' => 'https://i.ibb.co/k3QQQNQ/R0216-AZ-24-AU-BK27-01-01.jpg',],
                ],
                'image_url' => 'https://i.ibb.co/k3QQQNQ/R0216-AZ-24-AU-BK27-01-01.jpg',
            ],
            [
                'name' => 'Women',
                'slug' => 'women',
                'children' => [
                    ['name' => 'Women Clothing', 'slug' => 'women-clothing', 'image_url' => 'https://i.ibb.co/z2s2wHY/Y3411-AZ-22-CW-RD70-01-02.jpg',],
                    ['name' => 'Women Shoes', 'slug' => 'women-shoes', 'image_url' => 'https://i.ibb.co/z2s2wHY/Y3411-AZ-22-CW-RD70-01-02.jpg',],
                    ['name' => 'Women Bags', 'slug' => 'women-bags', 'image_url' => 'https://i.ibb.co/z2s2wHY/Y3411-AZ-22-CW-RD70-01-02.jpg',],
                ],
                'image_url' => 'https://i.ibb.co/z2s2wHY/Y3411-AZ-22-CW-RD70-01-02.jpg',
            ],
            [
                'name' => 'Kids',
                'slug' => 'kids',
                'children' => [
                    ['name' => 'Kids Clothing', 'slug' => 'kids-clothing', 'image_url' => 'https://i.ibb.co/mtQbhKh/PANCOOO7.png'],
                    ['name' => 'Kids Shoes', 'slug' => 'kids-shoes', 'image_url' => 'https://i.ibb.co/mtQbhKh/PANCOOO7.png'],
                    ['name' => 'Kids Pajamas', 'slug' => 'kids-pajamas', 'image_url' => 'https://i.ibb.co/mtQbhKh/PANCOOO7.png'],
                ],
                'image_url' => 'https://i.ibb.co/mtQbhKh/PANCOOO7.png',
            ],
        ];
        foreach ($categories as $categoryData) {
            $this->createCategory($categoryData);
        }
    }

    private function createCategory(array $data, $parentId = null)
    {
        $category = Category::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'image_url' => $data['image_url'],
            'parent_id' => $parentId,
        ]);

        if (isset($data['children']) && is_array($data['children'])) {
            foreach ($data['children'] as $child) {
                $this->createCategory($child, $category->id);
            }
        }
    }
}


<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductVariant;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $brands = [
            ['name' => 'Nike', 'description' => 'Just Do It'],
            ['name' => 'Adidas', 'description' => 'Impossible is Nothing'],
            ['name' => 'Puma', 'description' => 'Forever Faster'],
        ];
        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        $categories = [
            ['name' => 'Men', 'slug' => 'men'],
            ['name' => 'Women', 'slug' => 'women'],
            ['name' => 'Kids', 'slug' => 'kids'],
        ];
        foreach ($categories as $category) {
            Category::create($category);
        }

        $colors = [
            ['name' => 'Black', 'hex_code' => '#000000', 'chance' => 0.40],
            ['name' => 'White', 'hex_code' => '#FFFFFF', 'chance' => 0.35],
            ['name' => 'Red', 'hex_code' => '#FF0000', 'chance' => 0.20],
            ['name' => 'Blue', 'hex_code' => '#0000FF', 'chance' => 0.10],
            ['name' => 'Green', 'hex_code' => '#008000', 'chance' => 0.25],
            ['name' => 'Yellow', 'hex_code' => '#FFFF00', 'chance' => 0.20],
            ['name' => 'Orange', 'hex_code' => '#FFA500', 'chance' => 0.15],
            ['name' => 'Purple', 'hex_code' => '#800080', 'chance' => 0.15],
            ['name' => 'Pink', 'hex_code' => '#FFC0CB', 'chance' => 0.30],
            ['name' => 'Gray', 'hex_code' => '#808080', 'chance' => 0.25],
        ];
        foreach ($colors as $color) {
            Color::create($color);
        }

        $sizes = [
            ['name' => 'Small', 'code' => 'S', 'chance' => 0.40],
            ['name' => 'Medium', 'code' => 'M', 'chance' => 0.45],
            ['name' => 'Large', 'code' => 'L', 'chance' => 0.50],
            ['name' => 'Extra Large', 'code' => 'XL', 'chance' => 0.40],
            ['name' => 'XXL', 'code' => 'XXL', 'chance' => 0.30],
            ['name' => 'XXXL', 'code' => 'XXXL', 'chance' => 0.20],
        ];
        foreach ($sizes as $size) {
            Size::create($size);
        }

        $materials = [
            ['name' => 'Cotton', 'description' => '100% Cotton', 'chance' => 0.55],
            ['name' => 'Polyester', 'description' => '100% Polyester', 'chance' => 0.35],
            ['name' => 'Cotton Blend', 'description' => '60% Cotton 40% Polyester', 'chance' => 0.20],
            ['name' => 'Wool', 'description' => '100% Wool', 'chance' => 0.25],
            ['name' => 'Silk', 'description' => '100% Silk', 'chance' => 0.15],
            ['name' => 'Linen', 'description' => '100% Linen', 'chance' => 0.20],
            ['name' => 'Nylon', 'description' => '100% Nylon', 'chance' => 0.20],
            ['name' => 'Leather', 'description' => '100% Leather', 'chance' => 0.30]
        ];
        foreach ($materials as $material) {
            Material::create($material);
        }

        $brands = Brand::all();
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        $materials = Material::all();

        foreach ($brands as $brand) {
            for ($i = 1; $i <= 5; $i++) {
                $product = Product::create([
                    'name' => $brand->name . ' Product ' . $i,
                    'description' => 'This is a sample product description for ' . $brand->name . ' Product ' . $i,
                    'price' => rand(10, 100),
                    'brand_id' => $brand->id,
                    'is_active' => true,
                    'best_seller' => rand(0, 1) == 1 ? true : false
                ]);

                $product->categories()->attach($categories->random(rand(1, 2)));

                foreach ($colors as $color) {
                    foreach ($sizes as $size) {
                        foreach($materials as $material){
                if (rand(0, 100) / 100 <= $color->chance &&
                                rand(0, 100) / 100 <= $size->chance &&
                                rand(0, 100) / 100 <= $material->chance) 
                            {
                                ProductVariant::create([
                                    'product_id' => $product->id,
                                    'sku' => 'SKU-' . $product->id . '-' . $color->id . '-' . $size->id . '-' . $material->id,
                                    'name' => $product->name . ' - ' . $color->name . ' - ' . $size->name . ' - ' . $material->name,
                                    'price' => $product->price,
                                    'color_id' => $color->id,
                                    'size_id' => $size->id,
                                    'material_id' => $material->id,
                                ]);
                        } 
                    }
                }
            }
        }
    }
}
}
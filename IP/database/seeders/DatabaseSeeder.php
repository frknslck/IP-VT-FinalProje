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
            ['name' => 'Black', 'hex_code' => '#000000'],
            ['name' => 'White', 'hex_code' => '#FFFFFF'],
            ['name' => 'Red', 'hex_code' => '#FF0000'],
            ['name' => 'Blue', 'hex_code' => '#0000FF'],
        ];
        foreach ($colors as $color) {
            Color::create($color);
        }

        $sizes = [
            ['name' => 'Small', 'code' => 'S'],
            ['name' => 'Medium', 'code' => 'M'],
            ['name' => 'Large', 'code' => 'L'],
            ['name' => 'Extra Large', 'code' => 'XL'],
        ];
        foreach ($sizes as $size) {
            Size::create($size);
        }

        $materials = [
            ['name' => 'Cotton', 'description' => '100% Cotton'],
            ['name' => 'Polyester', 'description' => '100% Polyester'],
            ['name' => 'Cotton Blend', 'description' => '60% Cotton 40% Polyester'],
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
                ]);

                $product->categories()->attach($categories->random(rand(1, 2)));

                foreach ($colors as $color) {
                    foreach ($sizes as $size) {
                        foreach($materials as $material){
                            ProductVariant::create([
                                'product_id' => $product->id,
                                'sku' => 'SKU-' . $product->id . '-' . $color->id . '-' . $size->id . '-' .$material->id,
                                'name' => $product->name . ' - ' . $color->name . ' - ' . $size->name. ' - ' .$material->name,
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

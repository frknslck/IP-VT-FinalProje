<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\Stock;
use App\Models\Supplier;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $brands = Brand::all();
        $categories = Category::whereNotNull('parent_id')->get();
        $colors = Color::all();
        $materials = Material::all();

        $image_urls = [
            [
                'category' => 'men-clothing',
                'image_url' => 'https://i.ibb.co/jg5hmCz/D0449-AX-24-AU-NM18-01-3.jpg'
            ],
            [
                'category' => 'men-clothing',
                'image_url' => 'https://i.ibb.co/0J0WzmJ/A4114-AX-24-WN-BK81-03-02.jpg'
            ],
            [
                'category' => 'men-clothing',
                'image_url' => 'https://i.ibb.co/Bc7vwXK/A2756-AX-24-WN-BK81-01-02.jpg'
            ],
            [
                'category' => 'men-shoes',
                'image_url' => 'https://i.ibb.co/LJmGjS3/C8780-AX-NS-BE55-02-01.jpg'
            ],
            [
                'category' => 'men-bags',
                'image_url' => 'https://i.ibb.co/XSG8YrS/E0691-AX-NS-BK27-01-01.jpg'
            ],
            [
                'category' => 'women-clothing',
                'image_url' => 'https://i.ibb.co/YpTHRMW/E2289-AX-24-WN-BR93-01-01.jpg'
            ],
            [
                'category' => 'women-clothing',
                'image_url' => 'https://i.ibb.co/vYLwpyT/C6402-AX-24-AU-RD54-05-01.jpg'
            ],
            [
                'category' => 'women-clothing',
                'image_url' => 'https://i.ibb.co/FJM1pTz/D1096-AX-24-CW-ER103-01-02.jpg'
            ],
            [
                'category' => 'women-shoes',
                'image_url' => 'https://i.ibb.co/cyVL9ZQ/D0356-A8-NS-BG123-02-01.jpg'
            ],
            [
                'category' => 'women-bags',
                'image_url' => 'https://i.ibb.co/4T9WYPh/D3149-AX-NS-BK27-01-01-1.jpg'
            ],
            [
                'category' => 'kids-clothing',
                'image_url' => 'https://i.ibb.co/TTW4c81/T7468-A6-24-AU-BK81-07-01.jpg'
            ],
            [
                'category' => 'kids-clothing',
                'image_url' => 'https://i.ibb.co/BcwQDpn/D8092-A8-24-WN-ER83-01-01.jpg'
            ],
            [
                'category' => 'kids-pajamas',
                'image_url' => 'https://i.ibb.co/BtFZDpw/D5513-A8-24-WN-WT34-01-01.jpg'
            ],
            [
                'category' => 'kids-pajamas',
                'image_url' => 'https://i.ibb.co/zXjvkYH/D8599-A8-24-CW-GR400-01-01-1.jpg'
            ],
            [
                'category' => 'kids-shoes',
                'image_url' => 'https://i.ibb.co/rHmzpMW/B8747-A8-NS-PN4-02-01.jpg'
            ],
            [
                'category' => 'kids-shoes',
                'image_url' => 'https://i.ibb.co/k3kTm5X/C8018-A8-NS-BK23-02-01.jpg'
            ],
        ];

        foreach ($brands as $brand) {
            foreach ($categories as $category) {
                for ($i = 1; $i <= 3; $i++) {
                    
                    $filtered_urls = array_filter($image_urls, function ($image_url) use ($category) {
                        return $image_url['category'] === $category->slug;
                    });
        
                    $random_url = $filtered_urls ? $filtered_urls[array_rand($filtered_urls)] : null;
        
                    $product = Product::create([
                        'name' => $brand->name . ' ' . $category->name . ' ' . $i,
                        'description' => 'This is a sample product description for ' . $brand->name . ' ' . $category->name . ' ' . $i,
                        'image_url' => $random_url ? $random_url['image_url'] : null,
                        'price' => rand(10, 100),
                        'brand_id' => $brand->id,
                        'is_active' => true,
                        'best_seller' => mt_rand(1, 100) <= 25,
                    ]);
        
                    $product->categories()->attach($category->id);
        
                    $this->createProductVariants($product, $category, $colors, $materials);
                }
            }
        }
    }

    private function createProductVariants($product, $category, $colors, $materials)
    {
        $sizeCategory = $this->getSizeCategoryForProductCategory($category->slug);
        $sizes = Size::where('category', $sizeCategory)->get();

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                foreach ($materials as $material) {
                    if (rand(0, 100) / 100 <= $color->chance &&
                        rand(0, 100) / 100 <= $size->chance &&
                        rand(0, 100) / 100 <= $material->chance) 
                    {
                        $sku = 'SKU-' . $product->id . '-' . $color->id . '-' . $size->id . '-' . $material->id;
                        
                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $sku,
                            'name' => $product->name . ' - ' . $color->name . ' - ' . $size->name . ' - ' . $material->name,
                            'price' => $product->price,
                            'color_id' => $color->id,
                            'size_id' => $size->id,
                            'material_id' => $material->id,
                        ]);

                        $randomSupplier = Supplier::inRandomOrder()->first();

                        if ($randomSupplier) {
                            $quantity = rand(1, 20);
                            $cost = rand(10, $product->price);

                            $variant->suppliers()->attach($randomSupplier->id, [
                                'cost' => $cost,
                                'quantity' => $quantity,
                            ]);

                            Stock::create([
                                'sku' => $sku,
                                'quantity' => $quantity
                            ]);
                        }
                    }
                }
            }
        }
    }

    private function getSizeCategoryForProductCategory($categorySlug)
    {
        if (strpos($categorySlug, 'shoes') !== false) {
            return strpos($categorySlug, 'kids') !== false ? 'kids-shoes' : 'adult-shoes';
        } elseif (strpos($categorySlug, 'bags') !== false) {
            return 'bags';
        } else {
            return 'clothing';
        }
    }
}


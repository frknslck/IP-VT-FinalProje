<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color;
use App\Models\Size;
use App\Models\Material;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $this->createColors();
        $this->createSizes();
        $this->createMaterials();
    }

    private function createColors()
    {
        $colors = [
            ['name' => 'Black', 'hex_code' => '#000000', 'chance' => 0.40],
            ['name' => 'White', 'hex_code' => '#FFFFFF', 'chance' => 0.35],
            ['name' => 'Red', 'hex_code' => '#FF0000', 'chance' => 0.30],
            ['name' => 'Blue', 'hex_code' => '#0000FF', 'chance' => 0.30],
            ['name' => 'Green', 'hex_code' => '#008000', 'chance' => 0.25],
            ['name' => 'Orange', 'hex_code' => '#FFA500', 'chance' => 0.15],
            ['name' => 'Purple', 'hex_code' => '#800080', 'chance' => 0.25],
            ['name' => 'Pink', 'hex_code' => '#FFC0CB', 'chance' => 0.30],
            ['name' => 'Gray', 'hex_code' => '#808080', 'chance' => 0.25],
        ];
        foreach ($colors as $color) {
            Color::create($color);
        }
    }

    private function createSizes()
    {
        $sizes = [
            ['name' => 'Small', 'code' => 'S', 'chance' => 0.35, 'category' => 'clothing'],
            ['name' => 'Medium', 'code' => 'M', 'chance' => 0.35, 'category' => 'clothing'],
            ['name' => 'Large', 'code' => 'L', 'chance' => 0.35, 'category' => 'clothing'],
            ['name' => 'Extra Large', 'code' => 'XL', 'chance' => 0.25, 'category' => 'clothing'],
            ['name' => 'XXL', 'code' => 'XXL', 'chance' => 0.20, 'category' => 'clothing'],
            ['name' => 'XXXL', 'code' => 'XXXL', 'chance' => 0.25, 'category' => 'clothing'],
            
            ['name' => '35', 'code' => '35', 'chance' => 0.20, 'category' => 'adult-shoes'],
            ['name' => '36', 'code' => '36', 'chance' => 0.25, 'category' => 'adult-shoes'],
            ['name' => '37', 'code' => '37', 'chance' => 0.30, 'category' => 'adult-shoes'],
            ['name' => '38', 'code' => '38', 'chance' => 0.35, 'category' => 'adult-shoes'],
            ['name' => '39', 'code' => '39', 'chance' => 0.35, 'category' => 'adult-shoes'],
            ['name' => '40', 'code' => '40', 'chance' => 0.45, 'category' => 'adult-shoes'],
            ['name' => '41', 'code' => '41', 'chance' => 0.40, 'category' => 'adult-shoes'],
            ['name' => '42', 'code' => '42', 'chance' => 0.40, 'category' => 'adult-shoes'],
            ['name' => '43', 'code' => '43', 'chance' => 0.35, 'category' => 'adult-shoes'],
            ['name' => '44', 'code' => '44', 'chance' => 0.30, 'category' => 'adult-shoes'],
            ['name' => '45', 'code' => '45', 'chance' => 0.25, 'category' => 'adult-shoes'],
            
            ['name' => '20', 'code' => '20', 'chance' => 0.25, 'category' => 'kids-shoes'],
            ['name' => '21', 'code' => '21', 'chance' => 0.25, 'category' => 'kids-shoes'],
            ['name' => '22', 'code' => '22', 'chance' => 0.30, 'category' => 'kids-shoes'],
            ['name' => '23', 'code' => '23', 'chance' => 0.30, 'category' => 'kids-shoes'],
            ['name' => '24', 'code' => '24', 'chance' => 0.40, 'category' => 'kids-shoes'],
            ['name' => '25', 'code' => '25', 'chance' => 0.40, 'category' => 'kids-shoes'],
            ['name' => '26', 'code' => '26', 'chance' => 0.35, 'category' => 'kids-shoes'],
            ['name' => '27', 'code' => '27', 'chance' => 0.40, 'category' => 'kids-shoes'],
            ['name' => '28', 'code' => '28', 'chance' => 0.40, 'category' => 'kids-shoes'],
            ['name' => '29', 'code' => '29', 'chance' => 0.30, 'category' => 'kids-shoes'],
            ['name' => '30', 'code' => '30', 'chance' => 0.35, 'category' => 'kids-shoes'],
            
            ['name' => 'Small', 'code' => 'S', 'chance' => 0.35, 'category' => 'bags'],
            ['name' => 'Medium', 'code' => 'M', 'chance' => 0.35, 'category' => 'bags'],
            ['name' => 'Large', 'code' => 'L', 'chance' => 0.35, 'category' => 'bags'],
        ];
        foreach ($sizes as $size) {
            Size::create($size);
        }
    }

    private function createMaterials()
    {
        $materials = [
            ['name' => 'Cotton', 'description' => '100% Cotton', 'chance' => 0.35],
            ['name' => 'Cotton Blend', 'description' => '60% Cotton 40% Polyester', 'chance' => 0.40],
            ['name' => 'Polyester', 'description' => '100% Polyester', 'chance' => 0.30],
            ['name' => 'Wool', 'description' => '100% Wool', 'chance' => 0.25],
            ['name' => 'Silk', 'description' => '100% Silk', 'chance' => 0.30],
            ['name' => 'Linen', 'description' => '100% Linen', 'chance' => 0.20],
            ['name' => 'Leather', 'description' => '100% Leather', 'chance' => 0.50]
        ];
        foreach ($materials as $material) {
            Material::create($material);
        }
    }
}

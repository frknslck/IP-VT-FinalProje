<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\PaymentMethod;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartItem;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Stock;
use App\Models\Role;
use App\Models\RoleUser;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->createBrands();
        $this->createCategories();
        $this->createColors();
        $this->createSizes();
        $this->createMaterials();
        $this->createProducts();
        $this->createRoles();
        $this->createUsers();
        $this->createAddresses();
        $this->createWishlistItems();
        $this->createShoppingCart();
        $this->createShoppingCartItems();
        $this->createPaymentMethods();
    }

    private function createRoles()
    {
        $roles = ['Admin', 'Staff', 'Customer', 'Blogger'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }

    private function createUsers()
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
                'name' => 'yağmur kaya',
                'email' => 'ymrky@gmail.com',
                'tel_no' => '05055322682',
                'password' => bcrypt('fsb12345'),
                'roles' => ['Staff', 'Blogger']
            ],
            [
                'name' => 'batu abi',
                'email' => 'batu@gmail.com',
                'tel_no' => '05055055500',
                'password' => bcrypt('fsb12345'),
                'roles' => ['Customer']
            ],
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

    private function createBrands()
    {
        $brands = [
            ['name' => 'Nike', 'description' => 'Just Do It'],
            ['name' => 'Adidas', 'description' => 'Impossible is Nothing'],
            ['name' => 'Puma', 'description' => 'Forever Faster'],
        ];
        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }

    private function createCategories()
    {
        $categories = [
            [
                'name' => 'Men',
                'slug' => 'men',
                'children' => [
                    ['name' => 'Men Clothing', 'slug' => 'men-clothing'],
                    ['name' => 'Men Shoes', 'slug' => 'men-shoes'],
                    ['name' => 'Men Bags', 'slug' => 'men-bags'],
                ],
            ],
            [
                'name' => 'Women',
                'slug' => 'women',
                'children' => [
                    ['name' => 'Woman Clothing', 'slug' => 'women-clothing'],
                    ['name' => 'Woman Shoes', 'slug' => 'women-shoes'],
                    ['name' => 'Woman Bags', 'slug' => 'women-bags'],
                ],
            ],
            [
                'name' => 'Kids',
                'slug' => 'kids',
                'children' => [
                    ['name' => 'Kids Clothing', 'slug' => 'kids-clothing'],
                    ['name' => 'Kids Shoes', 'slug' => 'kids-shoes'],
                    ['name' => 'Kids Pajamas', 'slug' => 'kids-pajamas'],
                ],
            ],
        ];
        foreach ($categories as $categoryData) {
            $this->createCategory($categoryData);
        }
    }

    private function createColors()
    {
        $colors = [
            ['name' => 'Black', 'hex_code' => '#000000', 'chance' => 0.40],
            ['name' => 'White', 'hex_code' => '#FFFFFF', 'chance' => 0.35],
            ['name' => 'Red', 'hex_code' => '#FF0000', 'chance' => 0.20],
            ['name' => 'Blue', 'hex_code' => '#0000FF', 'chance' => 0.10],
            ['name' => 'Green', 'hex_code' => '#008000', 'chance' => 0.25],
            ['name' => 'Orange', 'hex_code' => '#FFA500', 'chance' => 0.15],
            ['name' => 'Purple', 'hex_code' => '#800080', 'chance' => 0.15],
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
            ['name' => 'Small', 'code' => 'S', 'chance' => 0.40, 'category' => 'clothing'],
            ['name' => 'Medium', 'code' => 'M', 'chance' => 0.45, 'category' => 'clothing'],
            ['name' => 'Large', 'code' => 'L', 'chance' => 0.50, 'category' => 'clothing'],
            ['name' => 'Extra Large', 'code' => 'XL', 'chance' => 0.40, 'category' => 'clothing'],
            ['name' => 'XXL', 'code' => 'XXL', 'chance' => 0.30, 'category' => 'clothing'],
            ['name' => 'XXXL', 'code' => 'XXXL', 'chance' => 0.20, 'category' => 'clothing'],
            
            ['name' => '35', 'code' => '35', 'chance' => 0.20, 'category' => 'adult-shoes'],
            ['name' => '36', 'code' => '36', 'chance' => 0.25, 'category' => 'adult-shoes'],
            ['name' => '37', 'code' => '37', 'chance' => 0.30, 'category' => 'adult-shoes'],
            ['name' => '38', 'code' => '38', 'chance' => 0.35, 'category' => 'adult-shoes'],
            ['name' => '39', 'code' => '39', 'chance' => 0.40, 'category' => 'adult-shoes'],
            ['name' => '40', 'code' => '40', 'chance' => 0.45, 'category' => 'adult-shoes'],
            ['name' => '41', 'code' => '41', 'chance' => 0.50, 'category' => 'adult-shoes'],
            ['name' => '42', 'code' => '42', 'chance' => 0.45, 'category' => 'adult-shoes'],
            ['name' => '43', 'code' => '43', 'chance' => 0.40, 'category' => 'adult-shoes'],
            ['name' => '44', 'code' => '44', 'chance' => 0.35, 'category' => 'adult-shoes'],
            ['name' => '45', 'code' => '45', 'chance' => 0.30, 'category' => 'adult-shoes'],
            
            ['name' => '20', 'code' => '20', 'chance' => 0.20, 'category' => 'kids-shoes'],
            ['name' => '21', 'code' => '21', 'chance' => 0.25, 'category' => 'kids-shoes'],
            ['name' => '22', 'code' => '22', 'chance' => 0.30, 'category' => 'kids-shoes'],
            ['name' => '23', 'code' => '23', 'chance' => 0.35, 'category' => 'kids-shoes'],
            ['name' => '24', 'code' => '24', 'chance' => 0.40, 'category' => 'kids-shoes'],
            ['name' => '25', 'code' => '25', 'chance' => 0.45, 'category' => 'kids-shoes'],
            ['name' => '26', 'code' => '26', 'chance' => 0.50, 'category' => 'kids-shoes'],
            ['name' => '27', 'code' => '27', 'chance' => 0.45, 'category' => 'kids-shoes'],
            ['name' => '28', 'code' => '28', 'chance' => 0.40, 'category' => 'kids-shoes'],
            ['name' => '29', 'code' => '29', 'chance' => 0.35, 'category' => 'kids-shoes'],
            ['name' => '30', 'code' => '30', 'chance' => 0.30, 'category' => 'kids-shoes'],
            
            ['name' => 'Small', 'code' => 'S', 'chance' => 0.40, 'category' => 'bags'],
            ['name' => 'Medium', 'code' => 'M', 'chance' => 0.45, 'category' => 'bags'],
            ['name' => 'Large', 'code' => 'L', 'chance' => 0.40, 'category' => 'bags'],
        ];
        foreach ($sizes as $size) {
            Size::create($size);
        }
    }

    private function createMaterials()
    {
        $materials = [
            ['name' => 'Cotton', 'description' => '100% Cotton', 'chance' => 0.55],
            ['name' => 'Cotton Blend', 'description' => '60% Cotton 40% Polyester', 'chance' => 0.20],
            ['name' => 'Polyester', 'description' => '100% Polyester', 'chance' => 0.35],
            ['name' => 'Wool', 'description' => '100% Wool', 'chance' => 0.25],
            ['name' => 'Silk', 'description' => '100% Silk', 'chance' => 0.15],
            ['name' => 'Linen', 'description' => '100% Linen', 'chance' => 0.20],
            ['name' => 'Leather', 'description' => '100% Leather', 'chance' => 0.20]
        ];
        foreach ($materials as $material) {
            Material::create($material);
        }
    }

    private function createProducts()
    {
        $brands = Brand::all();
        $categories = Category::whereNotNull('parent_id')->get(); // Only subcategories
        $colors = Color::all();
        $materials = Material::all();

        foreach ($brands as $brand) {
            foreach ($categories as $category) {
                for ($i = 1; $i <= 5; $i++) {
                    $product = Product::create([
                        'name' => $brand->name . ' ' . $category->name . ' ' . $i,
                        'description' => 'This is a sample product description for ' . $brand->name . ' ' . $category->name . ' ' . $i,
                        'price' => rand(10, 100),
                        'brand_id' => $brand->id,
                        'is_active' => true,
                        'best_seller' => rand(0, 1) == 1,
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

                        Stock::create([
                            'sku' => $sku,
                            'quantity' => rand(0, 20)
                        ]);
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

    private function createCategory(array $data, $parentId = null)
    {
        $category = Category::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'parent_id' => $parentId,
        ]);

        if (isset($data['children']) && is_array($data['children'])) {
            foreach ($data['children'] as $child) {
                $this->createCategory($child, $category->id);
            }
        }
    }

    private function createAddresses(){
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

    private function createWishlistItems(){
        for ($i = 0; $i <12; $i++) {
            Wishlist::create([
                'user_id' => 1,
                'product_id' => rand(1, 135)
            ]);
        }
    }

    private function createShoppingCart(){
        ShoppingCart::create([
            'user_id' => 1,
        ]);
    }

    private function createShoppingCartItems(){
        for ($i = 0; $i < 10; $i++) {
            ShoppingCartItem::create([
                'shopping_cart_id' => 1,
                'product_variant_id' => rand(1, 500),
                'quantity' => rand(1, 5)
            ]);
        }
    }

    private function createPaymentMethods(){
        $paymentmethods = [
            [
                'name' => 'Credit Card',
                'is_active' => true
            ],
            [
                'name' => 'Debit Card',
                'is_active' => true
            ],
            [
                'name' => 'Cash on Delivery',
                'is_active' => true
            ],
            [
                'name' => 'Digital Wallet',
                'is_active' => true
            ],
            [
                'name' => 'Mobile Payment',
                'is_active' => true
            ],
        ];

        foreach($paymentmethods as $paymentmethod){
            PaymentMethod::create($paymentmethod);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Action;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use App\Models\Address;
use App\Models\Campaign;
use App\Models\Coupon;
use App\Models\PaymentMethod;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartItem;
use App\Models\Wishlist;
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

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->createBrands();
        $this->createCategories();
        $this->createColors();
        $this->createSizes();
        $this->createMaterials();
        $this->createSuppliers();
        $this->createProducts();
        $this->createRoles();
        $this->createUsers();
        $this->createAddresses();
        $this->createWishlistItems();
        $this->createShoppingCart();
        $this->createShoppingCartItems();
        $this->createPaymentMethods();
        $this->createCoupons();
        $this->createCampaigns();
        $this->createActions();
    }

    private function createRoles()
    {
        $roles = ['Admin', 'Corp', 'Customer', 'Blogger'];

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
            ['name' => 'Cotton', 'description' => '100% Cotton', 'chance' => 0.40],
            ['name' => 'Cotton Blend', 'description' => '60% Cotton 40% Polyester', 'chance' => 0.50],
            ['name' => 'Polyester', 'description' => '100% Polyester', 'chance' => 0.35],
            ['name' => 'Wool', 'description' => '100% Wool', 'chance' => 0.25],
            ['name' => 'Silk', 'description' => '100% Silk', 'chance' => 0.30],
            ['name' => 'Linen', 'description' => '100% Linen', 'chance' => 0.20],
            ['name' => 'Leather', 'description' => '100% Leather', 'chance' => 0.50]
        ];
        foreach ($materials as $material) {
            Material::create($material);
        }
    }

    private function createSuppliers(){
        $suppliers = [
            [
                'name' => 'Supplier One',
                'contact_person' => 'John Doe',
                'email' => 'supplierone@example.com',
                'phone' => '123456789',
                'address' => '123 Supplier St, City, Country',
            ],
            [
                'name' => 'Supplier Two',
                'contact_person' => 'Jane Smith',
                'email' => 'suppliertwo@example.com',
                'phone' => '987654321',
                'address' => '456 Supplier Ave, City, Country',
            ],
            [
                'name' => 'Supplier Three',
                'contact_person' => 'Alice Johnson',
                'email' => 'supplierthree@example.com',
                'phone' => '555123456',
                'address' => '789 Supplier Rd, City, Country',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }

    private function createProducts()
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
                for ($i = 1; $i <= 5; $i++) {
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

    private function createShoppingCartItems() {
        for ($i = 0; $i < 5; $i++) {
            $productVariantId = ProductVariant::orderBy('id', 'DESC')->first()->id;
    
            ShoppingCartItem::create([
                'shopping_cart_id' => 1,
                'product_variant_id' => rand(1, $productVariantId),
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

    private function createCoupons(){
        $coupons = [
            [
                'code' => 'DISCOUNT10',
                'type' => 'percentage',
                'value' => 10.00,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'usage_limit' => 100,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'FIXED50',
                'type' => 'fixed',
                'value' => 50.00,
                'start_date' => now(),
                'end_date' => now()->addDays(15),
                'usage_limit' => 50,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'WELCOME5',
                'type' => 'fixed',
                'value' => 5.00,
                'start_date' => now(),
                'end_date' => now()->addDays(60),
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            $coupon = Coupon::create($coupon);
            $coupon->users()->attach([1, 2, 3]);
        }
    }

    private function createCampaigns()
    {
        $campaigns = [
            [
                'name' => 'Summer Sale',
                'type' => 'fixed',
                'value' => 10.00,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'is_active' => true,
                'image_url' => 'https://i.ibb.co/c6Skbmc/5406758.jpg'
            ],
            [
                'name' => 'Black Friday',
                'type' => 'percentage',
                'value' => 30.00,
                'start_date' => now(),
                'end_date' => now()->addDays(10),
                'is_active' => true,
                'image_url' => 'https://i.ibb.co/xLxhvrM/18393162-5991789.jpg'
            ],
            [
                'name' => 'Winter Sale',
                'type' => 'percentage',
                'value' => 45.00,
                'start_date' => now(),
                'end_date' => now()->addDays(60),
                'is_active' => true,
                'image_url' => 'https://i.ibb.co/P1g1Ln5/preview-fashion-winter-sales-men-banner-template-free-design-1609939353.jpg'
            ],
        ];

        foreach ($campaigns as $campaignData) {
            $campaign = Campaign::create($campaignData);
        
            $products = Product::all();
            $productCount = $products->count();
            
            $assignmentPercentage = mt_rand(10, 50) / 100;
            $assignmentCount = round($productCount * $assignmentPercentage);
            
            $shuffledProducts = $products->shuffle()->take($assignmentCount);
            
            foreach ($shuffledProducts as $product) {
                $alreadyAssigned = \DB::table('campaign_product')->where('product_id', $product->id)->exists();
                
                if (!$alreadyAssigned) {
                    if ($campaign->type === 'fixed') {
                        if ($product->price >= $campaign->value * 1.5) {
                            $campaign->products()->attach($product->id);
                        }
                    } else {
                        $discountedPrice = $product->price * (1 - $campaign->value / 100);

                        $minPricePercentage = max(0.3, 1 - ($campaign->value / 100));
                        $minPrice = $product->price * $minPricePercentage;
                        
                        if ($discountedPrice >= $minPrice) {
                            $campaign->products()->attach($product->id);
                        }
                    }
                }
            }
        }
    }

    private function createActions()
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
            ['role_id' => 2, 'action_id' => 4],
            ['role_id' => 4, 'action_id' => 5],
        ];
        
        foreach($mappings as $map){
            \DB::table('action_role')->insert([
                'role_id' => $map['role_id'],
                'action_id' => $map['action_id'],
            ]);
        }
    }
}
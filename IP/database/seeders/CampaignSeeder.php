<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\Product;

class CampaignSeeder extends Seeder
{
    public function run()
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
}


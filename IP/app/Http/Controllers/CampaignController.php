<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::where('is_active', true)
                    ->orderBy('start_date', 'desc')
                    ->get();

        return view('campaigns.index', compact('campaigns'));
    }

    public function show(Campaign $campaign)
    {
        $products = $campaign->products()->paginate(12);
        return view('campaigns.show', compact('campaign', 'products'));
    }
}

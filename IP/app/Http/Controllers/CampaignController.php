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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'image_url' => 'required|string|max:255',
            'is_active' => 'required|boolean', 
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        

        Campaign::create($validatedData);

        return back()->with('success', 'Campaign added successfully.');
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'image_url' => 'required|string|max:255',
            'is_active' => 'required|boolean', 
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $campaign->update($validatedData);
        return back()->with('success', 'Campaign updated successfully.');
    }

    public function delete(Campaign $campaign)
    {
        $campaign->delete();
        return back()->with('success', 'Campaign deleted successfully.');
    }
}

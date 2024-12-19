<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\Stock;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
        ]);

        $variant = ProductVariant::with('stock')->findOrFail($request->variant_id);

        \DB::transaction(function () use ($request, $variant) {
            $variant->suppliers()->attach($request->supplier_id, [
                'quantity' => $request->quantity,
                'cost' => $request->cost,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $stock = $variant->stock;
            if ($stock) {
                $stock->quantity += $request->quantity;
                $stock->save();
            } else {
                Stock::create([
                    'product_variant_id' => $variant->id,
                    'quantity' => $request->quantity,
                ]);
            }
        });

        return back()->with('success', 'Supply added successfully.');
    }

    public function getSuggestedCost(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        $variant = ProductVariant::findOrFail($request->variant_id);

        $minCost = $variant->price * 0.1;
        $maxCost = $variant->price;

        $suggestedCost = round(mt_rand($minCost * 100, $maxCost * 100) / 100, 2);

        return response()->json([
            'suggested_cost' => $suggestedCost,
        ]);
    }

}

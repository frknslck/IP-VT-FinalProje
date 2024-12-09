<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function updateStock(Request $request, ProductVariant $productVariant)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $productVariant->stock = $request->stock;
        $productVariant->save();

        return redirect()->back()->with('success', 'Stock updated successfully.');
    }
}
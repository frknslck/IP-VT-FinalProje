<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use App\Models\Material;
use App\Models\Category;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function index()
    {   
        $categories = Category::all();
        $products = Product::with(['brand', 'categories'])
            ->where('is_active', true)
            ->paginate(12);
        return view('homepage', compact('products', 'categories'));
    }

    // public function show(Product $product)
    // {
    //     $product->load(['brand', 'categories', 'variants.color', 'variants.size', 'variants.material']);
    //     return view('products.show', compact('product'));
    // }

    public function show(Product $product)
    {
        $product->load(['variants', 'categories']);
        $relatedProducts = Product::whereHas('categories', function ($query) use ($product) {
            $query->whereIn('categories.id', $product->categories->pluck('id'));
        })->where('id', '!=', $product->id)->take(4)->get();
    
        $colors = Color::all();
        $sizes = Size::all();
        $materials = Material::all();
    
        return view('products.show', compact('product', 'relatedProducts', 'colors', 'sizes', 'materials'));
    }
    


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}

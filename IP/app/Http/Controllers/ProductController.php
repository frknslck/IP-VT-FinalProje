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
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $products = Product::with(['brand', 'categories'])
            ->where('is_active', true)
            ->paginate(12);
        return view('homepage', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['variants.color', 'variants.size', 'variants.material', 'categories']);

        $stock = $product->variants->sum(function($variant) {
            return $variant->stock ? $variant->stock->quantity : 0;
        });
        
        $relatedProducts = Product::whereHas('categories', function ($query) use ($product) {
            $query->whereIn('categories.id', $product->categories->pluck('id'));
        })->where('id', '!=', $product->id)->take(4)->get();

        $variantOptions = $product->variants->groupBy('color_id')->map(function ($colorVariants) {
            return $colorVariants->groupBy('size_id')->map(function ($sizeVariants) {
                return $sizeVariants->pluck('material_id');
            });
        });

        // dd($variantOptions);

        $colors = $product->variants->pluck('color')->unique()->sortBy('id');
        $sizes = $product->variants->pluck('size')->unique()->sortBy('id');
        $materials = $product->variants->pluck('material')->unique()->sortBy('id');

        $sizeNames = $sizes->pluck('name', 'id');
        $materialNames = $materials->pluck('name', 'id');

        return view('products.show', compact('product', 'stock', 'relatedProducts', 'colors', 'sizes', 'materials', 'variantOptions', 'sizeNames', 'materialNames'));
    }

    public function searchProductById(Request $request)
    {
        $id = $request->input('id');
        if ($id) {
            $product = Product::find($id);
            if ($product) {
                // flash()->success('');
                return redirect()->route('products.show', ['product' => $product]);
            }
        }
        // flash()->error('Product not found.');
        return redirect()->back()->with('error', 'Product not found');
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

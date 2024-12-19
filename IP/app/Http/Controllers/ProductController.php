<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\Size;
use App\Models\Material;
use App\Models\Notification;
use App\Models\Category;
use App\Models\Campaign;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function home()
    {
        $categories = Category::whereNull('parent_id')->get();
        $campaigns = Campaign::where('is_active', true)->get();
        $products = Product::where('best_seller', true)
        ->with(['campaigns' => function($query) {
            $query->where('is_active', true);
        }])
        ->paginate(12);

        return view('homepage', compact('categories', 'products', 'campaigns'));
    }

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Combine color, size, and material filters
        if ($request->filled('color') || $request->filled('size') || $request->filled('material')) {
            $query->whereHas('variants', function ($variantQuery) use ($request) {
                if ($request->filled('color')) {
                    $variantQuery->where('color_id', $request->color);
                }
                if ($request->filled('size')) {
                    $variantQuery->where('size_id', $request->size);
                }
                if ($request->filled('material')) {
                    $variantQuery->where('material_id', $request->material);
                }
            });
        }

        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereBetween('price', [$request->min_price, $request->max_price]);
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $query->with(['variants.color', 'variants.size', 'variants.material', 'categories', 'campaigns' => function($q) {
            $q->where('is_active', true);
        }]);

        $products = $query->paginate(12);

        $categories = Category::whereNotNull('parent_id')->get();
        $colors = Color::all();
        $sizes = Size::all();
        $materials = Material::all();

        $minPrice = Product::join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->min('product_variants.price');
        $maxPrice = Product::join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->max('product_variants.price');

        return view('shop.index', compact('products', 'categories', 'colors', 'sizes', 'materials', 'minPrice', 'maxPrice'));
    }

    public function getSizesForCategory($categoryId)
    {
        $sizes = Size::whereHas('productVariants.product.categories', function ($query) use ($categoryId) {
            $query->where('categories.id', $categoryId);
        })->get();

        return response()->json($sizes);
    }

    public function show(Product $product)
    {
        $product->load(['variants.color', 'variants.size', 'variants.material', 'categories', 'campaigns' => function($query) {
            $query->where('is_active', true);
        }]);

        $stock = $product->variants->sum(function($variant) {
            return $variant->stock ? $variant->stock->quantity : 0;
        });
        
        $relatedProducts = Product::with(['campaigns' => function($query) {
            $query->where('is_active', true);
        }])->whereHas('categories', function ($query) use ($product) {
            $query->whereIn('categories.id', $product->categories->pluck('id'));
        })->where('id', '!=', $product->id)->take(4)->get();

        $variantOptions = $product->variants->groupBy('color_id')->map(function ($colorVariants) {
            return $colorVariants->groupBy('size_id')->map(function ($sizeVariants) {
                return $sizeVariants->pluck('material_id');
            });
        });

        $colors = $product->variants->pluck('color')->unique()->sortBy('id');
        $sizes = $product->variants->pluck('size')->unique()->sortBy('id');
        $materials = $product->variants->pluck('material')->unique()->sortBy('id');

        $sizeNames = $sizes->pluck('name', 'id');
        $materialNames = $materials->pluck('name', 'id');

        $reviews = $product->reviews()->with('user')->paginate(5);

        $userReview = auth()->user() ? $product->reviews()->where('user_id', auth()->id())->first() : null;

        $userPurchasedProduct = false;
        if (auth()->check()) {
            $userPurchasedProduct = auth()->user()->orders()
                ->whereHas('details.productVariant', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })->exists();
        }

        return view('products.show', compact(
            'product', 'stock', 'relatedProducts', 'colors', 'sizes', 'materials', 
            'variantOptions', 'sizeNames', 'materialNames', 'reviews', 'userReview', 
            'userPurchasedProduct'
        ));
    }

    public function searchProductById(Request $request)
    {
        $id = $request->input('id');
        if ($id) {
            $product = Product::find($id);
            if ($product) {
                return redirect()->route('products.show', ['product' => $product]);
            }
        }
        return redirect()->back()->with('error', 'Product not found');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'image_url' => 'required|url|max:255',
            'price' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:brands,id',
            'is_active' => 'required|boolean', 
            'best_seller' => 'required|boolean',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);
    
        $product = Product::create($validatedData);
        $product->categories()->attach($validatedData['categories']);
    
        return back()->with('success', 'Product added successfully');
    }
    
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'image_url' => 'required|url|max:255',
            'price' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:brands,id',
            'is_active' => 'required|boolean', 
            'best_seller' => 'required|boolean',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);
    
        $product->update($validatedData);
        $product->categories()->sync($validatedData['categories']);
    
        return back()->with('success', 'Product updated successfully');
    }
    
    
    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted successfully');
    }

    public function fetchProductsForSupplyManagement($id)
    {
        $product = Product::with('variants.stock')->findOrFail($id);
        return response()->json([
            'variants' => $product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'sku' => $variant->sku,
                    'stock' => $variant->stock->quantity ?? 0,
                ];
            })
        ]);
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $subcategories = $category->children;
        
        if ($category->parent_id === null) {
            $products = $category->getAllProducts()->paginate(12);
        } else {
            $products = $category->products()->paginate(12);
        }

        return view('categories.show', compact('category', 'subcategories', 'products'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(Category $category)
    {
        //
    }

    public function update(Request $request, Category $category)
    {
        //
    }
    public function destroy(Category $category)
    {
        //
    }
}

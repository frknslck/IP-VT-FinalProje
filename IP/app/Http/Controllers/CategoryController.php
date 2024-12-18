<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

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
        if($category->parent_id === null){
            $products = $category->getAllProducts()->with(['campaigns' => function($query) {
                $query->where('is_active', true);
            }])->paginate(12);
        }else{
            $products = $category->products()->with(['campaigns' => function($query) {
                $query->where('is_active', true);
            }])->paginate(12);
        }

        return view('categories.show', compact('category', 'subcategories', 'products'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        Category::create($validatedData);

        return back()->with('success', 'Kategori başarıyla eklendi.');
    }

    public function update(Request $request, Category $category)
    {
        // dd($request);
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        if ($validatedData['parent_id'] && ($validatedData['parent_id'] == $category->id || $this->isChildCategory($category->id, $validatedData['parent_id']))) {
            return back()->with('error', 'Bir kategori kendisini veya alt kategorisini üst kategori olarak seçemez.');
        }

        $category->update($validatedData);
        return back()->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function delete(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Bu kategori ürünler içeriyor. Kategoriyi silmeden önce lütfen ilişkili ürünleri kaldırın veya başka bir kategoriye taşıyın.');
        }else if($category->parent_id == null && $category->children()->exists()){
            return back()->with('error', 'Bu kategori alt kategoriler içeriyor. Kategoriyi silmeden önce lütfen ilişkili kategorileri kaldırın veya başka bir ana kategoriye taşıyın.');
        }

        $category->delete();
        return back()->with('success', 'Kategori başarıyla silindi.');
    }

    private function isChildCategory($parentId, $categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) return false;
        if ($category->parent_id == $parentId) return true;
        return $this->isChildCategory($parentId, $category->parent_id);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ActionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $products = $category->getAllProducts()->with(['campaigns' => function ($query) {
                $query->where('is_active', true);
            }])->paginate(12);
        } else {
            $products = $category->products()->with(['campaigns' => function ($query) {
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

        $category = Category::create($validatedData);

        ActionLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'target' => 'category',
            'status' => 'success',
            'ip_address' => request()->ip(),
            'details' => 'Category created. ID: ' . $category->id,
        ]);

        return back()->with('success', 'Category added successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        if ($validatedData['parent_id'] && ($validatedData['parent_id'] == $category->id || $this->isChildCategory($category->id, $validatedData['parent_id']))) {
            ActionLog::create([
                'user_id' => Auth::id(),
                'action' => 'update',
                'target' => 'category',
                'status' => 'failed',
                'ip_address' => request()->ip(),
                'details' => 'Failed to update category. Invalid parent ID. ID: ' . $category->id,
            ]);

            return back()->with('error', 'A category cannot select itself or its subcategory as its parent category.');
        }

        $category->update($validatedData);

        ActionLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'target' => 'category',
            'status' => 'success',
            'ip_address' => request()->ip(),
            'details' => 'Category updated. ID: ' . $category->id,
        ]);

        return back()->with('success', 'Category updated successfully.');
    }

    public function delete(Category $category)
    {
        if ($category->products()->exists()) {
            ActionLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete',
                'target' => 'category',
                'status' => 'failed',
                'ip_address' => request()->ip(),
                'details' => 'Failed to delete category. Has associated products. ID: ' . $category->id,
            ]);

            return back()->with('error', 'This category contains products. Please remove or move the associated products to another category before deleting it.');
        } else if ($category->parent_id == null && $category->children()->exists()) {
            ActionLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete',
                'target' => 'category',
                'status' => 'failed',
                'ip_address' => request()->ip(),
                'details' => 'Failed to delete category. Has child categories. ID: ' . $category->id,
            ]);

            return back()->with('error', 'This category contains subcategories. Please remove or move the associated subcategories to another parent category before deleting it.');
        }

        $category->delete();

        ActionLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'target' => 'category',
            'status' => 'success',
            'ip_address' => request()->ip(),
            'details' => 'Category deleted. ID: ' . $category->id,
        ]);

        return back()->with('success', 'Category deleted successfully.');
    }

    private function isChildCategory($parentId, $categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) return false;
        if ($category->parent_id == $parentId) return true;
        return $this->isChildCategory($parentId, $category->parent_id);
    }
}

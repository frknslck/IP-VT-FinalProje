<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'parent_id', 'image_url'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function getAllProducts()
    {
        return Product::whereHas('categories', function ($query) {
            $query->where('categories.id', $this->id)
                  ->orWhere('categories.parent_id', $this->id);
        });
    }

    public function getFormattedName()
    {
        if (!$this->parent) {
            return $this->name;
        }
    
        $parentName = $this->parent->name;
        $childName = $this->name;
    
        if (strpos($childName, $parentName) === 0) {
            $childName = trim(substr($childName, strlen($parentName)));
        }
    
        return $parentName . ' - ' . $childName;
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = auth()->user()->wishlist()->with(['product.campaigns' => function($query) {
            $query->where('is_active', true)->orderBy('created_at', 'DESC');
        }])->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::id();
        $productId = $request->input('product_id');

        $wishlistItem = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            return back()->with('success', 'Product removed from wishlist successfully.');
        } else {
            Wishlist::create(['user_id' => $userId, 'product_id' => $productId]);
            return back()->with('success', 'Product added to wishlist successfully.');
        }
    }

    public function removeFromWishlist(Wishlist $wishlistItem)
    {
        $wishlistItem->delete();
        return redirect()->route('wishlist.index')->with('success', 'Product removed from wishlist successfully.');
    }
}
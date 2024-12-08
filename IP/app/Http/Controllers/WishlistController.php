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
        $wishlistItems = Wishlist::where('user_id', Auth::user()->id)->get();
        return view('wishlist.index', compact('wishlistItems'));
    }

    public function addToWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlistItem = Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return back()->with('success', 'Product added to wishlist successfully.');
    }

    public function removeFromWishlist(Wishlist $wishlistItem)
    {
        $wishlistItem->delete();
        return redirect()->route('wishlist.index')->with('success', 'Product removed from wishlist successfully.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use App\Models\ShoppingCartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShoppingCartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        return view('shopping-cart.index', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getOrCreateCart();

        $item = $cart->items()->where('product_variant_id', $request->product_variant_id)->first();

        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            $cart->items()->create([
                'user_id' => Auth::id(),
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    }

    public function removeFromCart(ShoppingCartItem $item)
    {
        $item->delete();
        return redirect()->route('cart.index')->with('success', 'Product removed from cart successfully.');
    }

    public function updateQuantity(Request $request, ShoppingCartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item->quantity = $request->quantity;
        $item->save();

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    private function getOrCreateCart()
    {
        if (Auth::check()) {
            return ShoppingCart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            $sessionId = session()->get('cart_session_id');
            if (!$sessionId) {
                $sessionId = Str::uuid();
                session()->put('cart_session_id', $sessionId);
            }
            return ShoppingCart::firstOrCreate(['session_id' => $sessionId]);
        }
    }
}
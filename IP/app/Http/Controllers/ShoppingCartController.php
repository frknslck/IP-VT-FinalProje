<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use App\Models\ShoppingCartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Session;

class ShoppingCartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        return view('shopping-cart.index', compact('cart'));
    }

    public function addToCart(Request $request)
{
    $product_id = $request->product_id;
    $color_id = $request->color_id;
    $size_id = $request->size_id;
    $material_id = $request->material_id;
    $quantity = 1;

    $product_variant = ProductVariant::where('product_id', $product_id)
        ->where('color_id', $color_id)
        ->where('size_id', $size_id)
        ->where('material_id', $material_id)
        ->first();

    if (!$product_variant) {
        return redirect()->back()->with('error', 'Product variant not found.');
    }

    $cart = ShoppingCart::firstOrCreate(['user_id' => Auth::id()]);

    $cartItem = ShoppingCartItem::where('shopping_cart_id', $cart->id)
        ->where('product_variant_id', $product_variant->id)
        ->first();

    if ($cartItem) {
        $cartItem->quantity += $quantity;
        $cartItem->save();
    } else {
        ShoppingCartItem::create([
            'shopping_cart_id' => $cart->id,
            'product_variant_id' => $product_variant->id,
            'quantity' => $quantity,
            'price' => $product_variant->price,
        ]);
    }

    return back()->with('success', 'Product added to cart successfully.');
}

    public function removeFromCart(ShoppingCartItem $item)
    {
        $item->delete();
        return back()->with('success', 'Product removed from cart successfully.');
    }

    public function updateQuantity(Request $request, ShoppingCartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item->quantity = $request->quantity;
        $item->save();

        return back()->with('success', 'Cart updated successfully.');
    }

    private function getOrCreateCart()
    {
        if (Auth::check()) {
            return ShoppingCart::firstOrCreate(['user_id' => Auth::id()], ['session_id' => Session::getId()]);
        }
    }
}
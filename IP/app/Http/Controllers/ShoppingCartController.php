<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use App\Models\ShoppingCartItem;
use App\Models\ProductVariant;
use App\Models\Coupon;
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

    public function updateQuantities(Request $request)
    {
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
        ]);

        foreach ($request->quantities as $itemId => $quantity) {
            $item = ShoppingCartItem::find($itemId);
            if ($item && $item->shopping_cart_id === $this->getOrCreateCart()->id) {
                $item->quantity = $quantity;
                $item->save();
            }
        }

        return back()->with('success', 'Cart quantities updated successfully.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $request->code)->first();


        if (!$coupon || !$coupon->is_active) {
            return back()->with('error', 'Invalid coupon code.');
        }

        $cart = $this->getOrCreateCart();
        $cart->coupon_id = $coupon->id;
        $cart->save();

        return back()->with('success', 'Coupon applied successfully.');
    }

    private function getOrCreateCart()
    {
        if (Auth::check()) {
            return ShoppingCart::firstOrCreate(
                ['user_id' => Auth::id()]);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $cart = auth()->user()->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        \DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'payment_method_id' => $request->payment_method_id,
                'address_id' => $request->address_id,
                'order_number' => 'ORDER-'.auth()->id().'-'.uniqid().'-'.time(),
                'total_amount' => $cart->total,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            foreach ($cart->items as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->total_price,
                ]);
            }

            $cart->items()->delete();
            $cart->delete();

            \DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function index()
    {
        $orders = auth()->user()->orders()->latest()->get();
        return view('orders.index', compact('orders'));
    }


    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        
        $deliveryAddress = auth()->user()->addresses()->find($order->address_id);
        $paymentMethod = PaymentMethod::find($order->payment_method_id);

        return view('orders.show', compact('order', 'deliveryAddress', 'paymentMethod'));
    }
}

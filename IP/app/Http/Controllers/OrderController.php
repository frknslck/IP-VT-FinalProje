<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Coupon;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;
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
            $coupon = null;
            if ($cart->coupon_id) {
                $coupon = Coupon::find($cart->coupon_id);
                if ($coupon) {
                    if ($coupon->used_count >= $coupon->usage_limit) {
                        return back()->with('error', 'This coupon has reached its maximum usage limit.');
                    }else if($coupon->is_active == 0 || $coupon->is_active == false){
                        return back()->with('error', 'This coupon is not active anymore.');
                    }
                    $coupon->used_count++;
                    $coupon->save();
                }
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'payment_method_id' => $request->payment_method_id,
                'address_id' => $request->address_id,
                'used_coupon' => $coupon 
                ? 
                    $coupon->type == 'fixed' 
                    ? "{$coupon->code} -> {$coupon->value}$" 
                    : "{$coupon->code} -> {$coupon->value}%"
                : null,
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

                $productVariant = ProductVariant::find($item->product_variant_id);
                if ($productVariant) {
                    $stock = $productVariant->stock;
                    if ($stock) {
                        $stock->quantity -= $item->quantity;
                        $stock->save();
                    }
                }
            }

            $cart->items()->delete();
            $cart->delete();

            \DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            if ($coupon) {
                $coupon->used_count--;
                $coupon->save();
            }
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

    public function updateOrderStatus($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status == 'pending') {
            $order->status = 'processing';
            $order->save();
            return back()->with('success', 'Order is now being processed.');
        }

        return back()->with('error', 'Invalid order status.');
    }

    public function finalizeOrderStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
            'message' => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);

        $status = $validated['status'];
        $message = $validated['message'];

        
        $order->status = $status;
        $order->save();
        
        if ($status == 'cancelled') {
            foreach ($order->details as $detail) {
                $productVariant = ProductVariant::find($detail->product_variant_id);
                if ($productVariant) {
                    $stock = $productVariant->stock;
                    if ($stock) {
                        $stock->quantity += $detail->quantity;
                        $stock->save();
                    }
                }
            }
        }

        $notificationMessage = $status == 'completed'
            ? 'Congratulations on your purchase! Your order ' . $order->order_number . ' has been completed. '. $message
            : 'We are sorry to inform you that your order ' . $order->order_number . ' has been cancelled. '. $message;


        $notification = Notification::create([
            'from' => 'System',
            'title' => 'Order Status of -> '.$order->order_number,
            'message' => $notificationMessage,
        ]);
        $notification->users()->attach(auth()->user()->id);

        return back()->with('success', 'Order status updated and notification sent.');
    }

}

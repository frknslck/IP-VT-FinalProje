@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Order Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Order Information</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Order Number:</span>
                    <span>{{ $order->order_number }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Status:</span>
                    <span class="text-capitalize">{{ $order->status }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total Amount:</span>
                    <span>${{ number_format($order->total_amount, 2) }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Used Coupon:</span>
                    <span>{{ $usedCoupon ? (($usedCoupon->type == 'fixed' 
                                ? $usedCoupon->code.' -> $'.$usedCoupon->value
                                : $usedCoupon->code.' -> %'.$usedCoupon->value).' Discount')
                            : 'No coupon was used for this order.'}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Payment Method</span>
                    <span>{{ $paymentMethod->name }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Order Date:</span>
                    <span>{{ $order->created_at->format('d-m-Y H:i') }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Delivery Address:</span>
                    <span>{{ $deliveryAddress->country.', '.$deliveryAddress->city.', '.$deliveryAddress->neighborhood.', '.$deliveryAddress->building_no.', '.$deliveryAddress->apartment_no }}</span>
                </li>
                @if($order->notes)
                    <li class="list-group-item">
                        <span>Notes:</span>
                        <p class="mt-2">{{ $order->notes }}</p>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Ordered Items</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->details as $detail)
                    <tr>
                        <td>{{ $detail->productVariant->product->name }}</td>
                        <td>
                            {{ $detail->productVariant->color->name ?? 'N/A' }} -
                            {{ $detail->productVariant->size->name ?? 'N/A' }} -
                            {{ $detail->productVariant->material->name ?? 'N/A' }}
                        </td>
                        <td>{{ $detail->quantity }}</td>
                        <td>${{ number_format($detail->price, 2) }}</td>
                        <td>${{ number_format($detail->quantity * $detail->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
    </div>
</div>
@endsection

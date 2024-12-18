@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mb-5">Your Shopping Cart</h1>

    @if($cart->items->count() > 0)
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Products</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart->items as $item)
                            <tr>
                                <td>
                                    {{ $item->productVariant->product->name }}<br>
                                    <small>
                                        Variant: {{ $item->productVariant->color->name }} - 
                                        {{ $item->productVariant->size->name }} - 
                                        {{ $item->productVariant->material->name }}
                                    </small>
                                </td>
                                <td>
                                    @if($item->productVariant->getEffectivePrice() < $item->productVariant->price)
                                        <span class="text-danger">${{ number_format($item->productVariant->getEffectivePrice(), 2) }}</span>
                                        <small class="text-muted text-decoration-line-through">${{ number_format($item->productVariant->price, 2) }}</small>
                                    @else
                                        ${{ number_format($item->productVariant->getEffectivePrice(), 2) }}
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('cart.update-quantities') }}" method="POST" class="d-flex align-items-center justify-content-start gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="action" value="decrease" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">-</button>
                                        <div class="d-flex align-items-center justify-content-center border rounded bg-light" style="width: 50px; height: 32px; font-weight: bold;">
                                            {{ $item->quantity }}
                                        </div>
                                        <button type="submit" name="action" value="increase" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">+</button>
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    </form>
                                </td>
                                <td>${{ number_format($item->total_price, 2) }}</td>
                                <td>
                                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Summary</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span>${{ number_format($cart->subtotal, 2) }}</span>
                        </li>
                        @if($cart->discount > 0)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Discount:</span>
                            <span class="text-danger">-${{ number_format($cart->discount, 2) }}</span>
                        </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong>${{ number_format($cart->total, 2) }}</strong>
                        </li>
                        @if($cart->coupon_id)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Applied Coupon:</span>
                            <div>
                                <span class="badge bg-success">{{ $cart->coupon->code }}</span>
                                <form action="{{ route('cart.remove-coupon') }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger ms-2">Remove</button>
                                </form>
                            </div>
                        </li>
                        @endif
                    </ul>

                    <form action="{{ route('cart.apply-coupon') }}" method="POST" class="mt-3">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="code" class="form-control" placeholder="Enter coupon code">
                            <button type="submit" class="btn btn-secondary">Apply Coupon</button>
                        </div>
                    </form>

                    <form action="{{ route('checkout.store') }}" method="POST" class="mt-3">
                        @csrf
                        @if($cart->coupon_id)
                            <input type="hidden" name="coupon_id" value="{{ $cart->coupon_id }}">
                        @endif
                        <select name="payment_method_id" id="payment_method_id" class="form-select mt-3" required>
                            <option value="">Select a payment method</option>
                            @foreach($payment_methods as $method)
                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                            @endforeach
                        </select>

                        <select name="address_id" id="address_id" class="form-select mt-3" required>
                            <option value="">Select your delivery address</option>
                            @foreach($addresses as $address)
                                <option value="{{ $address->id }}">{{ $address->country.', '.$address->city.', '.$address->neighborhood }}</option>
                            @endforeach
                        </select>
                        
                        <textarea name="notes" class="form-control mt-3" placeholder="Order notes (optional)"></textarea>
                        <button type="submit" class="btn btn-primary w-100 mt-3">Proceed to Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <p>Your cart is empty.</p>
    @endif
</div>
@endsection
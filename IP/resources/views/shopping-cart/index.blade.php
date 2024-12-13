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
                    <form action="{{ route('cart.update-quantities') }}" method="POST">
                        @csrf
                        @method('PATCH')
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
                                        Variant: {{ $item->productVariant->color->name }} - 
                                        {{ $item->productVariant->size->name }} - 
                                        {{ $item->productVariant->material->name }}
                                    </td>
                                    <td>
                                        @if($item->productVariant->getEffectivePrice() < $item->productVariant->price)
                                            <span class="text-muted text-decoration-line-through">${{ number_format($item->productVariant->price, 2) }}</span>
                                            <span class="text-danger">${{ number_format($item->productVariant->getEffectivePrice(), 2) }}</span>
                                        @else
                                            ${{ number_format($item->productVariant->getEffectivePrice(), 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        <input type="number" name="quantities[{{ $item->id }}]" value="{{ $item->quantity }}" min="1" class="form-control">
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
                        <button type="submit" class="btn btn-primary">Update Quantities</button>
                    </form>
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
                    </ul>

                    <form action="{{ route('cart.apply-coupon') }}" method="POST" class="mt-3">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="code" class="form-control" placeholder="Enter coupon code">
                            <button type="submit" class="btn btn-secondary">Apply Coupon</button>
                        </div>
                    </form>

                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf
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
@extends('layout')

@section('content')
<div class="container mt-4">
    <h1>Your Shopping Cart</h1>

    @if($cart->items->count() > 0)
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
                        <td>{{ $item->productVariant->product->name }} - {{ $item->productVariant->name }}</td>
                        <td>${{ number_format($item->productVariant->price, 2) }}</td>
                        <td>
                            <form action="{{ route('cart.update-quantity', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control d-inline-block w-auto">
                                <button type="submit" class="btn btn-sm btn-secondary">Update</button>
                            </form>
                        </td>
                        <td>${{ number_format($item->productVariant->price * $item->quantity, 2) }}</td>
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
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td>${{ number_format($cart->items->sum(function($item) { return $item->productVariant->price * $item->quantity; }), 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
    @else
        <p>Your cart is empty.</p>
    @endif
</div>
@endsection
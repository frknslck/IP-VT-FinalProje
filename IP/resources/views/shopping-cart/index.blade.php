@extends('layout')

@section('content')
<div class="container mt-4 mb-5">
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
                        <td>
                            {{ $item->productVariant->product->name }}
                            <br>
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
                            <form action="{{ route('cart.update-quantity', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control d-inline-block w-auto">
                                <button type="submit" class="btn btn-sm btn-secondary">Update</button>
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
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                    <td>${{ number_format($cart->subtotal, 2) }}</td>
                    <td></td>
                </tr>
                @if($cart->discount > 0)
                    <tr>
                        <td colspan="3" class="text-right"><strong>Discount:</strong></td>
                        <td>-${{ number_format($cart->discount, 2) }}</td>
                        <td></td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td>${{ number_format($cart->total, 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <form action="{{ route('cart.apply-coupon') }}" method="POST" class="mb-3">
            @csrf
            <div class="input-group">
                <input type="text" name="code" class="form-control" placeholder="Enter coupon code">
                <button type="submit" class="btn btn-secondary">Apply Coupon</button>
            </div>
        </form>
        <a href="#" class="btn btn-primary">Proceed to Checkout</a>
    @else
        <p>Your cart is empty.</p>
    @endif
</div>
@endsection
@extends('layout')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Invoice {{ $invoice->invoice_number }}</h1>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Order:</strong> {{ $invoice->order->order_number }}</p>
            <p><strong>Total Amount:</strong> {{ $invoice->total_amount }}</p>
            <p><strong>Status:</strong> {{ $invoice->status }}</p>
            <p><strong>Payment Date:</strong> {{ $invoice->created_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
    
    <h2 class="mt-4">Order Details</h2>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @if($order && $order->details)
                @foreach($order->details as $detail)
                    <tr>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ $detail->price }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" class="text-center">No items found for this order.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection

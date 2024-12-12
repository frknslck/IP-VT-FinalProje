@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mb-5">My Orders</h1>

    @if($orders->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Total Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                    <td class="text-capitalize">{{ $order->status }}</td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-primary btn-sm">View Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-center">You have no orders yet.</p>
    @endif
</div>
@endsection

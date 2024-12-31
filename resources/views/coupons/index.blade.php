@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mb-5">Your Coupons</h1>
    @if ($coupons->isEmpty())
        <p>You don't have any coupons assigned to you.</p>
    @else
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Assigned On</th>
                    <th>Valid From</th>
                    <th>Valid Until</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ ucfirst($coupon->type) }}</td>
                        <td>
                            @if ($coupon->type === 'percentage')
                                {{ $coupon->value }}%
                            @else
                                ${{ number_format($coupon->value, 2) }}
                            @endif
                        </td>
                        <td>{{ $coupon->pivot->created_at->format('d M Y, H:i') }}</td>
                        <td>{{ $coupon->start_date->format('d M Y') }}</td>
                        <td>{{ $coupon->end_date->format('d M Y') }}</td>
                        <td>
                            @if ($coupon->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

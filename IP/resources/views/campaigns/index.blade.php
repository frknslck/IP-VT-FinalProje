@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mb-5">Active Campaigns</h1>

    @foreach($campaigns as $index => $campaign)
        <div class="row align-items-center mb-5 campaign-row {{ $index % 2 == 0 ? '' : 'flex-row-reverse' }}">
            <div class="col-md-6">
                <img src="{{ $campaign->image_url ?? 'https://via.placeholder.com/600x400?text=' . urlencode($campaign->name) }}" 
                     alt="{{ $campaign->name }}" 
                     class="img-fluid rounded shadow-lg">
            </div>
            <div class="col-md-6">
                <div class="p-4">
                    <h2 class="mb-3">{{ $campaign->name }}</h2>
                    <p class="lead mb-4">{{ $campaign->description }}</p>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="h4">
                            @if($campaign->type === 'fixed')
                                ${{ number_format($campaign->value, 2) }} Discount
                            @else
                                %{{ number_format($campaign->value, 0) }} Discount
                            @endif
                        </span>
                        <span class="text-muted">
                            {{ $campaign->start_date->format('d.m.Y') }} - {{ $campaign->end_date->format('d.m.Y') }}
                        </span>
                    </div>
                    <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-primary btn-lg">Start Shopping</a>
                </div>
            </div>
        </div>
        @if(!$loop->last)
            <hr class="my-5">
        @endif
    @endforeach
</div>
@endsection

@push('styles')
<style>
    .campaign-row {
        transition: all 0.3s ease-in-out;
    }
    .campaign-row:hover {
        transform: translateY(-5px);
    }
    .campaign-row img {
        transition: all 0.3s ease-in-out;
    }
    .campaign-row:hover img {
        transform: scale(1.05);
    }
</style>
@endpush
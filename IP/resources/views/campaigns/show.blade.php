@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mb-5">{{ $campaign->name }}</h1>
    
    <div class="row mb-4">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <!-- <p class="lead">{{ $campaign->description }}</p> -->
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="h4">
                            @if($campaign->type === 'fixed')
                                ${{ number_format($campaign->value, 2) }} İndirim
                            @else
                                %{{ number_format($campaign->value, 0) }} İndirim
                            @endif
                        </span>
                        <span class="text-muted">
                            {{ $campaign->start_date->format('d.m.Y') }} - {{ $campaign->end_date->format('d.m.Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @foreach($products as $product)
        <div class="col">
            @include('partials.product-card', ['campaign' => $campaign])
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-img-top {
        height: 200px;
        object-fit: cover;
    }
</style>
@endpush
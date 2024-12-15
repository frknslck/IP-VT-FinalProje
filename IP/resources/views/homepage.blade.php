@extends('layout')

@section('content')
<div class="container mt-5">
    <div id="carouselExampleIndicators" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($campaigns as $index => $campaign)
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" class="{{ $loop->first ? 'active' : '' }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($campaigns as $index => $campaign)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ $campaign->image_url ?? 'https://via.placeholder.com/1200x420' }}" class="d-block w-100" alt="{{ $campaign->name }}">
                    <div class="carousel-caption d-none d-md-block">
                        <h2>{{ $campaign->name }}</h2>
                        <p>
                            @if($campaign->type === 'fixed')
                                Save ${{ number_format($campaign->value, 2) }} on selected items
                            @else
                                Get {{ number_format($campaign->value, 0) }}% off on selected items
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>
    <hr>
    <div class="row text-center mb-5">
        <h1 class="mb-4">Categories</h1>
        @foreach($categories as $category)
            @if ($category->parent_id == null)
                <div class="col-md-4">
                    <a href="{{ route('categories.show', $category->id) }}" class="text-decoration-none text-dark">
                        <div class="card">
                            <img src="{{ $category->image_url ?? 'https://via.placeholder.com/300x200?text=' . urlencode($category->name) }}" class="card-img-top" alt="{{ $category->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $category->name }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @endforeach
    </div>
    <hr>
    <h1 class="text-center mb-4">Featured Products</h1>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @foreach($products as $product)
            <div class="col">
                @include('partials.product-card')
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
    .card {
        min-height: 400px;
    }

    .card-body {
        display: flex;
        flex-direction: column;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .card-body p {
        flex-grow: 1;
    }

    .badge.bg-danger {
        font-size: 0.8rem;
    }

    .carousel-caption {
        background-color: rgba(0, 0, 0, 0.7);
        padding: 20px;
        border-radius: 10px;
    }

    .carousel-caption h2 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .carousel-caption p {
        font-size: 1.2rem;
    }

    @media (max-width: 768px) {
        .card {
            min-height: 500px;
        }
    }
</style>
@endpush
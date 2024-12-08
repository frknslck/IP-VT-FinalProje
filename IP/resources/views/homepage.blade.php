@extends('layout')

@section('content')
<div class="container mt-5">

    <div id="carouselExampleIndicators" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/1200x420?text=Sale+Up+to+50%25+Off" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/1200x420?text=New+Collection+Available" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/1200x420?text=Shop+Now+Best+Deals" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>

    <div class="row text-center mb-5">
        <h1 class="mb-4">Kategoriler</h1>
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

    <h2 class="mb-4">Featured Products</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @foreach($products as $product)
        <div class="col">
            <div class="card h-100 position-relative">
            
                <form action="{{ route('wishlist.add') }}" method="POST" class="d-inline position-absolute top-0 start-0 m-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-heart"></i>
                    </button>
                </form>
        
                @if($product->best_seller)
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">Best Seller</span>
                @endif
                <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x400' }}" class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="text-muted flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold">${{ number_format($product->price, 2) }}</span>
                        <span class="badge bg-secondary"> 
                            {{ $product->variants->count() > 0 ? $product->variants->count().' Variant(s)' : 'No Variants' }}
                        </span>
                    </div>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-primary w-100">View Details</a>
                </div>
            </div>
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

    @media (max-width: 768px) {
        .card {
            min-height: 500px;
        }
    }
</style>
@endpush

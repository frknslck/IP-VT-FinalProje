@extends('layout')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-5">{{ $category->name }}</h1>

    @if($subcategories->isNotEmpty())
        <h2 class="mb-4 text-primary">Subcategories</h2>
        <div class="row">
            @foreach($subcategories as $subcategory)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="{{ $subcategory->name }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $subcategory->name }}</h5>
                            <p class="card-text text-muted mb-4">Explore products under {{ $subcategory->name }} category.</p>
                            <a href="{{ route('categories.show', $subcategory) }}" class="btn btn-outline-primary btn-lg mt-auto">
                                View {{ $subcategory->name }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <h2 class="mt-5 text-primary">Products</h2>
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
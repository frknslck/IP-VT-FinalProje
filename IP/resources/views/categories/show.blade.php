@extends('layout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{ $category->name }}</h1>
    <div class="d-flex justify-content-end mb-3">
        <form method="GET" action="">
            <select name="sort" onchange="this.form.submit()" class="form-select w-auto">
                <option value="">Sort By</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
            </select>
        </form>
    </div>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @foreach($products as $product)
            <div class="col">
                <div class="card h-100">
                    <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x200' }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <span class="text-muted">${{ number_format($product->price, 2) }}</span>
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

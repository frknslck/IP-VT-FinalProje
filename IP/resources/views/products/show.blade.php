@extends('layout')

@section('content')
<div class="container mt-3">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/400x400' }}" alt="{{ $product->name }}" class="img-fluid">
        </div>
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p>{{ $product->description }}</p>
            <p class="h3 mb-4">${{ number_format($product->price, 2) }}</p>
            <p>Total Stock: {{ $stock }}</p>
            
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="mb-3">
                    <label for="color" class="form-label">Color</label>
                    <select name="color_id" id="color" class="form-select" required>
                        <option value="">Select a color</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="size" class="form-label">Size</label>
                    <select name="size_id" id="size" class="form-select" required disabled>
                        <option value="">Select a size</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="material" class="form-label">Material</label>
                    <select name="material_id" id="material" class="form-select" required disabled>
                        <option value="">Select a material</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" id="addToCartBtn" disabled>Add to Cart</button>
            </form>

            <form action="{{ route('wishlist.toggle') }}" method="POST" class="col-md-6 mt-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" 
                    class="btn {{ Auth::user() && Auth::user()->wishlist->contains('product_id', $product->id) ? 'btn-danger' : 'btn-outline-danger' }}">
                    {{ Auth::user() && Auth::user()->wishlist->contains('product_id', $product->id) ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                </button>
            </form>
        </div>
    </div>
    
    <h2 class="mt-5 mb-4">Related Products</h2>
    <div class="row">
        @foreach($relatedProducts as $relatedProduct)
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="{{ $relatedProduct->image_url ?? 'https://via.placeholder.com/200x200' }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                        <p class="card-text">${{ number_format($relatedProduct->price, 2) }}</p>
                        <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-secondary">View Product</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
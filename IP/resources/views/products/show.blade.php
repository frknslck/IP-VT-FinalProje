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
            
            <form action="#" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="color" class="form-label">Color</label>
                    <select name="color" id="color" class="form-select" required>
                        <option value="">Select a color</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="size" class="form-label">Size</label>
                    <select name="size" id="size" class="form-select" required>
                        <option value="">Select a size</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="material" class="form-label">Material</label>
                    <select name="material" id="material" class="form-select" required>
                        <option value="">Select a material</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}">{{ $material->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Add to Cart</button>
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
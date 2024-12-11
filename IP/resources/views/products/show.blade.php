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
                
                <!-- <div class="mb-3">
                    <label for="size" class="form-label">Size</label>
                    <select name="size_id" id="size" class="form-select" required>
                        <option value="">Select a size</option>
                        @foreach ($sizes as $size)
                            <option value="{{$size->id}}">{{$size->name}}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="material" class="form-label">Material</label>
                    <select name="material_id" id="material" class="form-select" required>
                        <option value="">Select a material</option>
                        @foreach ($materials as $material)
                            <option value="{{$material->id}}">{{$material->name}}</option>
                        @endforeach
                    </select>
                </div> -->

                <div class="mb-3">
                    <label for="size" class="form-label">Size</label>
                    <select id="size" name="size_id" class="form-select" disabled required>
                        <option value="">Select a size</option>
                    </select>
                </div>
        
                <div class="mb-3">
                    <label for="material" class="form-label">Material</label>
                    <select id="material" name="material_id" class="form-select" disabled required>
                        <option value="">Select a material</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary" id="addToCartBtn">Add to Cart</button>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorSelect = document.getElementById('color');
        const sizeSelect = document.getElementById('size');
        const materialSelect = document.getElementById('material');
        const variantOptions = @json($variantOptions);
        const sizeNames = @json($sizeNames);
        const materialNames = @json($materialNames);

        colorSelect.addEventListener('change', function() {
            const selectedColor = this.value;
            sizeSelect.innerHTML = '<option value="">Select a size</option>';
            materialSelect.innerHTML = '<option value="">Select a material</option>';
            sizeSelect.disabled = !selectedColor;
            materialSelect.disabled = true;

            if (selectedColor && variantOptions[selectedColor]) {
                Object.keys(variantOptions[selectedColor]).forEach(sizeId => {
                    const option = document.createElement('option');
                    option.value = sizeId;
                    option.textContent = sizeNames[sizeId] || `Size ${sizeId}`;
                    sizeSelect.appendChild(option);
                });
            }
        });

        sizeSelect.addEventListener('change', function() {
            const selectedColor = colorSelect.value;
            const selectedSize = this.value;
            materialSelect.innerHTML = '<option value="">Select a material</option>';
            materialSelect.disabled = !selectedSize;

            if (selectedColor && selectedSize && variantOptions[selectedColor][selectedSize]) {
                variantOptions[selectedColor][selectedSize].forEach(materialId => {
                    const option = document.createElement('option');
                    option.value = materialId;
                    option.textContent = materialNames[materialId] || `Material ${materialId}`;
                    materialSelect.appendChild(option);
                });
            }
        });
    });
</script>
@endpush
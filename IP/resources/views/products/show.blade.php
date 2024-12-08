@extends('layout')

@section('content')
<div class="container mt-5">
    <!-- Ürün Detayları -->
    <div class="row">
        <div class="col-md-6">
            <!-- Ürün Görseli -->
            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/500x500?text=No+Image' }}" 
                 class="img-fluid rounded" 
                 alt="{{ $product->name }}">
        </div>
        <div class="col-md-6">
            <!-- Ürün Bilgileri -->
            <h1 class="display-5">{{ $product->name }}</h1>
            <p class="text-muted">{{ $product->sku }}</p>
            <p class="lead">{{ $product->description }}</p>
            <h4 class="text-primary fw-bold">${{ number_format($product->price, 2) }}</h4>

            <!-- Varyant Seçenekleri -->
            <div class="mt-4">
                <h5>Select Variants:</h5>
                
                <!-- Renk Seçimi -->
                @if($product->variants->pluck('color_id')->unique()->isNotEmpty())
                    <h6>Colors:</h6>
                    <div class="d-flex flex-wrap mb-3">
                        @foreach($colors as $color)
                            @php
                                $available = $product->variants->where('color_id', $color->id)->isNotEmpty();
                            @endphp
                            <button class="btn btn-sm me-2 mb-2 {{ $available ? 'btn-outline-primary' : 'btn-outline-danger' }}" 
                                    {{ $available ? '' : 'disabled' }}>
                                {{ $color->name }}
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Boyut Seçimi -->
                @if($product->variants->pluck('size_id')->unique()->isNotEmpty())
                    <h6>Sizes:</h6>
                    <div class="d-flex flex-wrap mb-3">
                        @foreach($sizes as $size)
                            @php
                                $available = $product->variants->where('size_id', $size->id)->isNotEmpty();
                            @endphp
                            <button class="btn btn-sm me-2 mb-2 {{ $available ? 'btn-outline-primary' : 'btn-outline-danger' }}" 
                                    {{ $available ? '' : 'disabled' }}>
                                {{ $size->name }}
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Malzeme Seçimi -->
                @if($product->variants->pluck('material_id')->unique()->isNotEmpty())
                    <h6>Materials:</h6>
                    <div class="d-flex flex-wrap mb-3">
                        @foreach($materials as $material)
                            @php
                                $available = $product->variants->where('material_id', $material->id)->isNotEmpty();
                            @endphp
                            <button class="btn btn-sm me-2 mb-2 {{ $available ? 'btn-outline-primary' : 'btn-outline-danger' }}" 
                                    {{ $available ? '' : 'disabled' }}>
                                {{ $material->name }}
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Sepete Ekleme -->
            <div class="mt-4">
                <form action="#" method="POST">
                    @csrf
                    <h6>Quantity:</h6>
                    <div class="d-flex align-items-center mb-3">
                        <input type="number" name="quantity" value="1" min="1" class="form-control w-25 me-3">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>

    <!-- İlgili Ürünler -->
    @if($relatedProducts->count())
        <div class="mt-5">
            <h3 class="mb-4">Related Products</h3>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                @foreach($relatedProducts as $related)
                    <div class="col">
                        <div class="card h-100">
                            <img src="{{ $related->image_url ?? 'https://via.placeholder.com/300x200' }}" 
                                 class="card-img-top" 
                                 alt="{{ $related->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $related->name }}</h5>
                                <p class="text-muted">${{ number_format($related->price, 2) }}</p>
                                <a href="{{ route('products.show', $related) }}" class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

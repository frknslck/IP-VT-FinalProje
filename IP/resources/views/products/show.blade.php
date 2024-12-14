@extends('layout')

@section('content')
<div class="container my-5">
    <div class="row mb-5">
        <div class="col-md-6">
            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/400x400' }}" alt="{{ $product->name }}" class="img-fluid rounded shadow-sm">
        </div>
        <div class="col-md-6">
            <h1 class="mb-3">{{ $product->name }}</h1>
            <div class="d-flex align-items-center mb-3">
                <div class="me-3">
                    <span class="h4 me-2 mb-0">{{ number_format($product->average_rating, 1) }}</span>
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= round($product->average_rating) ? ' text-warning' : ' text-muted' }}"></i>
                    @endfor
                </div>
                <span class="text-muted">({{ $product->rating_count }} reviews)</span>
            </div>
            <p class="lead mb-4">{{ $product->description }}</p>
            <div class="mb-4">
                @if($product->discounted_price < $product->price)
                    <span class="h3 text-danger me-2">${{ number_format($product->discounted_price, 2) }}</span>
                    <span class="h5 text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</span>
                @else
                    <span class="h3">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>
            <p class="mb-4">Total Stock: <span class="fw-bold">{{ $stock }}</span></p>
            
            <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="color" class="form-label">Color</label>
                        <select name="color_id" id="color" class="form-select" required>
                            <option value="">Select a color</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="size" class="form-label">Size</label>
                        <select id="size" name="size_id" class="form-select" disabled required>
                            <option value="">Select a size</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="material" class="form-label">Material</label>
                        <select id="material" name="material_id" class="form-select" disabled required>
                            <option value="">Select a material</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex mt-4">
                    <button type="submit" class="btn btn-primary flex-grow-1 me-2" id="addToCartBtn">Add to Cart</button>
                    <form action="{{ route('wishlist.toggle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-heart"></i>
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h2 class="mb-4">Customer Reviews</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title h4">Overall Rating</h3>
                    <div class="d-flex align-items-center mb-3">
                        <span class="display-4 me-2">{{ number_format($product->average_rating, 1) }}</span>
                        <div>
                            <div class="mb-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= round($product->average_rating) ? ' text-warning' : ' text-muted' }}"></i>
                                @endfor
                            </div>
                            <span class="text-muted">Based on {{ $product->rating_count }} reviews</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        @for ($i = 5; $i >= 1; $i--)
                            @php
                                $percentage = $product->rating_count > 0 ? ($product->reviews->where('rating', $i)->count() / $product->rating_count) * 100 : 0;
                            @endphp
                            <div class="d-flex align-items-center mb-1">
                                <span class="me-2">{{ $i }} star</span>
                                <div class="progress flex-grow-1" style="height: 8px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ms-2">{{ number_format($percentage, 0) }}%</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    @if(auth()->check() && $userPurchasedProduct)
                        <h3 class="card-title h4 mb-4">{{ $userReview ? 'Update Your Review' : 'Write a Review' }}</h3>
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="mb-3">
                                <label class="form-label">Your Rating</label>
                                <div class="rating">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ $userReview && $userReview->rating == $i ? 'checked' : '' }} required>
                                        <label for="star{{ $i }}" title="{{ $i }} stars">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Your Review</label>
                                <textarea name="comment" id="comment" rows="3" class="form-control" required>{{ $userReview ? $userReview->comment : '' }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ $userReview ? 'Update Review' : 'Submit Review' }}</button>
                        </form>
                    @elseif(auth()->check())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>You need to purchase this product to leave a review.
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Please <a href="{{ route('login') }}">log in</a> to leave a review.
                        </div>
                    @endif
                </div>
            </div>
            <div id="reviewsList">
                @foreach($reviews as $review)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">{{ $review->user->name }}</h5>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? ' text-warning' : ' text-muted' }}"></i>
                                @endfor
                            </div>
                            <p class="card-text">{{ $review->comment }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="mb-4">Related Products</h2>
        </div>
        @foreach($relatedProducts as $relatedProduct)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="{{ $relatedProduct->image_url ?? 'https://via.placeholder.com/200x200' }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                        <div class="mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= round($relatedProduct->average_rating) ? ' text-warning' : ' text-muted' }}"></i>
                            @endfor
                            <small class="text-muted">({{ $relatedProduct->rating_count }})</small>
                        </div>
                        <p class="card-text">
                            @if($relatedProduct->discounted_price < $relatedProduct->price)
                                <span class="text-danger">${{ number_format($relatedProduct->discounted_price, 2) }}</span>
                                <small class="text-muted text-decoration-line-through">${{ number_format($relatedProduct->price, 2) }}</small>
                            @else
                                ${{ number_format($relatedProduct->price, 2) }}
                            @endif
                        </p>
                        <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-outline-primary mt-auto">View Product</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .rating input {
        display: none;
    }

    .rating label {
        font-size: 1.5rem;
        color: #ccc;
        cursor: pointer;
    }

    .rating input:checked ~ label,
    .rating label:hover,
    .rating label:hover ~ label {
        color: #ffc107;
    }
</style>

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


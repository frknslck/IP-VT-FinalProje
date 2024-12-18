@extends('layout')

@section('content')
<div class="container ">
    <h1 class="text-center mb-5">My Reviews</h1>

    @if($ordersWithDetails->count() > 0)
    @foreach ($ordersWithDetails as $order)
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">Order Number: <strong>{{ $order->order_number }}</strong></h5>
                <p class="text-muted mb-0">Status: {{ ucfirst($order->status) }}</p>
            </div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#orderDetails-{{ $order->id }}">
                View Details
            </button>
        </div>
        <div class="collapse" id="orderDetails-{{ $order->id }}">
            <div class="card-body">
                <ul class="list-group">
                    @foreach ($order->details as $detail)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">{{ $detail->productVariant->product->name ?? 'Product Name N/A' }}</h6>
                            <small class="text-muted">Quantity: {{ $detail->quantity }}</small>
                        </div>
                        <div class="d-flex gap-2 ms-auto">
                            <button 
                                class="btn btn-outline-primary btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#reviewModal" 
                                data-product-id="{{ $detail->productVariant->product->id }}" 
                                data-product-name="{{ $detail->productVariant->product->name }}"
                                data-review-rating="{{ $detail->productVariant->product->reviews->first()->rating ?? '' }}"
                                data-review-comment="{{ $detail->productVariant->product->reviews->first()->comment ?? '' }}">
                                {{ $detail->productVariant->product->reviews->first() ? 'Edit Review' : 'Leave Review' }}
                            </button>
                            <a class="btn btn-outline-primary btn-sm" href="/product/{{ $detail->productVariant->product->id }}">Ürüne Git</a>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <p class="text-center">You have no orders to review yet.</p>
    @endif
</div>


<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Leave a Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="productId">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product</label>
                        <input type="text" id="productName" class="form-control" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                                <label for="star{{ $i }}" class="star">&#9733;</label>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea name="comment" id="comment" class="form-control" rows="3" placeholder="Share your thoughts..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
    document.addEventListener('DOMContentLoaded', function () {
    const reviewModal = document.getElementById('reviewModal');
    if (reviewModal) {
        reviewModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const reviewRating = button.getAttribute('data-review-rating');
            const reviewComment = button.getAttribute('data-review-comment');

            document.getElementById('productId').value = productId;
            document.getElementById('productName').value = productName;

            if (reviewRating) {
                document.querySelector(`input[name="rating"][value="${reviewRating}"]`).checked = true;
            }

            if (reviewComment) {
                document.getElementById('comment').value = reviewComment;
            }

            document.getElementById('reviewModalLabel').textContent = reviewRating ? 'Edit Review' : 'Leave a Review';
        });
    }
});
</script>
@endsection

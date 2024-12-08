@extends('layout')

@section('content')
<div class="container mt-4">
    <h1>Your Wishlist</h1>

    @if($wishlistItems->count() > 0)
        <div class="row">
            @foreach($wishlistItems as $item)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/300x200' }}" class="card-img-top" alt="{{ $item->product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->product->name }}</h5>
                            <p class="card-text">${{ number_format($item->product->price, 2) }}</p>
                            <a href="{{ route('products.show', $item->product) }}" class="btn btn-primary">View Product</a>
                            <form action="{{ route('wishlist.remove', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Your wishlist is empty.</p>
    @endif
</div>
@endsection
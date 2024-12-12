@extends('layout')

@section('content')
<div class="container mt-4 mb-5">
    <h1>Your Wishlist</h1>

    @if($wishlistItems->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @foreach($wishlistItems as $item)
                <div class="col">
                    @include('partials.product-card', ['product' => $item->product])
                </div>
            @endforeach
        </div>
    @else
        <p>Your wishlist is empty.</p>
    @endif
</div>
@endsection
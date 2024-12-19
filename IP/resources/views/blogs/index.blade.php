@extends('layout')

@section('content')
<div class="container">
    <div class="mb-5 text-center">
        <h1>Blog Posts</h1>
        @if($user && ($user->hasRole('Admin') || $user->hasRole('Blogger')))
            <a href="{{ route('blogs.create') }}" class="btn btn-primary mt-3">Create New Blog</a>
        @endif
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($blogs as $blog)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    @if($blog->image_url)
                        <img src="{{ $blog->image_url }}" class="card-img-top" alt="{{ $blog->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/350x200" class="card-img-top" alt="{{ $blog->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $blog->title }}</h5>
                        <p class="card-text">{{ Str::limit($blog->content, 100) }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">By {{ $blog->user->name }}</small>
                            <a href="{{ route('blogs.show', $blog->id) }}" class="btn btn-primary btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection


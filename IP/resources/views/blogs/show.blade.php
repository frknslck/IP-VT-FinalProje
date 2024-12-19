@extends('layout')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                @if($blog->image_url)
                    <img src="{{ $blog->image_url }}" class="card-img-top" alt="{{ $blog->title }}" style="height: 300px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h1 class="card-title">{{ $blog->title }}</h1>
                    <p class="text-muted">
                        <small>By {{ $blog->user->name }} | {{ $blog->created_at->format('F d, Y') }}</small>
                    </p>
                    <div class="card-text mt-4">
                        {!! nl2br(e($blog->content)) !!}
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-{{ $blog->status == 'published' ? 'success' : ($blog->status == 'draft' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($blog->status) }}
                        </span>
                        <a href="{{ route('blogs.index') }}" class="btn btn-outline-primary">Back to Blogs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


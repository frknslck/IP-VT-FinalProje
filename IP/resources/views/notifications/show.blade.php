@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Message Details</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>From:</strong>
                            <span>{{ $notification->from }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Title:</strong>
                            <span>{{ $notification->title }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Message:</strong>
                            <p class="mt-2 text-muted">{{ $notification->message }}</p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Received At:</strong>
                            <span>{{ $notification->created_at->format('d-m-Y H:i') }}</span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center bg-light">
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-arrow-left-circle"></i> Back to Notifications
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mb-5">My Notifications</h1>

    @if($notifications->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>From</th>
                    <th>Title</th>
                    <th>Read Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifications as $notification)
                    <tr class="{{ $notification->pivot->is_read ? 'table-secondary' : '' }}">
                        <td>{{ $notification->pivot->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $notification->from }}</td>
                        <td>{{ $notification->title }}</td>
                        <td>{{ $notification->pivot->is_read ? 'Read' : 'Unread' }}</td>
                        <td>
                            <a href="{{ route('notifications.show', $notification->id) }}" class="btn btn-primary btn-sm">
                               View Details
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-center">You have no notifications yet.</p>
    @endif
</div>
@endsection

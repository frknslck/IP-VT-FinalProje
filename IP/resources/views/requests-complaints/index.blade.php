@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mb-5">My Requests and Complaints</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('requests-complaints.create') }}" class="btn btn-success mb-4">New Request/Complaint</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>Subject</th>
                <th>Category</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $complaint)
                <tr>
                    <td>{{ $complaint->id }}</td>
                    <td>{{ $complaint->RorC }}</td>
                    <td>{{ $complaint->subject }}</td>
                    <td>{{ $complaint->category }}</td>
                    <td>{{ $complaint->status }}</td>
                    <td>{{ $complaint->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

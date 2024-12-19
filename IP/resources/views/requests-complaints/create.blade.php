@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mb-5">Create Request/Complaint</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('requests-complaints.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="RorC" class="form-label">Type</label>
            <select class="form-select" id="RorC" name="RorC" required>
                <option value="Request">Request</option>
                <option value="Complaint">Complaint</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" class="form-control" id="subject" name="subject" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" id="category" name="category" required>
                <option value="Category">Category</option>
                <option value="Brand">Brand</option>
                <option value="Product">Product</option>
                <option value="User">User</option>
                <option value="Review">Review</option>
                <option value="Order">Order</option>
                <option value="Campaign">Campaign</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection

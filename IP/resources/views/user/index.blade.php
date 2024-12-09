@extends('layout')

@section('content')

@if (Auth::user())
    <div class="text-center mt-3">
        <p> You're logged in </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Logout</button>
        </form>
    </div>
@else
    <div class="text-center mt-3">
        <p> You're not logged in </p>
        <a class="btn btn-primary" href="/register">Register</a>
        <a class="btn btn-primary" href="/login">Login</a>
    </div>
@endif

@endsection
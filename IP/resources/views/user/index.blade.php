@extends('layout')

@section('content')
<div class="container mt-3 mb-5">
    <h2 class="mb-4">Profile Information</h2>

    <form action="{{ route('user.update') }}" method="POST" class="mb-5">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="tel_no" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="tel_no" name="tel_no" value="{{ $user->tel_no }}" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>

    <h2 class="mb-4">Your Roles</h2>
    <div class="mb-4">
        @foreach($userRoles as $role)
            <span class="badge bg-primary text-white me-1 mb-1" style="font-size: 0.9rem; padding: 0.5em 1em; border-radius: 0.25rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                {{ $role->name }}
            </span>
        @endforeach
    </div>

    @if($user->isAdmin())
    <div class="mb-5">
        <h4>Manage Roles</h4>
        <form action="{{ route('user.addRole') }}" method="POST" class="mb-3">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <select name="role_id" class="form-select">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-success">Add Role</button>
                </div>
            </div>
        </form>
        @foreach($userRoles as $role)
            <form action="{{ route('user.removeRole') }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <input type="hidden" name="role_id" value="{{ $role->id }}">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <button type="submit" class="btn btn-danger btn-sm">Remove {{ $role->name }}</button>
            </form>
        @endforeach
    </div>
    @endif

    <h2 class="mb-4">Your Addresses</h2>
    <div class="row">
        @foreach($addresses as $address)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $address->country }}, {{ $address->city }}</h5>
                        <p class="card-text">
                            Country: {{ $address->country }} <br>
                            City: {{ $address->city }} <br>
                            Neighborhood: {{ $address->neighborhood }} <br>
                            Building No: {{ $address->building_no }}, Apartment No: {{ $address->apartment_no }}
                        </p>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editAddressModal-{{ $address->id }}">Edit</button>
                        <form action="{{ route('user.deleteAddress', $address->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editAddressModal-{{ $address->id }}" tabindex="-1" aria-labelledby="editAddressLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('user.updateAddress', $address->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editAddressLabel">Edit Address</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control" name="country" value="{{ $address->country }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" name="city" value="{{ $address->city }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="neighborhood" class="form-label">Neighborhood</label>
                                    <input type="text" class="form-control" name="neighborhood" value="{{ $address->neighborhood }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="building_no" class="form-label">Building No</label>
                                    <input type="text" class="form-control" name="building_no" value="{{ $address->building_no }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="apartment_no" class="form-label">Apartment No</label>
                                    <input type="text" class="form-control" name="apartment_no" value="{{ $address->apartment_no }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAddressModal">+ Add New Address</button>

    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('user.addAddress') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAddressLabel">Add New Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" name="country" required>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" name="city" required>
                        </div>
                        <div class="mb-3">
                            <label for="neighborhood" class="form-label">Neighborhood</label>
                            <input type="text" class="form-control" name="neighborhood" required>
                        </div>
                        <div class="mb-3">
                            <label for="building_no" class="form-label">Building No</label>
                            <input type="text" class="form-control" name="building_no" required>
                        </div>
                        <div class="mb-3">
                            <label for="apartment_no" class="form-label">Apartment No</label>
                            <input type="text" class="form-control" name="apartment_no">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Add Address</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
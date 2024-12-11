<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        return view('user.index', compact('user', 'addresses'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'tel_no' => 'required|string|max:20',
        ]);

        $user->update($validatedData);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function addAddress(Request $request)
{
    $validatedData = $request->validate([
        'country' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'neighborhood' => 'required|string|max:255',
        'building_no' => 'required|string|max:255',
        'apartment_no' => 'nullable|string|max:255',
    ]);

    Auth::user()->addresses()->create($validatedData);

    return back()->with('success', 'Address added successfully.');
}

public function updateAddress(Request $request, Address $address)
{
    $validatedData = $request->validate([
        'country' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'neighborhood' => 'required|string|max:255',
        'building_no' => 'required|string|max:255',
        'apartment_no' => 'nullable|string|max:255',
    ]);

    $address->update($validatedData);

    return back()->with('success', 'Address updated successfully.');
}

public function deleteAddress(Address $address)
{
    $address->delete();

    return back()->with('success', 'Address deleted successfully.');
}

}
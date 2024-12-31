<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\User;
use App\Models\Role;
use App\Models\ActionLog;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        $roles = Role::all();
        $userRoles = $user->roles;
        return view('user.index', compact('user', 'addresses', 'roles', 'userRoles'));
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

        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'target' => 'user profile',
            'status' => 'success',
            'ip_address' => request()->ip(),
            'details' => 'User profile updated: ID ' . $user->id,
        ]);

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

        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'target' => 'address',
            'status' => 'success',
            'ip_address' => request()->ip(),
            'details' => 'Address added for user ID ' . auth()->id(),
        ]);

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

        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'target' => 'address',
            'status' => 'success',
            'ip_address' => request()->ip(),
            'details' => 'Address updated for user ID ' . auth()->id(),
        ]);

        return back()->with('success', 'Address updated successfully.');
    }

    public function deleteAddress(Address $address)
    {
        $address->delete();

        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'target' => 'address',
            'status' => 'success',
            'ip_address' => request()->ip(),
            'details' => 'Address deleted for user ID ' . auth()->id(),
        ]);

        return back()->with('success', 'Address deleted successfully.');
    }

    public function addRole(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return back()->with('error', 'You do not have permission to manage roles.');
        }

        $validatedData = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($validatedData['role_id']);

        if (!$user->hasRole($role->name)) {
            $user->roles()->attach($role);

            ActionLog::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'target' => 'role',
                'status' => 'success',
                'ip_address' => request()->ip(),
                'details' => 'Role ' . $role->name . ' added to user ID ' . $user->id,
            ]);

            return back()->with('success', 'Role added successfully.');
        }

        return back()->with('error', 'You already have this role.');
    }

    public function removeRole(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return back()->with('error', 'You do not have permission to manage roles.');
        }

        $validatedData = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $role = Role::findOrFail($validatedData['role_id']);
        $targetUser = User::findOrFail($validatedData['user_id']);

        if ($role->name === 'Admin' && $user->id === $targetUser->id) {
            return back()->with('error', 'You cannot remove your own admin role.');
        }

        if ($targetUser->roles->contains($role)) {
            $targetUser->roles()->detach($role);

            ActionLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete',
                'target' => 'role',
                'status' => 'success',
                'ip_address' => request()->ip(),
                'details' => 'Role ' . $role->name . ' removed from user ID ' . $targetUser->id,
            ]);

            return back()->with('success', 'Role removed successfully.');
        }

        return back()->with('error', 'This user does not have this role.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\ActionLog;

class CouponController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $coupons = $user->coupons()->withPivot('created_at')->get();
        return view('coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255',
            'type' => 'required|string|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'required|nullable|numeric|',
            'is_active' => 'required|boolean',
        ]);

        $coupon = Coupon::create($validatedData);

        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'target' => 'coupon',
            'status' => 'success',
            'ip_address' => $request->ip(),
            'details' => 'Coupon created. ID: ' . $coupon->id,
        ]);

        return back()->with('success', 'Coupon added successfully.');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255',
            'type' => 'required|string|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'required|nullable|numeric|',
            'is_active' => 'required|boolean',
        ]);

        $coupon->update($validatedData);

        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'target' => 'coupon',
            'status' => 'success',
            'ip_address' => $request->ip(),
            'details' => 'Coupon updated. ID: ' . $coupon->id,
        ]);

        return back()->with('success', 'Coupon updated successfully.');
    }

    public function delete(Coupon $coupon)
    {
        $couponId = $coupon->id;
        $coupon->delete();

        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'target' => 'coupon',
            'status' => 'success',
            'ip_address' => request()->ip(),
            'details' => 'Coupon deleted. ID: ' . $couponId,
        ]);

        return back()->with('success', 'Coupon deleted successfully.');
    }
}

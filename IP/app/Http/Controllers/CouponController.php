<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;

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
        
        Coupon::create($validatedData);

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
        return back()->with('success', 'Coupon updated successfully.');
    }

    public function delete(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted successfully.');
    }
}

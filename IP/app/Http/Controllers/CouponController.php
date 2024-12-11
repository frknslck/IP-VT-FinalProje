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
}

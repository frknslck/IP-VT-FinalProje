<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $ordersWithDetails = auth()->user()->orders()
            ->with(['details.productVariant.product.reviews' => function($query) {
                $query->where('user_id', auth()->id());
            }])
            ->get();

        // dd($ordersWithDetails->first()->quantity);

        return view('reviews.index', compact('ordersWithDetails'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
    
        $review = ProductReview::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment ?? '',
            ]
        );

        return redirect()->back()->with('success', 'Your review has been ' . ($review->wasRecentlyCreated ? 'submitted' : 'updated') . ' successfully!');
    }

}
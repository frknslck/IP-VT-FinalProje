<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\ActionLog;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $ordersWithDetails = auth()->user()->orders()
            ->with(['details.productVariant.product.reviews' => function($query) {
                $query->where('user_id', auth()->id());
            }])->orderBy('created_at', 'desc')
            ->get();

        return view('reviews.index', compact('ordersWithDetails'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = ProductReview::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $validatedData['product_id'],
            ],
            [
                'rating' => $validatedData['rating'],
                'comment' => $validatedData['comment'] ?? '',
            ]
        );

        ActionLog::create([
            'user_id' => auth()->id(),
            'action' => $review->wasRecentlyCreated ? 'create' : 'update',
            'target' => 'product_review',
            'status' => 'success',
            'ip_address' => request()->ip(),
            'details' => 'Review ' . ($review->wasRecentlyCreated ? 'submitted' : 'updated') . ' for product ID: ' . $review->product_id,
        ]);

        return redirect()->back()->with('success', 'Your review has been ' . ($review->wasRecentlyCreated ? 'submitted' : 'updated') . ' successfully!');
    }
}

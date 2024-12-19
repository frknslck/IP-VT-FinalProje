<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RequestComplaintController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Homepage

Route::get('/', [ProductController::class, 'home'])->name('homepage');

// Product

Route::get('/product/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'searchProductById'])->name('products.search');

// Category

Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Product Variant - Stock

Route::patch('/product-variants/{productVariant}/update-stock', [ProductVariantController::class, 'updateStock'])->name('product-variants.update-stock');

// Shop

Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/shop/sizes-for-category/{categoryId}', [ProductController::class, 'getSizesForCategory']);

// Shopping Cart

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [ShoppingCartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [ShoppingCartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/update-quantities', [ShoppingCartController::class, 'updateQuantities'])->name('cart.update-quantities');
    Route::delete('/cart/remove/{item}', [ShoppingCartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/apply-coupon', [ShoppingCartController::class, 'applyCoupon'])->name('cart.apply-coupon');
    Route::delete('/cart/remove-coupon', [ShoppingCartController::class, 'removeCoupon'])->name('cart.remove-coupon');
});

// Checkout

Route::middleware(['auth'])->group(function () {
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Reviews

Route::middleware(['auth'])->group(function () {
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// Wishlist

Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/remove/{wishlistItem}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
});

// Coupons

Route::middleware('auth')->group(function () {
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
});

// Campaigns

Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');

// User

Route::middleware(['auth'])->group(function () {
    Route::get('/user', [UserController::class, 'show'])->name('user.index');
    Route::post('/user', [UserController::class, 'update'])->name('user.update');
    Route::post('/user/role', [UserController::class, 'addRole'])->name('user.addRole');
    Route::delete('/user/role', [UserController::class, 'removeRole'])->name('user.removeRole');
    Route::post('/user/address', [UserController::class, 'addAddress'])->name('user.addAddress');
    Route::put('/user/address/{address}', [UserController::class, 'updateAddress'])->name('user.updateAddress');
    Route::delete('/user/address/{address}', [UserController::class, 'deleteAddress'])->name('user.deleteAddress');
});

// Notifications

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
});

// Request & Complaints

Route::middleware(['auth'])->group(function () {
    Route::get('/requests-complaints', [RequestComplaintController::class, 'index'])->name('requests-complaints.index');
    Route::get('/requests-complaints/create', [RequestComplaintController::class, 'create'])->name('requests-complaints.create');
    Route::post('/requests-complaints/create/store', [RequestComplaintController::class, 'store'])->name('requests-complaints.store');
});

// Actions

Route::middleware(['auth'])->group(function () {
    Route::get('/actions', [ActionController::class, 'index'])->name('action-panel.index');

    // Category Management
    Route::post('/actions/categories', [CategoryController::class, 'store'])->name('action-panel.store-category');
    Route::put('/actions/categories/{category}', [CategoryController::class, 'update'])->name('action-panel.update-category');
    Route::delete('/actions/categories/{category}', [CategoryController::class, 'delete'])->name('action-panel.delete-category');

    // Campaign Management
    Route::post('/actions/campaigns', [CampaignController::class, 'store'])->name('action-panel.store-campaign');
    Route::put('/actions/campaigns/{campaign}', [CampaignController::class, 'update'])->name('action-panel.update-campaign');
    Route::delete('/actions/campaigns/{campaign}', [CampaignController::class, 'delete'])->name('action-panel.delete-campaign');
    
    // Coupon Management
    Route::post('/actions/coupon', [CouponController::class, 'store'])->name('action-panel.store-coupon');
    Route::put('/actions/coupon/{coupon}', [CouponController::class, 'update'])->name('action-panel.update-coupon');
    Route::delete('/actions/coupon/{coupon}', [CouponController::class, 'delete'])->name('action-panel.delete-coupon');

    // Product Management
    Route::post('/actions/product', [ProductController::class, 'store'])->name('action-panel.store-product');
    Route::put('/actions/product/{product}', [ProductController::class, 'update'])->name('action-panel.update-product');
    Route::delete('/actions/product/{product}', [ProductController::class, 'delete'])->name('action-panel.delete-product');

    // Order Management

    Route::post('/actions/orders/{id}/status-action', [OrderController::class, 'finalizeOrderStatus'])->name('action-panel.finalize-order');
Route::post('/actions/orders/{id}/status', [OrderController::class, 'updateOrderStatus'])->name('action-panel.update-order');



    // Notification Service

    Route::post('/actions/notifications', [NotificationController::class, 'send'])->name('action-panel.send-notification');

    // Request & Complaints

    Route::get('/actions/review-rc/{id}', [RequestComplaintController::class, 'review'])->name('action-panel.review-requests-complaints');
    Route::get('/actions/resolve-rc/{id}', [RequestComplaintController::class, 'resolve'])->name('action-panel.resolve-requests-complaints');

});

// User Dashboard

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';

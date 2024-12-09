<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Classic Routes

Route::get('/', [ProductController::class, 'index'])->name('homepage');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Product Variant - Stock

Route::patch('/product-variants/{productVariant}/update-stock', [ProductVariantController::class, 'updateStock'])->name('product-variants.update-stock');

// Shopping Cart

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [ShoppingCartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [ShoppingCartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{item}', [ShoppingCartController::class, 'removeFromCart'])->name('cart.remove');
    Route::patch('/cart/update-quantity/{item}', [ShoppingCartController::class, 'updateQuantity'])->name('cart.update-quantity');
});

// Wishlist

Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/remove/{wishlistItem}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
});

// User Dashboard

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// User

Route::get('/user', function (){return view('user.index');})->name('user.index');

// Profile

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

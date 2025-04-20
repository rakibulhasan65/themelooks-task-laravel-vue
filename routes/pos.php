<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Api\PosApiController;
use Illuminate\Support\Facades\Route;

// Web Routes
Route::group(['middleware' => ['web']], function () {
    // Product Routes
    Route::resource('products', ProductController::class);
    
    // Variation Routes
    Route::post('products/{product}/variations', [VariationController::class, 'store'])->name('variations.store');
    Route::put('variations/{variation}', [VariationController::class, 'update'])->name('variations.update');
    Route::delete('variations/{variation}', [VariationController::class, 'destroy'])->name('variations.destroy');
    
    // Order Routes
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    
    // POS Route
    Route::get('pos', function () {
        return view('pos_views.pos.pos');
    })->name('pos');
});

// API Routes for POS
Route::prefix('api/pos')->group(function () {
    Route::get('products', [PosApiController::class, 'getProducts']);
    Route::get('products/{product}', [PosApiController::class, 'getProductDetails']);
    // place order
    Route::post('orders', [OrderController::class, 'store'])->name('api.orders.store');
});
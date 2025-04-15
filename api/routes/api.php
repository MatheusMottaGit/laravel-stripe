<?php

use App\Http\Controllers\CheckoutSessionController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'listAllProducts']);
Route::post('/checkout', [CheckoutSessionController::class, 'createCheckoutSession']);
Route::get('/checkout/session/{id}/verify', [CheckoutSessionController::class, 'verifySession']);
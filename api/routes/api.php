<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutSessionController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/products', [ProductController::class, 'listAllProducts']);
  Route::post('/checkout', [CheckoutSessionController::class, 'createCheckoutSession']);
  Route::get('/checkout/session/{sessionId}/verify', [CheckoutSessionController::class, 'verifySession']);
});

Route::post('/payment/webhook', [PaymentWebhookController::class, 'handle']);
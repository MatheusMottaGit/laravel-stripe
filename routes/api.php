<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/intent/create', [PaymentController::class, "createPaymentIntent"]);
Route::post('/intent/:id/confirm', [PaymentController::class, "confirmPaymentIntent"]);

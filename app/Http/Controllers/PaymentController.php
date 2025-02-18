<?php

namespace App\Http\Controllers;

use Http;
use Illuminate\Http\Request;
// use Stripe\PaymentIntent;
// use Stripe\Stripe;
use Validator;

class PaymentController extends Controller
{
    public function handlePaymentIntent(Request $request) {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'payment_method_types' => 'required|array',
            'payment_method_types.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => "Check your data format. Something's wrong..."], 400);
        }

        // process before payment
        // Authorization: Basic base64_encode(username:password)
        // Authorization: Basic sk_test_yourStripeSecretKey:
        $paymentIntentResponse = Http::withBasicAuth(env('STRIPE_SECRET'), "")->post("https://api.stripe.com/v1/payment_intents", [ 
            'amount' => $request->amount,
            'currency' => $request->currency,
            'payment_method_types' => $request->payment_method_types
        ]); 

        if ($paymentIntentResponse->successful()) {
            $paymentIntent = $paymentIntentResponse->json();

            return response()->json($paymentIntent['client_secret'], 200);
        }
    }
}

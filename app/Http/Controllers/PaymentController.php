<?php

namespace App\Http\Controllers;

use Http;
use Illuminate\Http\Request;
use Validator;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request) {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'currency' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => "Check your data. Something's wrong..."], 400);
        }

        // process before payment
        // Authorization: Basic base64_encode(username:password)
        // Authorization: Basic sk_test_yourStripeSecretKey:
        $paymentIntentResponse = Http::withBasicAuth(env('STRIPE_SECRET'), "")->post("https://api.stripe.com/v1/payment_intents", [ 
            'amount' => $request->amount,
            'currency' => $request->currency,
        ]); 

        if ($paymentIntentResponse->successful()) {
            $paymentIntent = $paymentIntentResponse->json();

            return response()->json($paymentIntent['client_secret'], 200);
        }

        return response()->json(['message' => "Something went wrong."], 400);
    }

    public function confirmPaymentIntent(Request $request) {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => "Check your data. Something's wrong..."], 400);
        }

        $paymentIntentId = $request->query('id');

        $confirmationResponse = Http::withQueryParameters([
            'id' => $paymentIntentId
        ])->post('https://api.stripe.com/v1/payment_intents/' . $paymentIntentId . '/confirm', [
            'payment_method' => $request->payment_method
        ]);

        if($confirmationResponse->successful()) {
            $confirmation = $confirmationResponse->json();
            return response()->json($confirmation['status'], 200);
        }

        return response()->json(['message' => "Something went wrong."], 400);
    }
}

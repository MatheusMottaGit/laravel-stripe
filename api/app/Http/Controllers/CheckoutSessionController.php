<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutSessionController extends Controller
{
    protected $stripe;

    public function __construct() {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function createCheckoutSession(Request $request)
    {
        $request->validate([
            'price_id' => 'required|string',
        ]);
        
        $checkout_session = $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $request->price_id,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost:5173/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:5173/cancel',
        ]);

        return response()->json([
            'url' => $checkout_session['url'],
            'session_id' => $checkout_session['id'],
        ]);
    }

    public function verifySession(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        try {
            $session = $this->stripe->checkout->sessions->retrieve($request->session_id);
            return response()->json($session);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Session not found'], 404);
        }
        
    }
}

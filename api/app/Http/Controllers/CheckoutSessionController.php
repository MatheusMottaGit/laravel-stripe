<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\CreateCheckoutSessionRequest;
use Log;

class CheckoutSessionController extends Controller
{
    protected $stripe;

    public function __construct() {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function createCheckoutSession(CreateCheckoutSessionRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('id', $validated['user_id'])->first();

        if(!$user) {
            return response()->json(['message' => "This user don't exists."], 404);
        }
        
        $checkout_session = $this->stripe->checkout->sessions->create([
            'payment_method_types' => [
                'card'
            ],
            'line_items' => [[
                'price' => $validated['price_id'],
                'quantity' => 1, // for a while only test one item
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost:3000/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:3000/cancel',
            'metadata' => [
                'user_id' => $user['id'],
            ],
        ]);

        return response()->json([
            'url' => $checkout_session['url'],
            'session_id' => $checkout_session['id'],
        ]);
    }

    public function verifySession(string $sessionId)
    {
        try {
            $session = $this->stripe->checkout->sessions->retrieve($sessionId);
            // Log::debug($session);
            return response()->json(['data' => $session]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Session not found'], 404);
        }
    }
}

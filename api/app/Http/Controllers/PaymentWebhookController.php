<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Stripe\Webhook;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request) {
        $rawPostRequestPayload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        $event = null;
        
        try {
            $event = Webhook::constructEvent(
                $rawPostRequestPayload,
                $sigHeader,
                $endpointSecret
            );
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            Payment::updateOrCreate(
                [
                    'session_id' => $session->id,
                ],
                [
                    'user_id' => $session->metadata->user_id,
                    'session_id' => $session->id,
                    'status' => $session->status,
                    'payment_intent_id' => $session->payment_intent, // stripe server identifier
                    'currency' => $session->currency,
                    'amount' => $session->amount_total,
                    'customer_email' => $session->customer_email,
                ]
            );
        }

        return response()->json(['message' => 'Payment received'], 200);
    }
}

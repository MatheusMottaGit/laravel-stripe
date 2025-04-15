<?php

namespace App\Http\Controllers;

class ProductController extends Controller
{
    protected $stripe;

    public function __construct() {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function listAllProducts()
    {
        try {
            $productsData = [];

            $products = $this->stripe->products->all();
            
            foreach($products as $prod) {
                $price = $this->stripe->prices->all(['product' => $prod->id]);

                $productsData[] = [
                    'name' => $prod->name,
                    'image' => $prod->images[0] ?? null,
                    'description' => $prod->description,
                    'currency' => $price->data[0]->currency,
                    'price' => $price->data[0]->unit_amount / 100,
                    'price_id' => $price->data[0]->id,
                ];
            }
            
            return response()->json($productsData, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

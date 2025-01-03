<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Shop;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function showPayment($id)
    {
        $shop = Shop::findOrFail($id);
        
        return view('/payment', compact('shop'));
    }

    public function createCheckoutSession(PaymentRequest $request)
    {
        try{
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => '店舗ID: ' . $request->shop_id,
                        ],
                        'unit_amount' => $request->amount,
                    ],
                    'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('paymentSuccess'),
                    'cancel_url' => route('paymentCancel'),
                ]);
                
                return response()->json(['id' => $checkoutSession->id]);
            } catch (ApiErrorException $e) {
                Log::error('Stripe API Error:' . $e->getMessage());
                return response()->json(['error' => '決済処理に失敗しました。後ほど再試行してください。'], 500);
            }
    }

    public function paymentSuccess()
    {
        return view('/success');
    }

    public function paymentCancel()
    {
        return view('/cancel');
    }
}

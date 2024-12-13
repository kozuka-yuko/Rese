<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Shop;
use App\Http\Requests\PaymentRequest;

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
                        'unit_amount' => $request->amount * 100,
                    ],
                    'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => url('/success'),
                    'cancel_url' => url('/cancel'),
                ]);
                
                return response()->json(['id' => $checkoutSession->id]);
            } catch (ApiErrorException $e) {
                \Log::error('Stripe API Error:' . $e->getMessage());
                return response()->json(['error' => '決済処理に失敗しました。後ほど再試行してください。'], 500);
            }
    }

    public function paymentSuccess(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $session = Session::retrieve($request->get('session_id'));
            if ($session->payment_status === 'paid') {
                Payment::create([
                    'user_id' => auth()->id(),
                    'amount' => $session->amount_total / 100,
                    'status' => 'success',
                    'transaction_id' => $session->payment_intent,
                    'payment_method' => 'Stripe',
                ]);

                return view('/success');
            }
            return redirect('/mypage')->with('error','支払いに失敗しました');
        } catch (\Exception $e) {
            \Log::error('Error during payment success prosessing:' . $e->getMessage());
            return redirect('mypage')->with('error', '決済処理中にエラーが発生しました');
        }
    }

    public function paymentCancel()
    {
        return view('/cancel');
    }
}

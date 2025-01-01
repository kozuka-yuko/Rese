@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endsection

@section('content')
<div class="content">
    <h3 class="content__inner">
        {{ $shop->name }} へのお支払い
    </h3>
    <form action="" class="payment" method="post">
        @csrf
        <input type="hidden" id="shop_id" name="shop_id" class="payment__inner" value="{{ $shop->id }}">
        <input type="number" name="amount" id="amount" class="payment__inner" placeholder="金額を入力してください">
        <div class="form__error">
            @error('amount')
            {{ $message }}
            @enderror
        </div>
        <button type="submit" id="checkout-button" class="checkout-button">決済をする</button>
        <a href="{{ route('mypage') }}" class="return__btn">戻る</a>
    </form>

    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe('{{ config('services.stripe.public_key') }}');

        document.getElementById('checkout-button').addEventListener('click', async (e) => {
            e.preventDefault();
            const shopId = document.getElementById('shop_id').value;
            const amount = document.getElementById('amount').value;

            if (!amount) {
                alert('金額を入力してください');
                return;
            }
            const response = await fetch('/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    shop_id: shopId,
                    amount: amount
                })
            });

            const session = await response.json();

            if (session.id) {
                stripe.redirectToCheckout({
                    sessionId: session.id
                });
            } else {
                alert('決済に失敗しました')
            }
        });
    </script>
</div>
@endsection
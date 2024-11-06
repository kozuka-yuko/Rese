@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<h1 class="title">店舗代表者作成</h1>
<form action="{{ route('shopRepConfirm') }}" class="new-rep__create" method="post">
    @csrf
    <div class="form__inner">
        <label for="shop-name" class="label__inner">店舗名</label>
        <input type="text" class="info" name="shop_name" value="{{ old('shop_name') }}" />
        <div class="form__error">
            @error('shop_name')
            {{ $message }}
            @enderror
        </div>
        <label for="name" class="label__inner">店舗代表者</label>
        <input type="text" class="info" name="shop_rep_name" value="{{ old('shop_rep_name') }}" />
        <div class="form__error">
            @error('shop_rep_name')
            {{ $message }}
            @enderror
        </div>
        <label for="phone-number" class="label__inner">連絡先</label>
        <input type="tel" class="info" name="phone_number" value="{{ old('phone_number') }}" />
        <div class="form__error">
            @error('phone_number')
            {{ $message }}
            @enderror
        </div>
        <a href="#" onclick="history.back()" class="back__btn">戻る</a>
        <button class="form__confirm" type="submit">入力内容の確認</button>
    </div>
</form>
@endsection
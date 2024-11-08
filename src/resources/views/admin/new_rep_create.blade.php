@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/new_rep_create.css') }}">
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
        <label for="email" class="label__inner">メールアドレス</label>
        <input type="email" class="info" name="email" value="{{ old('email') }}" />
        <div class="form__error">
            @error('email')
            {{ $message }}
            @enderror
        </div>
        <label for="password" class="label__inner">パスワード</label>
        <input type="password" class="info" name="password" placeholder="8文字以上で設定してください" />
        <div class="form__error">
            @error('password')
            {{ $message }}
            @enderror
        </div>
        <label for="password_confirmation" class="label__inner">確認用パスワード</label>
        <input type="password" class="info" name="password_confirmation">
        <a href="#" onclick="history.back()" class="back__btn">戻る</a>
        <button class="form__confirm" type="submit">入力内容の確認</button>
    </div>
</form>
@endsection
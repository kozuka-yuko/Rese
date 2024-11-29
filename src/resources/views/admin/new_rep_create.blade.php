@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/new_rep_create.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="title">店舗代表者作成</h1>
    <form action="{{ route('shopRepConfirm') }}" class="new-rep__create" method="post">
        @csrf
        <div class="form__inner">
            <label for="name" class="label__inner">店舗代表者:</label>
            <input type="text" class="info" name="name" value="{{ old('name') }}" placeholder="例: 山田  太郎"/>
            <div class="form__error">
                @error('name')
                {{ $message }}
                @enderror
            </div>
            <label for="email" class="label__inner">メールアドレス:</label>
            <input type="email" class="info" name="email" value="{{ old('email') }}" placeholder="test@example.ne"/>
            <div class="form__error">
                @error('email')
                {{ $message }}
                @enderror
            </div>
            <label for="password" class="label__inner">パスワード:</label>
            <input type="password" class="info" name="password" placeholder="8文字以上で設定して下さい" />
            <div class="form__error">
                @error('password')
                {{ $message }}
                @enderror
            </div>
            <label for="password_confirmation" class="label__inner">確認用パスワード:</label>
            <input type="password" class="info" name="password_confirmation">
            <div class="button">
                <a href="#" onclick="history.back()" class="back__btn">戻る</a>
                <button class="form__confirm" type="submit">入力内容の確認</button>
            </div>
        </div>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
@endsection

@section('content')
<div class="login__content">
    <div class="login-form__heading">
        <h2 class="title">Login</h2>
    </div>
    <form action="/login" class="form" method="post">
        @csrf
        <div class="form__group">
            <div class="form__input-email">
                <i class="fa-solid fa-envelope"></i>
                <input class="form__inner" type="email" name="email" value="{{ old('email') }}" placeholder="Email" />
            </div>
            <div class="form__error">
                @error('email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__group">
            <div class="form__input-password">
                <i class="fa-solid fa-lock"></i>
                <input class="form__inner" type="password" name="password" placeholder="Password" />
            </div>
            <div class="form__error">
                @error('password')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit">
                ログイン
            </button>
        </div>
    </form>
</div>
@endsection
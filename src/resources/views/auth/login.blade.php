@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
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
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" />
            </div>
            <div class="form__error">
                @error('email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__group">
            <div class="form__input-password">
                <input type="password" name="password" placeholder="Password" />
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
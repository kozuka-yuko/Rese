@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register__content">
    <div class="register-form__heading">
        <h2 class="title">Registration</h2>
    </div>
    <form action="/register" class="form" method="post">
        @csrf
        <div class="form__group">
            <div class="form__input">
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Username" />
            </div>
            <div class="form__error">
                @error('name')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__group">
            <div class="form__input">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" />
            </div>
            <div class="form__error">
                @error('email')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__group">
            <div class="form__input">
                <input type="password" name="password" placeholder="Password" />
            </div>
            <div class="form__error">
                @error('password')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__group">
            <div class="form__input">
                <input type="password" name="password_confirmation" placeholder="確認用パスワード" />
            </div>
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit">登録</button>
        </div>
    </form>
</div>
@endsection
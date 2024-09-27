@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
<div class="thanks__content">
    <div class="thanks-form__heading">
        <h2 class="title">会員登録ありがとうございます</h2>
    </div>
    <form action="/register" class="thnks__form" method="post">
        @csrf
        <div class="form__button">
            <button class="form__button-submit" type="submit">
                ログインする
            </button>
        </div>
    </form>
</div>
@endsection
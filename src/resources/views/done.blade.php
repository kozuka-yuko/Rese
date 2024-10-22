@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/done.css') }}">
@endsection

@section('content')
<div class="done__content">
    <div class="done-form__heading">
        <h2 class="title">ご予約ありがとうございます</h2>
    </div>
    <form action="route('index')" class="done__form" method="get">
        <div class="form__button">
            <button class="form__button-submit" type="submit">
                戻る
            </button>
        </div>
    </form>
</div>
@endsection
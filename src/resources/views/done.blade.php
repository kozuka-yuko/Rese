@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/done.css') }}">
@endsection

@section('content')
<div class="done__content">
    <div class="done-form__heading">
        <h2 class="title">ご予約ありがとうございます</h2>
    </div>
    <a href="{{ route('home') }}" class="redirect">戻る</a>
</div>
@endsection
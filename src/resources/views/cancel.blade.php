@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/cancel.css') }}">
@endsection

@section('content')
<div class="content">
    <p class="cancel">支払いをキャンセルしました</p>
    <a href="{{ route('mypage') }}" class="return__btn">戻る</a>
</div>
@endsection
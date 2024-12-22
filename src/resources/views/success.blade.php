@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/success.css') }}">
@endsection

@section('content')
<p class="success">支払いが完了しました</p>
<a href="{{ route('mypage') }}" class="return__btn">戻る</a>
@endsection
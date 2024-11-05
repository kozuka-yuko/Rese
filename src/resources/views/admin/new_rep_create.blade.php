@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<h1 class="title">店舗代表者作成</h1>
<form action="" class="new-rep__create" method="post">
    @csrf
    <label for="shop-name" class="label__inner">店舗名</label>
    <input type="text" class="info" name="shop-name">
    <label for="name" class="label__inner">店舗代表者氏名</label>
    <input type="text" class="info" name="name">
    <label for="phone-number" class="label__inner">連絡先</label>
    <a href="/admin/confirm" class="form__confirm">入力内容の確認</a>
</form>
@endsection
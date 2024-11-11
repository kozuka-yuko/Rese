@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_rep/confirm.css') }}">
@endsection

@section('content')
<div class="confirm-content">
    <form action="" class="confirm" method="">
        @method('PATCH')
        @csrf
        <p class="shop_name">店舗名</p>
        <div class="img">
            <img class="image" src="" alt="お店の画像" />
        </div>
        <div class="tag">
            # #
        </div>
        <div class="info">
            <p class="info__inner">{{  }}</p>
        </div>
    </form>
</div>
@endsection
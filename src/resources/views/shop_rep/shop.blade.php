@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_rep/shop.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="detail-content">
        <div class="shop-name">
            <span class="shop-name__inner">{{ $shop->name }}</span>
        </div>
        <div class="img">
            <img class="img_url" src="{{ asset($shop->img_url) }}" alt="お店の画像" />
        </div>
        <div class="tag">
            #{{ $shop->area->name ?? 'エリア不明' }} #{{ $shop->genre->name ?? 'ジャンル不明' }}
        </div>
        <div class="info">
            <p class="info__inner">{{ $shop->info }}</p>
        </div>
    </div>
    <a href="{{ route('shopEdit', $shop->id) }}" class="edit">変更</a>
</div>
@endsection
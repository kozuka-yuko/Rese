@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_rep/edit.css') }}">
@endsection

@section('content')
<div class="edit-content">
    <h1 class="title">店舗情報編集</h1>
    <form action="{{ route('shopUpdateConfirm') }}" class="shop__edit" method="post" enctype="multipart/form-data">
        @csrf
        <p class="shop_name">{{ $shop->name }}</p>
        <div class="image-file">
            <label for="" class="edit-label">ShopImage</label>
            <input type="file" name="image" class="image">
        </div>
        <div class="area">
            <label for="" class="edit-label">Area</label>
            <select name="area" class="area__inner">
                <option value="" hidden>{{ optional($shop->area)->name }}</option>
                @foreach ($areas as $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="genre">
            <label for="" class="edit-label">Genre</label>
            <select name="genre" class="genre__inner">
                <option value="" hidden>{{ optional($shop->genre)->name }}</option>
                @foreach ($genres as $genre)
                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="description">
            <label for="" class="edit-label">ShopInfo</label>
            <textarea name="description" id="description" class="description__inner" cols="30" rows="5"></textarea>
        </div>
        <a href="#" onclick="history.back()" class="back__btn">戻る</a>
        <button class="upload">入力内容の確認</button>
    </form>
</div>
@endsection
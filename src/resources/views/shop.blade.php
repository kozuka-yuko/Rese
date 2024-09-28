@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}">
@endsection

@section('search')
<form action="" method="">
    <div class="search">
        <select name="area" class="area">
            <option value="">All area</option>

        </select>
        <select name="genre" class="genre">
            <option value="" disabled>All genre</option>

        </select>
        <input type="text" class="search-form__input" name="name__input" placeholder="Search..." value="{{ old('name__input') }}" />
        <input type="submit" />
    </div>
</form>
@endsection

@section('content')
<div class="shop__content">
    <div class="shop">
        <div class="img">
            <a class="img-url" href=""></a>
        </div>
        <div class="info">
            <h3 class="shop__naeme">仙人</h3>
            <p class="tag">＃東京都 ＃寿司</p>
        </div>
        <div class="button">
            <button class="detail__button" type="submit">詳しく見る</button>
        </div>
        <div class="favorite">

        </div>
    </div>
</div>
@endsection
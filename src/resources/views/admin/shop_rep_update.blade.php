@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/shop_rep_update.css') }}">
@endsection

@section('content')
<div class="update__content">
    <form action="{{ route('updateConfirm') }}" class="update__form" method="post">
        @csrf
        <label for="shop-name" class="label__inner">店舗名</label>
        <p class="shop-name__inner">{{ $shopRep->shops->first()->name }}</p>
        <select name="area_id" class="area">
            <option value="" hidden>{{ old('area', $shopRep->shops->first()->area) }}</option>
            @foreach ($areas as $area)
            <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
        <select name="genre_id" class="genre">
            <option value="" hidden>{{ $shopRep->shops->first()->genre }}</option>
            @foreach ($genres as $genre)
            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
            @endforeach
        </select>
        <label for="shop_rep_name" class="label__inner">店舗代表者</label>
        <input type="text" class="info" name="shop_rep_name" value="{{ old('shop_rep_name', $shopRep->name) }}" />
        <div class="form__error">
            @error('shop_rep_name')
            {{ $message }}
            @enderror
        </div>
        <label for="email" class="label__inner">メールアドレス</label>
        <input type="email" class="info" name="email" value="{{ old('email', $shopRep->email) }}" />
        <div class="form__error">
            @error('email')
            {{ $message }}
            @enderror
        </div>
        <a href="#" onclick="history.back()" class="back__btn">戻る</a>
        <button class="form__confirm" type="submit">入力内容の確認</button>
    </form>
</div>
@endsection
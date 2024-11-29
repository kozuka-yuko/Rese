@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_rep/edit.css') }}">
@endsection

@section('content')
<div class="edit-content">
    <h1 class="title">店舗情報編集</h1>
    <form action="{{ route('shopUpdateConfirm', $shop->id) }}" class="shop__edit" method="post" enctype="multipart/form-data">
        @csrf
        <input type="text" name="name" class="name" value="{{ old('name', $shop->name) }}" />
        <div class="img-file">
            <label for="" class="edit-label">Current Shop Image:</label>
            @if ($shop->img_url)
            <img src="{{ Storage::url($shop->img_url) }}" alt="Shop Image" class="now-img">
            @else
            <p class="message">画像が設定されていません</p>
            @endif
        </div>
        <div class="image-file">
            <label for="" class="edit-label">Select Shop Image:</label>
            <input type="file" name="image" class="image">
        </div>
        <div class="form__error">
            @error('image')
            {{ $message }}
            @enderror
        </div>
        <div class="area">
            <label for="" class="edit-label">Area:</label>
            <select name="area" class="area__inner">
                @foreach ($areas as $area)
                <option value="{{ $area->id }}" @if (old('area', $shop->area_id) == $area->id) selected @endif>
                    {{ $area->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form__error">
            @error('area')
            {{ $message }}
            @enderror
        </div>
        <div class="genre">
            <label for="" class="edit-label">Genre:</label>
            <select name="genre" class="genre__inner">
                @foreach ($genres as $genre)
                <option value="{{ $genre->id }}" @if (old('genre', $shop->genre_id) == $genre->id) selected @endif>{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form__error">
            @error('genre')
            {{ $message }}
            @enderror
        </div>
        <div class="description">
            <label for="" class="edit-label">Shop description:</label>
            <textarea name="description" id="description" class="description__inner" cols="50" rows="8">{{ old('description', $shop->description) }}</textarea>
        </div>
        <div class="form__error">
            @error('description')
            {{ $message }}
            @enderror
        </div>
        <a href="#" onclick="history.back()" class="back__btn">戻る</a>
        <button class="upload">入力内容の確認</button>
    </form>
</div>
@endsection
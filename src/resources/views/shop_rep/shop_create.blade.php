@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_rep/shop_create.css') }}">
@endsection

@section('content')
<div class="edit-content">
    <h1 class="title">店舗情報作成</h1>
    <form action="{{ route('shopCreateConfirm') }}" class="shop__create" method="post" enctype="multipart/form-data">
        @csrf
        <label for="" class="edit-label">Shop Name:</label>
        <input type="text" name="name" class="name" value="{{ old('name') }}" />
        <div class="form__error">
            @error('name')
            {{ $message }}
            @enderror
        </div>
        <div class="image-file">
            <label for="" class="edit-label">Select Shop Image:</label>
            <input type="file" name="image" class="image" value="{{ old('image') }}" />
        </div>
        <div class="form__error">
            @error('image')
            {{ $message }}
            @enderror
        </div>
        <div class="area">
            <label for="" class="edit-label">Area:</label>
            <select name="area" class="area__inner">
                <option value="" hidden>選択</option>
                @foreach ($areas as $area)
                <option value="{{ $area->id }}" @if (old('area')==$area->id) selected @endif>
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
                <option value="" hidden>選択</option>
                @foreach ($genres as $genre)
                <option value="{{ $genre->id }}" @if (old('genre')==$genre->id) selected @endif>{{ $genre->name }}</option>
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
            <textarea name="description" id="description" class="description__inner" cols="50" rows="8">{{ old('description') }}</textarea>
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
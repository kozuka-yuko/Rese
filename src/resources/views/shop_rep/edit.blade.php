@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_rep/edit.css') }}">
@endsection

@section('content')
<div class="edit-content">
    <h1 class="title">店舗情報編集</h1>
    <form action="" class="shop__edit" method="" enctype="multipart/form-data">
        @csrf
        <p class="shop_name">店舗名</p>
        <input type="file" name="image" class="image">
        <button class="upload">アップロード</button>
        <select name="area_id" class="area">
            <option value="" hidden>All area</option>
            @foreach ($areas as $area)
            <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
        <select name="genre_id" class="genre">
            <option value="" hidden>All genre</option>
            @foreach ($genres as $genre)
            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
            @endforeach
        </select>
        <textarea name="info" id="info" class="info" cols="30" rows="5"></textarea>
    </form>
</div>
@endsection
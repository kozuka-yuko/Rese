@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/shop_rep_list.css') }}">
@endsection

@section('search')
<form class="search-form" action="{{ route('repSearch') }}" method="get">
    <div class="search">
        <input type="text" class="search-form__input" name="rep_name" placeholder="SHopRepSearch" value="{{ old('rep_name') }}" />
        <input type="text" class="search-form__input" name="shop_name" placeholder="ShopSearch" value="{{ old('shop_name') }}" />
        <button class="search-form__submit" type="submit"></button>
    </div>
</form>
@endsection

@section('content')
<div class="content">
    <h1 class="title">店舗代表者一覧</h1>
    <table class="rep__list">
        <tr class="table__row">
            <th class="rep__list--header">店舗名</th>
            <th class="rep__list--header">代表者</th>
            <th class="rep__list--header">エリア</th>
            <th class="rep__list--header">ジャンル</th>
        </tr>
        @foreach ($shopReps as $shopRep)
        <tr class="table__row">
            <td class="rep__list--data">{{ optional($shopRep->shops->first())->name }}</td>
            <td class="rep__list--data">{{ optional($shopRep)->name }}</td>
            <td class="rep__list--data">{{ optional($shopRep->shops->first())->area->name ?? ' ' }}</td>
            <td class="rep__list--data">{{ optional($shopRep->shops->first())->genre->name ?? ' ' }}</td>
            <td class="rep__list--data">
                <a href="{{ route('updateEdit', $shopRep->id) }}" class="edit">変更</a>
            </td>
            <td class="rep__list--data">
                <form action="{{ route('shopRepDestroy') }}" method="post" class="delete__shop-rep">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" name="id" value="{{ $shopRep->id }}">
                    <button class="delete__shop-rep--button" type="submit" title="削除" onclick='return confirm("{{ optional($shopRep)->name }}を削除しますか？")'>削除</button>
            </td>
        </tr>
        </form>
        @endforeach
    </table>
</div>
@endsection
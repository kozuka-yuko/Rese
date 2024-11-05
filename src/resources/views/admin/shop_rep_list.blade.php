@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<table class="rep__list">
    <tr class="table__row">
        <th class="rep__list--header">店舗名</th>
        <th class="rep__list--header">代表者</th>
        <th class="rep__list--header">連絡先</th>
        <th class="rep__list--header">エリア</th>
        <th class="rep__list--header">ジャンル</th>
    </tr>
    @foreach ($shopReps as $shopRep)
    <tr class="table__row">
        <td class="rep__list--data">{{ $shopRep->shop->name }}</td>
        <td class="rep__list--data">{{ $shoRep->shop_rep_name }}</td>
        <td class="rep__list--data">{{ $shopRep->phone_number }}</td>
        <td class="rep__list--data">{{ $shopRep->shop->area }}</td>
        <td class="rep__list--data">{{ $shopRep->shop->genre }}</td>
    </tr>
    <a href="" class="edit">変更</a>
    <form action="{{ route('shopRepDestroy') }}" method="post" class="delete__shop-rep">
        @method('DELETE')
        @csrf
        <input type="hidden" name="id" value="{{ $shopRep->id }}">
        <button class="delete__shop-rep--button" type="submit" title="削除" onclick='return confirm("{{ $shopRep->shop->name }}を削除しますか？")'>削除</button>
    </form>
    @endforeach
</table>
@endsection
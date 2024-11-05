@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/management.css') }}">
@endsection

@section('content')
<div class="management-content">
    <h1 class="title">管理画面</h1>
    <ul class="link">
        <li class="link-btn">
            <a href="{{ route('shopRepList') }}" class="link-btn__inner">店舗代表者一覧</a>
        </li>
        <li class="link-btn">
            <a href="{{ route('newRepEdit') }}" class="link-btn__inner">店舗代表者新規作成</a>
        </li>
        <li class="link-btn">
            <a href="/admin/send_email" class="link-btn__inner">お知らせメール送信</a>
        </li>
    </ul>
</div>
@endsection
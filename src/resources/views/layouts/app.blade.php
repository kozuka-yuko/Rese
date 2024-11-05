<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <div class="header__heading">
                <a href="#modal" class="modal__menu">
                    <span class="hamburger-top"></span>
                    <span class="hamburger-middle"></span>
                    <span class="hamburger-under"></span>
                </a>
                <div class="modal" id="modal">
                    <div class="modal__inner">
                        <a href="#" class="close">&times;</a>
                        <ul class="modal__content">
                            @if (Auth::check())
                            @can('shop')
                            <li class="modal__content--item">
                                <a href="{{ route('repIndex') }}" class="myshop">My Shop</a>
                            </li>
                            <li class="modal__content--item">
                                <a href="{{ route('getReservation') }}" class="reservation-confirm">Reservation Confirm</a>
                            </li>
                            <li class="modal__content--item">
                                <a href="{{ route('shopRepEdit') }}" class="shop-edit">Shop Edit</a>
                            </li>
                            @endcan
                            @can('register')
                            <li class="modal__content--item">
                                <a href="{{ route('adIndex') }}" class="management">Management</a>
                            </li>
                            <li class="modal__content--item">
                                <a href="{{ route('sendEmail') }}" class="send-email">Send Email</a>
                            </li>
                            <li class="modal__content--item">
                                <a href="{{ route('shopRepList') }}" class="shop-rep__list">Shop Rep List</a>
                            </li>
                            <li class="modal__content--item">
                                <a href="{{ route('newRepCreate') }}" class="new-create">New Rep Create</a>
                            </li>
                            @endcan
                            <li class="modal__content--item">
                                <a href="/shop" class="home">Home</a>
                            </li>
                            <li class="modal__content--item">
                                <form action="/logout" method="post">
                                    @csrf
                                    <button class="modal__content-btn">
                                        Logout
                                    </button>
                                </form>
                            </li>
                            <li class="modal__content--item">
                                <a href="{{ route('mypage') }}" class="mypage">Mypage</a>
                            </li>
                            @else
                            <li class="modal__content--item">
                                <a href="{{ route('index') }}" class="home">Home</a>
                            </li>
                            <li class="modal__content--item"> <a href="/register" class="registration">Registration</a>
                            </li>
                            <li class="modal__content--item">
                                <a href="/login" class="login">Login</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <a href="
                @if (auth()->check() && auth()->user()->can('register'))
                    {{ route('adIndex') }}
                @elseif (auth()->check() && auth()->user()->can('shop'))
                    {{ route('repIndex') }}
                @else
                    {{ url('/shop') }}
                @endcan
                " class="header__logo">Rese</a>
            </div>
            <div class="search">
                @yield('search')
            </div>
        </header>
        <div class="message">
            @if (session('result'))
            <div class="message__inner">
                {{ session('result') }}
            </div>
            @php
            session()->forget('result');
            @endphp
            @endif
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>
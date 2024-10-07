<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <div class="header__heading">
                <span class="hamburger-top"></span>
                <span class="hamburger-middle"></span>
                <span class="hamburger-under"></span>
                <a href="#modal" class="modal__menu"></a>
                <div class="modal" id="modal">
                    <div class="modal__inner">
                        <a href="#" class="close">&times;</a>
                        <ul class="modal__content">
                            @if (Auth::check())
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
                                <a href="/mypage" class="mypage">Mypage</a>
                            </li>
                            @else
                            <li class="modal__content--item">
                                <a href="/shop" class="home">Home</a>
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
                <h1 class="header__heading-inner">Rese</h1>
            </div>
            <div class="search">
                @yield('search')
            </div>
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>
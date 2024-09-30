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
                <a href="#modal" class="modal__menu"></a>
                <div class="modal" id="modal">
                    <div class="modal__inner">
                        <a href="#" class="close">&times;</a>
                        <div class="modal__content">
                            <a href="/shop" class="home">Home</a><br>
                            <a href="/register" class="registration">Registration</a><br>
                            <a href="/login" class="login">Login</a><br>
                            @if (Auth::check())
                            <a href="/shop" class="home">Home</a><br>
                            <a href="/logout" class="logout">Logout</a><br>
                            <a href="/mypage" class="mypage">Mypage</a><br>
                            @endif
                        </div>
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
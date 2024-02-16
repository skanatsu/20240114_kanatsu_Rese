<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>メニュー</title>
</head>

<body>
    <header class="header">

<a href="javascript:history.back()">
  <img src="{{ asset('images/menu_close.png') }}" alt="メニュー">
</a>

    </header>
<a href="http://localhost/">Home</a>
@guest
<a href="http://localhost/register">Registration</a>
<a href="http://localhost/login">Login</a>
@endguest
@auth
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a href="#" class="header__nav-menu-list-link"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
<a href="http://localhost/mypage">Mypage</a>
@endauth

</body>

</html>

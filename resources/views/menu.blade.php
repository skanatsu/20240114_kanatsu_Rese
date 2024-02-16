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
        <p class="header__logo">Atte</p>
        <nav class="header__nav">
            <ul class="header__nav-menu">
                <li class="header__nav-menu-list"><a href="http://localhost/" class="header__nav-menu-list-link">ホーム</a>
                </li>
                <li class="header__nav-menu-list"><a href="http://localhost/attendance"
                        class="header__nav-menu-list-link">日付一覧</a></li>
                <li class="header__nav-menu-list"><a href="http://localhost/userlist"
                        class="header__nav-menu-list-link">ユーザー一覧</a></li>
                <li class="header__nav-menu-list">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a href="#" class="header__nav-menu-list-link"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
                    </form>
                </li>
            </ul>
        </nav>
    </header>
<button class="home" id="homeButton">
    Home
</button>
@guest
<button class="registration" id="registrationButton">
    Registration
</button>
<button class="login" id="loginButton">
    Login
</button>
@endguest
@auth
<button class="logout" id="logoutButton">
    Logout
</button>
<button class="mypage" id="mypageButton">
    Mypage
</button>
@endauth
</body>

<script>
    var homeButton = document.getElementById('homeButton');

    homeButton.addEventListener('click', function() {
        window.location.href = 'http://localhost/';
    });

    var registrationButton = document.getElementById('registrationButton');

    registrationButton.addEventListener('click', function() {
        window.location.href = 'http://localhost/registration';
    });

    var loginButton = document.getElementById('loginButton');

    loginButton.addEventListener('click', function() {
        window.location.href = 'http://localhost/login';
    });

    var logoutButton = document.getElementById('logoutButton');

    logoutButton.addEventListener('click', function() {
        window.location.href = 'http://localhost/logout';
    });

var mypageButton = document.getElementById('mypageButton');

mypageButton.addEventListener('click', function() {
    window.location.href = '/mypage'; // 相対URLに変更
});

</script>

</html>

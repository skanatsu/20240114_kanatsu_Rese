<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/userlist.css') }}">
    <title>ユーザー一覧</title>
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
    <div class="main">
        <form action="/userlist" method="POST" class="search">
            @csrf
            <div class="search__form">
                <p class="search__form-p">【 ユーザー検索 】</p>
                <div class="search__name">
                    <label for="name" class="search__name__label">名前</label>
                    <input type="text" id="name" name="name" class="search__name__input">
                </div>
                <div class="search__email">
                    <label for="email" class="search__email__label">メールアドレス</label>
                    <input type="text" id="email" name="email" class="search__email__input">
                </div>
                <button class="search__button">検索／リセット</button>
            </div>
        </form>
        <table class="table">
            <thead>
                <tr class="user__tr">
                    <th class="user__th">名前</th>
                    <th class="user__th">メールアドレス</th>
                    <th class="user__th"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="user__tr">
                        <td class="user__td">{{ $user->name }}</td>
                        <td class="user__td">{{ $user->email }}</td>
                        <td class="user__td">
                            <form action="{{ route('attendanceshow', ['user' => $user->id]) }}" method="get">
                                @csrf
                                <button type="submit" class="user__button"
                                    data-user="{{ $user->id }}">勤怠表示</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links('vendor.pagination.tailwind2') }}
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            var attendanceButtons = document.querySelectorAll('.user__button');
            attendanceButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    window.location.href = '/attendance/' + this.getAttribute('data-user');
                });
            });
        });
    </script>
    <footer class="footer">
        <small>
            <p class="footer__copyright">
                Atte,Inc.
            </p>
        </small>
    </footer>
</body>
</html>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>マイページ</title>
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
mypage
<h1 class="username">{{ auth()->user()->name }}さん</h1>
<h2 class="reservation_status">予約状況</h2>

       <form action="/reservation/delete" method="post" class="reservation-form">
            @csrf
            @method('delete')

            @foreach($reservations as $reservation)
                <div class="reservation caset">
                    予約{{ $loop->iteration }}
                </div>
                <button class="reservation__delete" name="id" value="{{ $reservation['id'] }}">❌</button>
                <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                <table>
                    <!-- 予約データの表示 -->

                    <tr>
                        <td>Shop</td>
                        <td>{{ $reservation->shop->shopname }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>{{ $reservation->date }}</td>
                    </tr>
                    <tr>
                        <td>Time</td>
                        <td>{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Number</td>
                        <td>{{ $reservation->number }}人</td>
                    </tr>
                </table>


            @endforeach
        </form>

<h2 class="favorite_shop">お気に入り店舗</h2>



</body>
</html>

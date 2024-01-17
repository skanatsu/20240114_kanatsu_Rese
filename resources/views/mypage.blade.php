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
<table>
    <thead>
        <tr>
            <th>Reservation</th>
            @isset($reservations)
                @foreach($reservations as $reservation)
                    <th>{{ $reservation->shop->shopname }}</th>
                @endforeach
            @endisset
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Date</td>
            @isset($reservations)
                @foreach($reservations as $reservation)
                    <td>{{ $reservation->date }}</td>
                @endforeach
            @endisset
        </tr>
        <tr>
            <td>Time</td>
            @isset($reservations)
                @foreach($reservations as $reservation)
                    <td>{{ $reservation->time }}</td>
                @endforeach
            @endisset
        </tr>
        <tr>
            <td>Number</td>
            @isset($reservations)
                @foreach($reservations as $reservation)
                    {{-- <td>{{ $reservation->number }}</td> --}}
                    <td>{{ $reservation['number'] }}</td>
                @endforeach
            @endisset
        </tr>
    </tbody>
</table>

    {{-- ここから --}}
    <div class="container">
        @section('reservation_section')
            @isset($reservations)
                <h1 class="username">{{ auth()->user()->name }}さん</h1>
                <h2 class="reservation_status">予約状況</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Reservation</th>
                            <th>Shop</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->id }}</td>
                                <td>{{ $reservation->shop->shopname }}</td>
                                <td>{{ $reservation->date }}</td>
                                <td>{{ $reservation->time }}</td>
                                <td>{{ $reservation->number }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">予約がありません</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endisset
        @show

        <!-- 別のセクションに同じ変数を使用する場合 -->
        @section('another_section')
            <!-- 別のセクションでのコード -->
        @show
    </div>


            <div class="container">
        <h2 class="reservation_status">予約状況</h2>
        @isset($reservations)
        <table>
            <thead>
                <tr>
                    <th>Reservation</th>
                    <th>Shop</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Number</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->id }}</td>
                    <td>{{ $reservation->shop->shopname }}</td>
                    <td>{{ $reservation->date }}</td>
                    <td>{{ $reservation->time }}</td>
                    <td>{{ $reservation->number }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">予約がありません</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @else
        <p>予約がありません</p>
        @endisset
    </div>
    {{-- ここまで --}}

<h2 class="favorite_shop">お気に入り店舗</h2>
</body>
</html>

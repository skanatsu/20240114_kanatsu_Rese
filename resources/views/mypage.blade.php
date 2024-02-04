<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @foreach ($reservations as $reservation)
        <div class="reservation caset">
            予約{{ $loop->iteration }}
        </div>

        <form action="/reservation/delete" method="post" class="reservation-form">
            @csrf
            @method('delete')

            <button class="reservation__delete" name="id" value="{{ $reservation['id'] }}">❌</button>
            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
        </form>

        <form action="{{ route('reservation.update', ['id' => $reservation->id]) }}" method="post"
            class="reservation__update-form">
            @csrf
            @method('put')

            <td colspan="2">
                <input type="date" id="date" class="reservation__date" name="date"
                    value="{{ $reservation->date }}" onchange="updateTable()">
                <select id="time" class="reservation__time" name="time">
                    @for ($hour = 0; $hour < 24; $hour++)
                        @for ($minute = 0; $minute < 60; $minute += 30)
                            @php
                                $timeValue = sprintf('%02d:%02d', $hour, $minute);
                                $selected = $timeValue === date('H:i', strtotime($reservation->time)) ? 'selected' : '';
                            @endphp
                            <option value="{{ $timeValue }}" {{ $selected }}>{{ $timeValue }}</option>
                        @endfor
                    @endfor
                </select>
                <select id="people" name="people" onchange="updateTable()">
                    @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ $i == $reservation->number ? 'selected' : '' }}>
                            {{ $i }}人</option>
                    @endfor
                </select>
                <button type="submit" class="reservation">予約変更</button>
            </td>
        </form>

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


    <h2 class="favorite_status">お気に入り店舗</h2>
    @foreach ($favoriteShops as $favorite)
        <table>
            <!-- お気に入り店舗データの表示 -->
            <tr>
                <td>{{ $favorite->shop->shopname }}</td>
            </tr>
            <tr>
                <td>{{ $favorite->shop->area }}</td>
            </tr>
            <tr>
                <td>{{ $favorite->shop->genre }}</td>
            </tr>
            <tr>
                <a href="{{ route('detail', ['id' => $favorite->shop->id]) }}" class="detail">詳しくみる</a>

                <img src="{{ url('/images/' . ($favorite->shop ? ($favorite->shop->isFavorite ? 'heart.jpeg' : 'greyheart.png') : 'default.png')) }}"
                    alt="" class="heart" data-shop-id="{{ $favorite->shop ? $favorite->shop->id : 0 }}">
            </tr>
        </table>
    @endforeach

    <script>
        function toggleImage(element, shopId) {
            var currentSrc = element.src;
            var newSrc = currentSrc.includes('greyheart.png') ? '{{ url('/images/heart.jpeg') }}' :
                '{{ url('/images/greyheart.png') }}';
            element.src = newSrc;

            // お気に入りのトグル処理を呼び出す
            if (shopId) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('mypage.toggle-favorite', ['shopId' => '__SHOP_ID__']) }}'.replace(
                        '__SHOP_ID__', shopId),
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        // 成功時の処理
                        console.log(response);
                    },
                    error: function(error) {
                        // エラー時の処理
                        console.error(error);
                    }
                });
            }
        }

        // クリックイベントで画像の切り替えを実行
        document.querySelectorAll('.heart').forEach(function(element) {
            element.addEventListener('click', function() {
                var shopId = this.getAttribute('data-shop-id');
                toggleImage(this, shopId);
                // ページをリロード
                location.reload();
            });
        });
    </script>
</body>

</html>

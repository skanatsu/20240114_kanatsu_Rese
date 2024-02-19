<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/mypage.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>マイページ</title>
</head>

<body>
        <div class="logo">
            <div class="logo_link">
                <a href="{{ url('menu') }}">
                    <img src="{{ asset('images/menu.png') }}" class="menu_image" alt="メニュー">
                </a>
            </div>
            <div class="logo_title">
                <p class="header__logo">Rese</p>
            </div>
        </div>

    <h1 class="username">{{ auth()->user()->name }}さん</h1>

<div class="mypage__content">
<div class="mypage__content__reservation">

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

@if(isset($reservations) && count($reservations) > 0)
    @foreach ($reservations as $reservation)

@php
            $reservationDateTime = strtotime($reservation->date . ' ' . $reservation->time);
            $currentDateTime = strtotime(date('Y-m-d H:i:s'));
            $isReservationPast = $currentDateTime > $reservationDateTime;
        @endphp


    <div class="reservation__card">
        <div class="reservation__card__header">
<div class="reservation__clock__number">
        <img src="{{ asset('images/clock.png') }}" class="clock_image" alt="時計">
        <div class="reservation__number">
            予約{{ $loop->iteration }}
               @if ($isReservationPast)
               <div class="reservation__status">ご来店済み</div>
    @endif
</div>
</div>

@if ($currentDateTime <= $reservationDateTime)

        <form action="/reservation/delete" method="post" class="reservation-form">
            @csrf
            @method('delete')



            <button class="reservation__delete" name="id" value="{{ $reservation['id'] }}">
                <img src="{{ asset('images/delete.png') }}" class="delete_image" alt="予約削除ボタン">
            </button>


 

            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
        </form>
        @endif
        </div>



        <table>
            <!-- 予約データの表示 -->
            <tr class="reservation__item">
                <td>Shop</td>
                <td class="reservation__data">{{ $reservation->shop->shopname }}</td>
            </tr>
            <tr class="reservation__item">
                <td>Date</td>
                <td class="reservation__data">{{ $reservation->date }}</td>
            </tr>
            <tr class="reservation__item">
                <td>Time</td>
                <td class="reservation__data">{{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}</td>
            </tr>
            <tr class="reservation__item">
                <td>Number</td>
                <td class="reservation__data">{{ $reservation->number }}人</td>
            </tr>

        </table>


{{-- <div class="qr-code">
        <img src="{{ route('reservation.qrcode', ['reservation_id' => $reservation->id]) }}" alt="QR Code">
    </div> --}}
@if (!$isReservationPast)
<div class="qr-code">
    <p class="qr-code__text">【QRコード】</p>
    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate($reservation->id)) !!}" class="qr-code__img" alt="QR Code">
</div>
@endif


@if (!$isReservationPast)

        <div class="reservation__update">
            <p class="reservation__update__text">
                ▼ 予約変更はこちら
            </p>


        <form action="{{ route('reservation.update', ['id' => $reservation->id]) }}" method="post"
            class="reservation__update-form">
            @csrf
            @method('put')

            <td colspan="2">
                <input type="date" id="date" class="reservation__update__date" name="date"
                    value="{{ $reservation->date }}" onchange="updateTable()">
                <select id="time" class="reservation__update__time" name="time">
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
                <select id="people" name="people" class="reservation__update__number" onchange="updateTable()">
                    @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ $i == $reservation->number ? 'selected' : '' }}>
                            {{ $i }}人</option>
                    @endfor
                </select>
                <button type="submit" class="reservation__update__button">予約変更</button>
            </td>
        </form>
</div>

@endif

    @php
        $reservationDateTime = strtotime($reservation->date . ' ' . $reservation->time);
        $currentDateTime = strtotime(date('Y-m-d H:i:s'));
        $isReservationPast = $currentDateTime > $reservationDateTime;
    @endphp

    @if (!$reservation->review && $isReservationPast)
        <button class="reservation__evaluate" data-reservation-id="{{ $reservation->id }}">評価を送る</button>
    @endif

<div class="reservation__evaluate-form" style="display: none;">
   
    @if(!$reservation->review)
        <form action="{{ route('reservation.evaluate', ['id' => $reservation->id]) }}" method="post">
            @csrf
            <label for="rating" class="review__item">▼ 評価スコア（5：満足 〜 1：不満）</label>
            <select name="score" id="rating" class="review__score">
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            <br>
            <label for="comment" class="review__item">▼ 評価コメント</label>
            <textarea name="comment" id="comment" rows="3" class="review__comment"></textarea>
            <br>
            <button type="submit" class="review__submit">評価送信</button>
        </form>
    @endif
</div>

{{-- 決済 --}}
        @if (!$isReservationPast)

        <div class="reservation__pay">
                <a href="{{ route('reservation.pay', ['id' => $reservation->id]) }}" class="reservation__pay__link">来店前の決済はこちら</a>

            </div>
        @endif

</div>
    @endforeach

@endif

</div>

<div class="mypage__content__favorite">

    <h2 class="favorite_status">お気に入り店舗</h2>


@if(isset($favoriteShops) && count($favoriteShops) > 0)

    @foreach ($favoriteShops as $favorite)
    <div class="shop">
    <img src="{{ $favorite->shop->image_url }}" class="shop__image" alt="店舗画像">
                        <p class="shop__name">{{ $favorite->shop->shopname }}</p>
                <div class="shop__tag">
                    <p class="shop__area">#{{ $favorite->shop->area }}</p>
                    <p>#{{ $favorite->shop->genre }}</p>
                </div>
                <a href="{{ route('detail', ['id' => $favorite->shop->id]) }}" class="shop__detail">詳しくみる</a>
                @auth
                    <img src="{{ url('/images/' . ($favorite->shop ? ($favorite->shop->isFavorite ? 'heart.png' : 'greyheart.png') : 'default.png')) }}"
                    alt="" class="heart" data-shop-id="{{ $favorite->shop ? $favorite->shop->id : 0 }}">
    @endauth

      </div>
    @endforeach
@endif
  </div>
</div>

        
    <script>
        function toggleImage(element, shopId) {
            var currentSrc = element.src;
            var newSrc = currentSrc.includes('greyheart.png') ? '{{ url('/images/heart.png') }}' :
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



    //         // 評価ボタンがクリックされたときの処理
    // document.querySelector('.reservation__evaluate').addEventListener('click', function () {
    //     // 関連する評価入力フォームを表示
    //     var evaluateForm = this.nextElementSibling;
    //     evaluateForm.style.display = 'block';
    // });

        document.querySelectorAll('.reservation__evaluate').forEach(function (evaluateButton) {
        evaluateButton.addEventListener('click', function () {
            // 関連する評価入力フォームを表示
            var evaluateForm = this.nextElementSibling;
            evaluateForm.style.display = 'block';
        });
    });
    </script>
</body>

</html>

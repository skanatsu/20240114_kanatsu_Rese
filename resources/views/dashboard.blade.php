<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>飲食店一覧ページ</title>
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
    飲食店一覧

<select id="area" name="area" onchange="filterShops()">
        <option value="allarea">All area</option>
        <option value="東京都">東京都</option>
        <option value="大阪府">大阪府</option>
        <option value="福岡県">福岡県</option>
    </select>

    <select id="genre" name="genre" onchange="filterShops()">
        <option value="allgenre">All genre</option>
        <option value="居酒屋">居酒屋</option>
        <option value="焼肉">焼肉</option>
        <option value="寿司">寿司</option>
        <option value="ラーメン">ラーメン</option>
        <option value="イタリアン">イタリアン</option>
    </select>

<input type="text" id="shopname" class="search__shopname" name="shopname" placeholder="Search..." onkeydown="searchOnEnter(event)">

    @foreach ($shops as $shop)
        <div class="shop" data-area="{{ $shop->area }}" data-genre="{{ $shop->genre }}" data-shopname="{{ $shop->shopname }}">
            <p>店名: {{ $shop->shopname }}</p>
            <p>エリア: {{ $shop->area }}</p>
            <p>ジャンル: {{ $shop->genre }}</p>
            <a href="{{ route('detail', ['id' => $shop->id]) }}" class="detail">詳しくみる</a>
            {{-- <img src="{{ url('/images/greyheart.png') }}" alt="" class="heart" onclick="toggleImage(this, {{ $shop->id }})" id="heartImage_{{ $loop->index }}"> --}}
            <img src="{{ url('/images/'. ($shop->isFavorite ? 'heart.jpeg' : 'greyheart.png')) }}" alt="" class="heart" onclick="toggleImage(this, {{ $shop->id }})" id="heartImage_{{ $loop->index }}">
        </div>
    @endforeach

<script>
        function filterShops() {
            var selectedArea = document.getElementById("area").value;
            var selectedGenre = document.getElementById("genre").value;
            var searchShopname = document.getElementById("shopname").value.toLowerCase();
            var shops = document.getElementsByClassName("shop");

            for (var i = 0; i < shops.length; i++) {
                var shop = shops[i];
                var area = shop.getAttribute("data-area");
                var genre = shop.getAttribute("data-genre");
                var shopname = shop.getAttribute("data-shopname").toLowerCase();

                // 選択されたエリア、ジャンルか "allarea"、"allgenre" なら表示、それ以外は非表示
                if ((selectedArea === "allarea" || area === selectedArea) &&
                    (selectedGenre === "allgenre" || genre === selectedGenre) &&
                    shopname.includes(searchShopname)) {
                    shop.style.display = "block";
                } else {
                    shop.style.display = "none";
                }
            }
        }

        function searchOnEnter(event) {
            if (event.key === "Enter") {
                filterShops();
            }
        }

        // 画像の状態をトグルする関数
                function toggleImage(element) {
            var currentSrc = element.src;

            if (currentSrc.includes('greyheart.png')) {
                element.src = '{{ url('/images/heart.jpeg') }}';
            } else {
                element.src = '{{ url('/images/greyheart.png') }}';
            }
        }

        // クリックイベントで画像の切り替えを実行
        document.getElementById('heartImage').addEventListener('click', toggleImage);

// クリックイベントで画像の切り替えを実行
    // 画像の状態をトグルする関数
    function toggleImage(element, shopId) {
        var currentSrc = element.src;
        var newSrc = currentSrc.includes('greyheart.png') ? '{{ url('/images/heart.jpeg') }}' : '{{ url('/images/greyheart.png') }}';

        element.src = newSrc;

        // お気に入りのトグル処理を呼び出す
        $.ajax({
            type: 'POST',
            url: '{{ route('shop.toggle-favorite', ['shopId' => '__SHOP_ID__']) }}'.replace('__SHOP_ID__', shopId),
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
    </script>

</body>
</html>

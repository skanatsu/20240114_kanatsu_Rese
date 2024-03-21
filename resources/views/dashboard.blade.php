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

@if (Auth::check() && Auth::user()->type == 'manage')


<div class="csv">
    <form action="{{ route('shops.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <p class="csv__title">【店舗情報のアップロード】</p>
        <input type="file" name="csv_file" accept=".csv" class="csv__select" id="fileInput" onchange="handleFileSelect()">
        <button type="submit" class="csv__import" id="importButton" style="display: none;">CSVをインポート</button>
        {{-- <span id="fileUploadedMessage">{{ $a ?? 0 }}</span> --}}
    </form>
</div>


@endif

@if ($errors->any())
<div class="error-messages">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

        @if (Auth::check() && Auth::user()->type == 'general')

<div class="sort">
    <button class="sort__button" onclick="toggleSelect()">並び替え：評価/低</button>


        <select id="sort" size="3" class="sort__select  custom-select" name="sort" onchange="handleSortChange()" style = 'display:none'>
            <option value="ランダム">ランダム</option>
            <option value="評価が高い順">評価が高い順</option>
            <option value="評価が低い順">評価が低い順</option>
        </select>


</div>
        @endif



        <div class="search">



            <select id="area" class="search__select" name="area" onchange="filterShops()">
                <option value="allarea">All area</option>
                <option value="東京都">東京都</option>
                <option value="大阪府">大阪府</option>
                <option value="福岡県">福岡県</option>
            </select>
            <p class="bar">|</p>
            <select id="genre" class="search__select" name="genre" onchange="filterShops()">
                <option value="allgenre">All genre</option>
                <option value="居酒屋">居酒屋</option>
                <option value="焼肉">焼肉</option>
                <option value="寿司">寿司</option>
                <option value="ラーメン">ラーメン</option>
                <option value="イタリアン">イタリアン</option>
            </select>
            <p class="bar">|</p>
            <input type="text" id="shopname" class="search__shopname" name="shopname" placeholder="Search..."
                onkeydown="searchOnEnter(event)">
        </div>
    </header>

    <div class="shops-container">
        @foreach ($shops as $shop)

        @php
            // $shopsWithAverageScoreから該当する店舗の平均スコアを取得
            $averageScore = $shopsWithAverageScore->firstWhere('id', $shop->id)->average_score ?? 0;
        @endphp

            <div class="shop" data-area="{{ $shop->area }}" data-genre="{{ $shop->genre }}"
                data-shopname="{{ $shop->shopname }}">
                <img src="{{ asset($shop->image_url) }}" class="shop__image" alt="{{ $shop->shopname }}">
                <p class="shop__name">{{ $shop->shopname }}</p>

                <p class="shop__average-score">{{ $averageScore }}</p> <!-- 平均スコアを表示 -->

                <div class="shop__tag">
                    <p class="shop__area">#{{ $shop->area }}</p>
                    <p>#{{ $shop->genre }}</p>
                </div>
                <a href="{{ route('detail', ['id' => $shop->id]) }}" class="shop__detail">詳しくみる</a>
                @auth
                    <img src="{{ url('/images/' . ($shop->isFavorite ? 'heart.png' : 'greyheart.png')) }}" alt=""
                        class="heart" onclick="toggleImage(this, {{ $shop->id }})"
                        id="heartImage_{{ $loop->index }}">
                @endauth
            </div>
        @endforeach
    </div>

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

        function toggleImage(element) {
            var currentSrc = element.src;
            if (currentSrc.includes('greyheart.png')) {
                element.src = '{{ url('/images/heart.png') }}';
            } else {
                element.src = '{{ url('/images/greyheart.png') }}';
            }
        }

        document.getElementById('heartImage').addEventListener('click', toggleImage);

        function toggleImage(element, shopId) {
            var currentSrc = element.src;
            var newSrc = currentSrc.includes('greyheart.png') ? '{{ url('/images/heart.png') }}' :
                '{{ url('/images/greyheart.png') }}';
            element.src = newSrc;

            $.ajax({
                type: 'POST',
                url: '{{ route('shop.toggle-favorite', ['shopId' => '__SHOP_ID__']) }}'.replace('__SHOP_ID__',
                    shopId),
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

function shuffleShops() {
    var shopsContainer = document.querySelector('.shops-container');
    var shops = Array.from(shopsContainer.querySelectorAll('.shop'));
    var randomShops = shops.sort(() => Math.random() - 0.5); // ランダムにシャッフル

    // シャッフルされた店舗をDOMに追加
    shopsContainer.innerHTML = '';
    randomShops.forEach(shop => {
        shopsContainer.appendChild(shop);
    });
}

function handleSortChange() {
    var selectedValue = document.getElementById('sort').value;
    if (selectedValue === 'ランダム') {
        shuffleShops();
    } else if (selectedValue === '評価が高い順') {
        // 平均評価で降順に並べ替え
        var shops = Array.from(document.querySelectorAll('.shop'));
        shops.sort((a, b) => {
            var averageScoreA = parseFloat(a.querySelector('.shop__average-score').textContent);
            var averageScoreB = parseFloat(b.querySelector('.shop__average-score').textContent);
            return averageScoreB - averageScoreA;
        });

        // 並び替え後のショップをDOMに追加
        var shopsContainer = document.querySelector('.shops-container');
        shopsContainer.innerHTML = '';
        shops.forEach(shop => {
            shopsContainer.appendChild(shop);
        });
    } else if (selectedValue === '評価が低い順') {
         // 平均評価で昇順に並べ替え
        var shops = Array.from(document.querySelectorAll('.shop'));
        shops.sort((a, b) => {
            var averageScoreA = parseFloat(a.querySelector('.shop__average-score').textContent);
            var averageScoreB = parseFloat(b.querySelector('.shop__average-score').textContent);
            // 平均評価が0の場合、評価を6として扱う
            if (averageScoreA === 0) {
                averageScoreA = 6;
            }
            if (averageScoreB === 0) {
                averageScoreB = 6;
            }
            return averageScoreA - averageScoreB;
        });


        // 並び替え後のショップをDOMに追加
        var shopsContainer = document.querySelector('.shops-container');
        shopsContainer.innerHTML = '';
        shops.forEach(shop => {
            shopsContainer.appendChild(shop);
        });
    }
}

// ページ読み込み時にはランダムシャッフルを行わないため、DOMContentLoadedイベントリスナーを使用します
document.addEventListener('DOMContentLoaded', function() {
    // ランダムオプションが選択されたときにシャッフル
    document.getElementById('sort').addEventListener('change', handleSortChange);
});


    function toggleSelect() {
        var select = document.getElementById("sort");
        select.style.display = select.style.display === "none" ? "block" : "none";
    }



// let a = 0;

    // function handleFileSelect() {
    //     var fileInput = document.getElementById('fileInput');
    //     var fileUploadedMessage = document.getElementById('fileUploadedMessage');
    //     if (fileInput.files.length > 0) {
    //         fileUploadedMessage.textContent = '1'; // ファイルが選択されたら1を表示
    //     } else {
    //         fileUploadedMessage.textContent = '0'; // ファイルが選択されていない場合は0を表示
    //     }
    // }


    // <input type="file" name="csv_file" accept=".csv" class="csv__select" id="fileInput" onchange="handleFileSelect()">
    //     <button type="submit" class="csv__import" id="importButton" style="display: none;">CSVをインポート</button>

        function handleFileSelect() {
        var fileInput = document.getElementById('fileInput');
        var importButton = document.getElementById('importButton');
        if (fileInput.files.length > 0) {
            // ファイルが選択されたらボタンを表示
            importButton.style.display = 'block';
        } else {
            // ファイルが選択されていない場合はボタンを非表示
            importButton.style.display = 'none';
        }
    }

</script>

</body>

</html>
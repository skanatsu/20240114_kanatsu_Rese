<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>打刻ページ</title>
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
        <div class="message">
            {{ Auth::user()->name }}さんお疲れ様です！
        </div>
        <div class="punch">
            <div class="punch__button">
                <div class="punch__button-1">
                    <form method="POST" action="/attendance">
                        @csrf
                        <input type="hidden" name="type" value="start">
                        <button id="start" class="punch__start" type="submit"
                            {{ isset($existingStatus) && $existingStatus === 'working' ? 'disabled' : '' }}>勤務開始</button>
                    </form>
                    <form method="POST" action="/attendance">
                        @csrf
                        <input type="hidden" name="type" value="end">
                        <button id="end" class="punch__end" type="submit"
                            {{ isset($existingStatus) && ($existingStatus === 'working' || $existingStatus === 'breaking') ? 'disabled' : '' }}>勤務終了</button>
                    </form>
                </div>
                <div class="punch__button-2">
                    <form method="POST" action="/attendance">
                        @csrf
                        <input type="hidden" name="type" value="break_start">
                        <button id="break_start" class="punch__break_start" type="submit"
                            {{ isset($existingStatus) && $existingStatus === 'breaking' ? 'disabled' : '' }}>休憩開始</button>
                    </form>
                    <form method="POST" action="/attendance">
                        @csrf
                        <input type="hidden" name="type" value="break_end">
                        <button id="break_end" class="punch__break_end" type="submit"
                            {{ isset($existingStatus) && ($existingStatus === 'working' || $existingStatus === 'breaking') ? '' : 'disabled' }}>休憩終了</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <small>
            <p class="footer__copyright">
                Atte,Inc.
            </p>
        </small>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <div id="lastStatus" data-status="{{ auth()->user()->last_status }}"></div>
    <script src="{{ mix('/resources/js/dashboard.js') }}"></script>
    <script>
        let formSubmitting = false;
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('form').addEventListener('submit', function(e) {
                if (formSubmitting) {
                    return;
                }
                e.preventDefault();
                const formData = new FormData(this);
                const action = this.getAttribute('action');
                const options = {
                    method: 'POST',
                    body: formData,
                };
                formSubmitting = true;
                fetch(action, options).then(response => {
                    if (response.ok) {
                        console.log('Success:', response);
                    } else {
                        console.log('Error:', response);
                    }
                    formSubmitting = false;
                });
            });
            $('.punch__button button').click(function(e) {
                e.preventDefault();
                var type = $(this).siblings('input').val();
                var formData = new FormData(this.form);
                $.ajax({
                    type: 'POST',
                    url: this.form.action,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            });
        });
        $(document).ready(function() {
            var lastStatus = "{{ auth()->user()->last_status }}";
            if (lastStatus === 'OFF') {
                $('#end, #break_start, #break_end').prop('disabled', true).css('color', 'lightgrey');
                $('#start').prop('disabled', false).css('color', 'black');
            } else if (lastStatus === 'working') {
                $('#start, #break_end').prop('disabled', true).css('color', 'lightgrey');
                $('#end, #break_start').prop('disabled', false).css('color', 'black');
            } else if (lastStatus === 'breaking') {
                $('#break_start, #start, #end').prop('disabled', true).css('color', 'lightgrey');
                $('#break_end').prop('disabled', false).css('color', 'black');
            }
            $('#start').on('click', function() {
                $('#start, #break_end').prop('disabled', true).css('color', 'lightgrey');
                $('#end, #break_start').prop('disabled', false).css('color', 'black');
            });
            $('#end').on('click', function() {
                $('#start, #end, #break_start, #break_end').prop('disabled', true).css('color',
                'lightgrey');
                $('#start').prop('disabled', false).css('color', 'black');
            });
            $('#break_start').on('click', function() {
                $('#break_start, #start, #end').prop('disabled', true).css('color', 'lightgrey');
                $('#break_end').prop('disabled', false).css('color', 'black');
            });
            $('#break_end').on('click', function() {
                $('#break_end, #start').prop('disabled', true).css('color', 'lightgrey');
                $('#break_start, #end').prop('disabled', false).css('color', 'black');
            });
        });
    </script>
</body>
</html>

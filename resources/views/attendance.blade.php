<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/attendance.css">
    <title>日付一覧</title>
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
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="calendar-pagination">
                                <a href="{{ route('attendance.index', ['date' => $prevDate]) }}">＜</a>
                                {{ $currentDate }}
                                <a href="{{ route('attendance.index', ['date' => $nextDate]) }}">＞</a>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>名前</th>
                                        <th>勤務開始</th>
                                        <th>勤務終了</th>
                                        <th>休憩時間</th>
                                        <th>勤務時間</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances->chunk(5) as $chunk)
                                        @foreach ($chunk as $attendance)
                                            <tr>
                                                <td>{{ $attendance->user ? $attendance->user->name : 'No User' }}</td>
                                                <td>{{ $attendance->start_time ? $attendance->start_time->format('H:i:s') : 'No Start Time' }}
                                                </td>
                                                <td>{{ $attendance->end_time ? $attendance->end_time->format('H:i:s') : 'No End Time' }}
                                                </td>
                                                <td>{{ gmdate('H:i:s', $attendance->break_time) }}</td>
                                                <td>{{ gmdate('H:i:s', $attendance->working_time) }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="panel-footer">
                                {{ $attendances->appends(['date' => $currentDate])->links('vendor.pagination.tailwind2') }}
                            </div>
                        </div>
                    </div>
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
</body>
</html>

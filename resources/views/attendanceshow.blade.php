<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/attendanceshow.css') }}">
    <title>{{ $user->name }}さんの勤怠</title>
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
                        <div class="panel-heading">
                            <h3>{{ $user->name }}さんの勤怠</h3>
                            <div class="calendar-pagination">
                                @if ($prevDate->format('Ym') !== $currentDate->format('Ym'))
                                    <a href="{{ $prevDateUrl }}">＜</a>
                                @endif
                                {{ $currentDate->format('Y年m月') }}
                                @if ($nextMonthFirstDay->format('Ym') !== $currentDate->format('Ym'))
                                    <a href="{{ $nextDateUrl }}">＞</a>
                                @endif
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>日付</th>
                                        <th>勤務開始</th>
                                        <th>勤務終了</th>
                                        <th>休憩時間</th>
                                        <th>勤務時間</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances as $attendance)
                                        @if ($attendance->start_time && $attendance->end_time)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($attendance->punch_time)->format('Y-m-d') }}
                                                </td>
                                                <td>{{ $attendance->start_time->format('H:i:s') }}</td>
                                                <td>{{ $attendance->end_time->format('H:i:s') }}</td>
                                                <td>{{ gmdate('H:i:s', $attendance->break_time ?? 0) }}</td>
                                                <td>{{ gmdate('H:i:s', $attendance->working_time ?? 0) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="panel-footer">
                                {{ $attendances->appends(['date' => $currentDate])->onEachSide(1)->links('vendor.pagination.tailwind2') }}
                            </div>
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

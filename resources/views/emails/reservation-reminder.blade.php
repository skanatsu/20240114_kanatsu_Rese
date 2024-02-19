<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>予約リマインダー</title>
</head>
<body>
    <p>予約リマインダー</p>
    <p>以下の予約についてお知らせします:</p>
    <ul>
        <li>店舗名: {{ $reservation->shop->shopname }}</li>
        <li>予約日時: {{ $reservation->date }} {{ $reservation->time }}</li>
        <li>人数: {{ $reservation->number }}人</li>
    </ul>
</body>
</html>

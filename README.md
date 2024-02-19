# Rese
Reseは企業のグループ会社の飲食店予約サービスです。
![](./img/home.png)

## 作成した目的
- 外部の飲食店予約サービスは手数料を取られるので、自社で予約サービスを持つことを目的としています。
- 初年度の利用者数10,000人達成が目標です。

## アプリケーションURL
### ページ一覧
- 会員登録ページ：<http://localhost/register>
- サンクスページ：<http://localhost/thanks>
- ログインページ：<http://localhost/login>
- マイページ：<http://localhost/mypage>
- 飲食店一覧ページ：<http://localhost/>
- 飲食店詳細ページ：<http://localhost/detail/1>
- 予約完了ページ：<http://localhost/done>
- メニューページ：<http://localhost/menu>

### 注意事項
ログインしていない状態では、飲食店の予約やお気に入り登録、評価ができません。それらはログインした後に行ってください。

## 他のレポジトリ
なし

## 機能一覧
### 会員登録機能
名前、メールアドレス、ログイン用パスワードを入力し、登録ボタンを押すと、メールアドレス宛に認証用メールが届きます。
そのメール内のリンクを押下することで、会員登録が完了します。（リンクをクリックしていなくても、ログイン自体は可能です。ただし、お店の予約や、マイページの閲覧はできません）
![](./img/register.png)

### ログイン機能
メールアドレスとパスワードを入力し、ログインボタンを押すことで、ログインができます。
![](./img/login.png)

### ログアウト機能
- メニューページの「Logout」を押すと、ログアウトできます。
![](./img/home.png)


### メニュー機能
画面左上のアイコンを押下すると、メニュー画面が表示されます。
- 未ログインの時
  - Home（飲食店一覧ページ）、会員登録ページ、ログインページに遷移できます。
- ログイン済みの時
  - Home（飲食店一覧ページ）、マイページに遷移できます。
  - 「Logout」を押下するとログアウトし、飲食店一覧ページに戻ります。

![](./img/home.png)

### 飲食店一覧表示機能
- 飲食店一覧ページでは、各店の概要が表示されます。
- 飲食店一覧ページの右上のフォームから、エリア・ジャンル・店名による絞り込みができます（AND検索）。
- 「詳しくみる」ボタンを押下すると、店舗詳細ページに遷移します。
- ログイン済みの場合、各店ごとにハートマークが表示されます。押下するごとに、赤色・灰色に変化します。
  - 赤色がお気に入り登録済み、灰色が未登録の状態です。
  - お身に入り登録済み店舗は、マイページに表示されます。
![](./img/attendance.png)

### 飲食店詳細表示機能
- 飲食店詳細ページでは、各店の詳細が表示されます。
- 評価が送られた店舗には、「お客様の声」欄に評価（5段階評価のスコア、コメント）が表示されます。
- ログイン済みの場合、予約フォームが表示されます。日時や人数を選択し、「予約する」ボタンを押下すると、予約できます（当日予約は店舗が対応しきれない可能性があるため、翌日以降の日時しか予約できません）。
![](./img/userlist.png)

### 予約情報の表示・削除・更新機能
- マイページでは、予約内容が表示されます。
- 「▼予約変更はこちら」欄にて、変更したい日時・人数を選択し、「予約変更」ボタンを押下すると、予約情報が更新できます。
- バツボタンを押下すると、予約が削除されます。

### 評価機能
- マイページでは、予約日時の過ぎた（来店済みの）店舗に対し、評価を送ることができます。
  - 「評価を送る」ボタンを押下すると、評価の入力フォームが表示されます。
  - 評価スコアを選択、評価コメントを入力し、「評価送信」ボタンを押下すると、評価が送信されます。
  - 1つの予約につき、1つの評価が送信できます。
    - 同じ店舗でも、予約ごとに評価を送信できます。
    - 同じ予約に対し、評価を複数回送信すると、送信済みの評価は上書きされます。
    - 送信した評価を取り消すことはできません。

### お気に入り店舗表示機能
- マイページでは、お気に入り店舗が表示されます。
- 「詳しくみる」ボタンを押下すると、店舗詳細ページに遷移します。
- ハートマークを押下すると、お気に入りが解除され、「お気に入り店舗」欄から削除されます。

## 使用技術（実行環境）
- フロントエンド
  - HTML
  - CSS
  - JavaScript
- バックエンド
  - PHP 8.2.13
  - Laravel 10.35.0
  - MySQL 8.2.0
- インフラ
  - Docker（開発環境）
- その他
  - GitHub

## テーブル設計
### usersテーブル
![](./img/usertable.png)

### shopsテーブル
![](./img/attendancetable.png)

### favoritesテーブル
![](./img/attendancetable.png)

### reservationsテーブル
![](./img/attendancetable.png)

### reviewsテーブル
![](./img/attendancetable.png)


## ER図
![](./img/er.png)

## 環境構築
- ローカル環境にてサーバーを立ち上げるには、下記コマンドを入力してください。
  - ./vendor/bin/sail up
- その後、JavaScriptを有効にするため、下記コードも入力してください。
  - npm run dev

## 他
### 作成済みのテスト用ユーザーデータ
パスワードはいずれも「twiligor」。
- Shimpei Kanatsu sk7@gmail.com
- 5work9 5work9@gmail.com
- magya6	magya6@tapi.re
- yuhi175	yuhi175@tapi.re
- 山田　太郎	hahihepu@instaddr.ch
- 鈴木花子	byoryowa153@fuwamofu.com


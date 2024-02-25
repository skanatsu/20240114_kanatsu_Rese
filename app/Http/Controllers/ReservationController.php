<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReservationRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Support\Facades\Log; // 追加
use Stripe\Exception\ApiErrorException; // 追加
use Stripe\Stripe; // 追加
use Stripe\PaymentIntent; // 追加
use Illuminate\Support\Facades\Redirect;
use Stripe\Webhook;

class ReservationController extends Controller
{
    public function done(): View
    {
        return view('done');
    }

 


    public function store(ReservationRequest $request, $shopId)
    {
        // バリデーションルールを通過した場合
        $validatedData = $request->validated();

        // 過去の日時での予約かどうかを確認
        $date = $validatedData['date'];
        $isPastDate = strtotime($date) < strtotime('today');

        // ログイン中のユーザーIDを取得
        $userId = Auth::id();

        // リクエストからデータを取得
        $time = $request->input('time');
        $number = $request->input('people');

        // Reservationテーブルにデータを挿入
        Reservation::create([
            'user_id' => $userId,
            'shop_id' => $shopId,
            'date' => $date,
            'time' => $time,
            'number' => $number,
        ]);


        // try {
        //     // Stripeの支払い処理
        //     Stripe::setApiKey(env('STRIPE_SECRET'));

        //     $paymentIntent = PaymentIntent::create([
        //         'amount' => 0, // 支払い金額（センツ単位）
        //         'currency' => 'JPY', // 通貨
        //     ]);

        //     // 支払いが成功した場合の処理
        //     if ($paymentIntent->status === 'succeeded') {
        //         // 支払い成功の処理をここに記述する
        //         return redirect('http://localhost/done');
        //     } else {
        //         // 支払い失敗の処理をここに記述する
        //         return redirect()->back()->with('error', '支払いに失敗しました。');
        //     }
        // } catch (ApiErrorException $e) {
        //     Log::error('Stripe payment error: ' . $e->getMessage());
        //     return redirect()->back()->with('error', '支払い処理中にエラーが発生しました。');
        // }


        // // 予約が完了したら指定のURLにリダイレクト
        // return redirect('http://localhost/done');

        try {
            // Stripeの支払い処理
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $paymentIntent = PaymentIntent::create([
                'amount' => 0, // 支払い金額（センツ単位）
                'currency' => 'JPY', // 通貨
            ]);

            // 予約データを保存
            // Reservation::create($reservationData);

            // 支払いが成功しているかどうかに関わらず、Doneページにリダイレクト
            return redirect('http://localhost/done');
        } catch (ApiErrorException $e) {
            Log::error('Stripe payment error: ' . $e->getMessage());

            // Stripeの支払い処理でエラーが発生した場合もDoneページにリダイレクト
            return redirect('http://localhost/done');
        }
    }


   public function deleteReservations(Request $request)
    {
        Reservation::find($request->id)->delete();
        // return redirect('mypage');
        return redirect()->route('mypage.show');
    }



    // public function reservationDetail($id)
    // {
    //     $reservation = Reservation::find($id);

    //     if (!$reservation) {
    //         // 予約が見つからない場合の処理をここに追加する（例: エラーページ表示）
    //         abort(404, 'Reservation not found');
    //     }

    //     // 予約詳細ページに予約データを渡してビューを表示
    //     return view('reservation.detail', compact('reservation'));
    // }

    // public function showUpdateForm($id)
    // {
    //     $reservation = Reservation::find($id);
    //     return view('reservation.update', compact('reservation'));　基本設計書作成中に不要と判明し消した
    // }

    // 予約変更処理
    public function update(ReservationRequest $request, $id)
    {
        $reservation = Reservation::find($id);
        $reservation->date = $request->input('date');
        $reservation->time = $request->input('time');
        $reservation->number = $request->input('people');
        $reservation->save();

        return redirect()->route('mypage.show'); // マイページにリダイレクトするか、適切なルートに変更
    }
    
    // 予約削除処理
    public function delete($id)
    {
        $reservation = Reservation::find($id);
        $reservation->delete();

        return redirect()->route('mypage.show'); // マイページにリダイレクトするか、適切なルートに変更
    }

    public function generateQrCode($reservation_id)
    {
        // 予約IDを使ってQRコードを生成
        $qrCode = QrCode::size(200)->generate($reservation_id);

        // 生成したQRコードのBase64エンコードを返す
        return response()->json(['qr_code' => $qrCode]);
    }

    // 決済ページにリダイレクトするメソッド
    public function pay($id)
    {
        // Stripeの決済ページURLを返す（例：https://buy.stripe.com/test_00g8wSbg0gU862IcMM）
        return Redirect::away('https://buy.stripe.com/test_00g8wSbg0gU862IcMM');
    }

    // public function paymentCallback(Request $request)
    // {
    //     // StripeのAPIキーをセットアップします
    //     Stripe::setApiKey(env('STRIPE_SECRET_KEY')); // 環境変数からStripeのシークレットキーを取得する例

    //     // Stripeからのリクエストを取得します
    //     $payload = @file_get_contents('php://input');

    //     // リクエストが空の場合やStripeのシグネチャが不正な場合はエラーを返します
    //     if (empty($payload)) {
    //         return response('Request is empty.', 400);
    //     }

    //     $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
    //     $event = null;

    //     try {
    //         // Stripeのシグネチャを検証してイベントオブジェクトを取得します
    //         $event = Webhook::constructEvent(
    //             $payload,
    //             $sig_header,
    //             env('STRIPE_WEBHOOK_SECRET') // 環境変数からStripeのWebhookシークレットを取得する例
    //         );
    //     } catch (\UnexpectedValueException $e) {
    //         // シグネチャが不正な場合はエラーを返します
    //         return response('Invalid signature.', 400);
    //     }

    //     // イベントタイプが支払いに関連するものであることを確認します
    //     if ($event->type === 'payment_intent.succeeded') {
    //         $paymentIntent = $event->data->object;
    //         // 支払いが成功した場合の処理を行います
    //         // 例えば、データベースに支払い情報を保存したり、ユーザーに通知を送ったりします
    //         // このコールバックメソッドはStripeのWebhookとして設定されているため、即時にレスポンスを返す必要があります
    //         return response('Payment completed successfully!', 200);
    //     }

    //     // 支払い以外のイベントには何もしないで処理を終了します
    //     return response('Unhandled event type.', 200);
    // }
}
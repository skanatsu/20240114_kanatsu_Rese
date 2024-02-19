<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReservationRequest;

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

        // if ($isPastDate) {
        //     // 過去の日時の場合はエラーメッセージをフラッシュしてリダイレクト
        //     return redirect()->route('detail',
        //         ['id' => $shopId]
        //     )->with('error', '過去の日時での予約はできません。');
        // }

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

        // 予約が完了したら指定のURLにリダイレクト
        return redirect('http://localhost/done');
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
}
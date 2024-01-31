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
        return redirect('mypage');
    }
}
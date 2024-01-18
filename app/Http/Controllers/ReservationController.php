<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function done(): View
    {
        return view('done');
    }

    public function store(Request $request, $shopId)
    {
        // ログイン中のユーザーIDを取得
        $userId = Auth::id();

        // リクエストからデータを取得
        $date = $request->input('date');
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

    public function showReservationStatus()
    {
        $userId = Auth::id();

        // 全ての予約情報を取得
        $reservations = Reservation::all();

        // すべての予約情報をビューに渡す
        return view('mypage', compact('reservations'));
    }

    public function deleteReservations(Request $request)
    {
        Reservation::find($request->id)->delete();
        return redirect('mypage');
    }
}
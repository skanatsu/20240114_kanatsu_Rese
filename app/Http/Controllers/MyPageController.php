<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Favorite;

class MyPageController extends Controller
{
    public function show()
    {
        $userId = Auth::id();

        // 予約情報を取得
        $reservations = Reservation::where('user_id', $userId)->get();

        // お気に入り店舗情報を取得
        $favoriteShops = Favorite::where('user_id', $userId)->get();

        return view('mypage', compact('reservations', 'favoriteShops'));
    }

    public function deleteReservation(Request $request)
    {
        Reservation::find($request->id)->delete();
        return redirect('mypage');
    }

    public function toggleFavorite($shopId)
    {
        // 現在のユーザー
        $user = Auth::user();

        // 対象のお気に入り情報を取得
        $favorite = Favorite::where('user_id', $user->id)
            ->where('shop_id', $shopId)
            ->first();

        // お気に入りが存在する場合は削除、存在しない場合は追加
        if ($favorite) {
            $favorite->delete();
        } else {
            // お気に入りを新規作成
            Favorite::create([
                'user_id' => $user->id,
                'shop_id' => $shopId,
            ]);
        }

        return redirect()->route('mypage');
    }

}
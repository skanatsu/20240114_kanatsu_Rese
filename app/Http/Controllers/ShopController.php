<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Reservation;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function index()
    {
        // $shops = Shop::all();
        // return view('dashboard', ['shops' => $shops]);
        $user = Auth::user();
        $shops = Shop::with('favorites')->get();

        return view('dashboard', compact('shops', 'user'));
    }

    public function show($id)
    {
        $shop = Shop::findOrFail($id);

        // 店舗に関連する評価データを取得
        // $reviews = Review::where('shop_id', $id)->get();
        // $reviews = Review::where('reservation_id', $id)->get();

        // return view('detail', compact('shop', 'reviews'));
        // 予約データから該当のショップIDに関連する予約IDを取得
        $reservationIds = Reservation::where('shop_id', $id)->pluck('id');

        // 予約IDを使って関連する評価データを取得
        $reviews = Review::whereIn('reservation_id', $reservationIds)->get();

        return view('detail', compact('shop', 'reviews'));
    }

    public function toggleFavorite($shopId)
    {
        // 現在のユーザー
        $user = Auth::user();

        // お気に入り情報を検索
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

        return redirect()->route('dashboard');
    }

    // public function getShopImage($filename)
    // {
    //     // 追加: 画像ファイルの保存場所を`public`ディスクに変更
    //     $path = Storage::disk('public')->path($filename);

    //     // 変更: ファイルが存在しない場合は404エラーを返す
    //     if (!file_exists($path)) {
    //         abort(404);
    //     }

    //     // 同じ: 画像ファイルが存在する場合は画像ファイルの内容を返す
    //     return response()->file($path);
    // }

    // public function showImage()
    // {
    //     // 画像ファイルの内容を取得
    //     $file = Storage::disk('public')->get('images/attendance.png');

    //     // 画像ファイルの内容を返す
    //     return response($file)->header('Content-Type', 'image/png');
    // }
}
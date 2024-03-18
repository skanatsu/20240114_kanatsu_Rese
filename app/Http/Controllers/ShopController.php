<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shops = Shop::with('favorites')->get();

        // 各店舗のレビューの平均スコアを取得
        $shopsWithAverageScore = Shop::with('favorites')
        ->select('shops.*', DB::raw('COALESCE(AVG(reviews.score), 0) as average_score'))
        ->leftJoin('reviews', 'shops.id', '=', 'reviews.shop_id')
        ->groupBy('shops.id')
        ->get();


        return view('dashboard', compact('shops', 'user', 'shopsWithAverageScore'));
    }

    public function show($id)
    {
        $shop = Shop::findOrFail($id);
        $reservationIds = Reservation::where('shop_id', $id)->pluck('id');
        // $reviews = Review::whereIn('reservation_id', $reservationIds)->get();
        $reviews = Review::where('shop_id', $id)->get(); // ショップに関連するレビューを取得

        // return view('detail', compact('shop'));
        return view('detail', compact('shop', 'reviews')); // レビューもビューに渡す
    }

    public function toggleFavorite($shopId)
    {
        $user = Auth::user();
        $favorite = Favorite::where('user_id', $user->id)
            ->where('shop_id', $shopId)
            ->first();

        if ($favorite) {
            $favorite->delete();
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'shop_id' => $shopId,
            ]);
        }

        return redirect()->route('dashboard');
    }
}
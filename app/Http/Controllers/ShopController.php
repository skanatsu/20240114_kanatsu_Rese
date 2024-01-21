<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;


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
        return view('detail', ['shop' => $shop]);
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

    // public function getFavoriteShops()
    // {
    //     // ログイン中のユーザーIDを取得
    //     $userId = Auth::id();
    //     // ログイン中のユーザーのお気に入り店舗情報を取得
    //     // $favoriteShops = auth()->user()->favorites;

    //     $favoriteShops = Favorite::where('user_id', $userId)->get();
    //     // 取得したお気に入り店舗情報をmypage.blade.phpに渡す
    //     // return view('mypage', ['favorite-shops' => $favoriteShops]);
    //     return view('mypage', compact('favoriteShops'));
    
    // }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Favorite;

class MyPageController extends Controller
{
    public function show()
    {
        $userId = Auth::id();
        $reservations = Reservation::where('user_id', $userId)->get();
        $favoriteShops = Favorite::where('user_id', $userId)->get();

        return view('mypage', compact('reservations', 'favoriteShops'));
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
        return redirect()->route('mypage');
    }
}

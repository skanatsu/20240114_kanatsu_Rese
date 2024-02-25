<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Reservation;

class ShopController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shops = Shop::with('favorites')->get();

        return view('dashboard', compact('shops', 'user'));
    }

    public function show($id)
    {
        $shop = Shop::findOrFail($id);
        $reservationIds = Reservation::where('shop_id', $id)->pluck('id');
        $reviews = Review::whereIn('reservation_id', $reservationIds)->get();

        return view('detail', compact('shop', 'reviews'));
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

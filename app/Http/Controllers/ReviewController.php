<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Shop;

class ReviewController extends Controller
{
    public function evaluate(Request $request, $id)
    {
        $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:255',
        ]);

        $reservation = Reservation::find($id);

        if (!$reservation) {
            return redirect()->back()->with('error', '予約が見つかりませんでした');
        }

        $review = Review::where('reservation_id', $reservation->id)->first();

        if ($review) {
            $review->score = $request->input('score');
            $review->comment = $request->input('comment');
            $review->save();
        } else {
            $review = new Review([
                'reservation_id' => $reservation->id,
                'score' => $request->input('score'),
                'comment' => $request->input('comment'),
            ]);
            $review->save();
        }

        return redirect()->back()->with('success', '評価が保存されました');
    }

    public function show($id)
    {
        $shop = Shop::findOrFail($id); // IDに基づいてShopモデルのインスタンスを取得

        return view('review', ['shop' => $shop]); // $detailをビューに渡す
    }
}
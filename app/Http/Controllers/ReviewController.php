<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Review;

class ReviewController extends Controller
{
    // public function evaluate(Request $request, $id)
    // {
    //     $request->validate([
    //         'score' => 'required|integer|min:1|max:5',
    //         'comment' => 'required|string|max:255',
    //     ]);

    //     $reservation = Reservation::find($id);
        

    //     if (!$reservation) {
    //         // 予約が存在しない場合の処理を追加する
    //         return redirect()->back()->with('error', '予約が見つかりませんでした');
    //     }

    //     // ログイン中のユーザーに関連付けてレビューを作成
    //     $review = new Review([
    //         'user_id' => auth()->user()->id,
    //         'shop_id' => $reservation->shop_id,
    //         'reservation_id' => $reservation->id,
    //         'score' => $request->input('score'),
    //         'comment' => $request->input('comment'),
    //     ]);

    //     $review->save();

    //     return redirect()->back()->with('success', '評価が保存されました'); // 保存後に元のページにリダイレクト
    // }


    public function evaluate(Request $request, $id)
    {
        $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:255',
        ]);

        $reservation = Reservation::find($id);

        if (!$reservation) {
            // 予約が存在しない場合の処理を追加する
            return redirect()->back()->with('error', '予約が見つかりませんでした');
        }

        // 予約に関連するレビューを取得
        $review = Review::where('reservation_id', $reservation->id)->first();

        if ($review) {
            // レビューが既に存在する場合は内容を更新
            $review->score = $request->input('score');
            $review->comment = $request->input('comment');
            $review->save();
        } else {
            // レビューが存在しない場合は新しいレビューを作成
            $review = new Review([
                'user_id' => auth()->user()->id,
                'shop_id' => $reservation->shop_id,
                'reservation_id' => $reservation->id,
                'score' => $request->input('score'),
                'comment' => $request->input('comment'),
            ]);
            $review->save();
        }

        return redirect()->back()->with('success', '評価が保存されました');
    }
}
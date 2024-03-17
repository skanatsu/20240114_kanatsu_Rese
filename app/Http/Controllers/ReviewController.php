<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;

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

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 最大2MBの画像ファイル
        ]);

        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('public/images');
            $url = Storage::url($imagePath);

            // 画像のパスを保存する処理
            // 例えば、Reviewモデルにreview_image_urlというカラムがある場合：
            // $review = new Review();
            // $review->review_image_url = $url;
            // $review->save();

            return redirect()->back()->with('success', '写真がアップロードされました。');
        }

        return redirect()->back()->with('error', '写真のアップロードに失敗しました。');
    }

    public function submit(Request $request)
    {
        // バリデーションを行う
        $validatedData = $request->validate([
            'score' => 'required|integer|min:1|max:5', // scoreは必須で1から5の整数であることを検証
            'comment' => 'required|string|max:400', // commentは必須で400文字以内であることを検証
            'shop_id' => 'required|exists:shops,id', // shop_idは必須で、存在するshop_idであることを検証
        ]);

        // 口コミデータを作成して保存する
        $review = new Review();
        $review->user_id = auth()->id(); // ログインユーザーのIDを取得し、口コミのuser_idに設定
        $review->shop_id = $validatedData['shop_id']; // shop_idを設定
        $review->score = $validatedData['score']; // バリデーション済みのscoreを設定
        $review->comment = $validatedData['comment']; // バリデーション済みのcommentを設定
        $review->save(); // データベースに保存

        // 口コミが正常に保存された場合は成功レスポンスを返す
        // return response()->json(['success' => true, 'message' => '口コミが正常に投稿されました']);

        $shopId = $validatedData['shop_id'];
        $redirectUrl = route('detail', ['id' => $shopId]); // 'detail' ルートに shop_id を渡して遷移先のURLを構築
        return redirect($redirectUrl);
    }
}
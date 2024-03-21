<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
        $review = $this->fetchReview(new Request(['shop_id' => $id])); // レビュー情報を取得

        // return view('review', ['shop' => $shop]); // $detailをビューに渡す
        return view('review', ['shop' => $shop, 'review' => $review->original['review']]); // $shopと$reviewをビューに渡す

    }


    public function submit(Request $request)
    {
        // バリデーションを行う
        $validatedData = $request->validate([
            'score' => 'required|integer|min:1|max:5', // scoreは必須で1から5の整数であることを検証
            'comment' => 'required|string|max:400', // commentは必須で400文字以内であることを検証
            'shop_id' => 'required|exists:shops,id', // shop_idは必須で、存在するshop_idであることを検証
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 画像ファイルのバリデーションを追加
        ]);

        // 口コミデータを作成または更新する
        $review = Review::where('user_id', auth()->id())
            ->where('shop_id', $validatedData['shop_id'])
            ->first();

        if ($review) {
            // 既存の口コミがある場合は、口コミデータを更新
            $review->score = $validatedData['score'];
            $review->comment = $validatedData['comment'];
            // $review->review_image_url = $validatedData['review_image_url'];
            if (isset($validatedData['review_image_url'])) {
                $review->review_image_url = $validatedData['review_image_url'];
            }
            $review->save();
        } else {
            // 既存の口コミがない場合は、新しい口コミデータを作成
            $review = new Review();
            $review->user_id = auth()->id(); // ログインユーザーのIDを取得し、口コミのuser_idに設定
            $review->shop_id = $validatedData['shop_id']; // shop_idを設定
            $review->score = $validatedData['score']; // バリデーション済みのscoreを設定
            $review->comment = $validatedData['comment']; // バリデーション済みのcommentを設定
            if (isset($validatedData['review_image_url'])) {
                $review->review_image_url = $validatedData['review_image_url'];
            }
            $review->save(); // データベースに保存
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName(); // アップロードされたファイルのオリジナル名を取得
            $path = $file->store('public/images'); // ファイルを保存し、保存先のパスを取得
            $review->review_image_url = $fileName; // ファイル名をreview_image_urlに設定
            $review->save();
        }

        // $review->save(); // データベースに保存

        // リダイレクト先のURLを構築してリダイレクト
        $shopId = $validatedData['shop_id'];
        $redirectUrl = route('detail', ['id' => $shopId]); // 'detail' ルートに shop_id を渡して遷移先のURLを構築
        return redirect($redirectUrl);
    }


    public function fetchReview(Request $request)
    {
        // ユーザーIDとショップIDを取得
        $userId = auth()->id();
        $shopId = $request->input('shop_id');

        // ユーザーIDとショップIDに関連する口コミを取得
        $review = Review::where('user_id', $userId)
            ->where('shop_id', $shopId)
            ->first();

        return response()->json(['review' => $review]);
    }

    public function delete($id)
    {
        $review = Review::findOrFail($id);

        // ログイン中のユーザーが管理者であるか、口コミの所有者であるかを確認する必要があります。
        if (Auth::check() && Auth::user()->type == 'manage') {
            // 管理者の場合は条件を満たす必要がないので、直接削除処理を実行します。
            $review->delete();
        } elseif (Auth::check() && $review->user_id == Auth::id()) {
            // ログイン中のユーザーが口コミの所有者である場合のみ、口コミを削除します。
            $review->delete();
        } else {
            // 権限がない場合はリダイレクトし、エラーメッセージを表示します。
            return redirect()->back()->with('error', '口コミの削除権限がありません');
        }

        // 口コミが削除された後は、現在の detail ページにリダイレクトします。
        return redirect()->route('detail', ['id' => $review->shop_id]);
    }
}
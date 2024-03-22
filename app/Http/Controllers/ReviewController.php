<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{

    public function show($id)
    {
        $shop = Shop::findOrFail($id);
        $review = $this->fetchReview(new Request(['shop_id' => $id]));
        return view('review', ['shop' => $shop, 'review' => $review->original['review']]);
    }
    public function submit(Request $request)
    {
        $validatedData = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $review = Review::where('user_id', auth()->id())
            ->where('shop_id', $validatedData['shop_id'])
            ->first();

        if ($review) {
            if ($request->has('score')) {
                $review->score = $request->input('score');
            }

            if ($request->has('comment')) {
                $review->comment = $request->input('comment');
            }

            if ($request->has('review_image_url')) {
                $review->review_image_url = $request->input('review_image_url');
            }
            $review->save();
        } else {
            $review = new Review();
            $review->user_id = auth()->id();
            $review->shop_id = $validatedData['shop_id'];
            $review->score = $request->input('score', 1);
            $review->comment = $request->input('comment', '');
            $review->review_image_url = $request->input('review_image_url', '');
            $review->save();
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            $path = $file->store('public/images');
            $review->review_image_url = $fileName;
            $review->save();
        }

        $shopId = $validatedData['shop_id'];
        $redirectUrl = route('detail', ['id' => $shopId]);
        return redirect($redirectUrl);
    }

    public function fetchReview(Request $request)
    {
        $userId = auth()->id();
        $shopId = $request->input('shop_id');
        $review = Review::where('user_id', $userId)
            ->where('shop_id', $shopId)
            ->first();
        return response()->json(['review' => $review]);
    }

    public function delete($id)
    {
        $review = Review::findOrFail($id);
        if (Auth::check() && Auth::user()->type == 'manage') {
            $review->delete();
        } elseif (Auth::check() && $review->user_id == Auth::id()) {
            $review->delete();
        } else {
            return redirect()->back()->with('error', '口コミの削除権限がありません');
        }
        return redirect()->route('detail', ['id' => $review->shop_id]);
    }
}
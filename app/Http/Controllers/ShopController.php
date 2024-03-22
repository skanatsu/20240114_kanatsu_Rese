<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Database\Seeders\ShopCsvSeeder;
use App\Http\Requests\ImportCsvRequest;

class ShopController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shops = Shop::with('favorites')->get();
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
        $reviews = Review::where('shop_id', $id)->get();
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

    public function import(ImportCsvRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('csv_file');
        $fileName = 'shops.csv';
        Storage::putFileAs('temp', $file, $fileName);

        $seeder = new ShopCsvSeeder;
        $seeder->run();

        Storage::delete('temp/' . $fileName);

        return redirect()->route('dashboard')->with('success', 'CSVファイルが正常にインポートされました');
    }
}
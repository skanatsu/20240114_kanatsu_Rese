<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showReservationStatus(Request $request)
    {
        $user = Auth::user();
        $reservations = Reservation::where('user_id', $user->id)->get();


        logger($reservations);
        
        // 変数 $reservations を compact 関数でビューに渡す
        return view('mypage', compact('user', 'reservations'));
    }
}
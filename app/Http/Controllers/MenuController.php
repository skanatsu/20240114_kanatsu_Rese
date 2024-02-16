<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        // メニューページの処理
        return view('menu');
    }

    public function mypage()
    {
        return view('mypage');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class ReservationController extends Controller
{
    public function done(): View
    {
        return view('done');
    }
}
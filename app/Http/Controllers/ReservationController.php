<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReservationRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Redirect;

class ReservationController extends Controller
{
    public function done(): View
    {
        return view('done');
    }

    public function store(ReservationRequest $request, $shopId)
    {
        $validatedData = $request->validated();
        $date = $validatedData['date'];
        $isPastDate = strtotime($date) < strtotime('today');
        $userId = Auth::id();
        $time = $request->input('time');
        $number = $request->input('people');

        Reservation::create([
            'user_id' => $userId,
            'shop_id' => $shopId,
            'date' => $date,
            'time' => $time,
            'number' => $number,
        ]);

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $paymentIntent = PaymentIntent::create([
                'amount' => 0,
                'currency' => 'JPY',
            ]);
            return redirect('http://localhost/done');
        } catch (ApiErrorException $e) {
            Log::error('Stripe payment error: ' . $e->getMessage());
            return redirect('http://localhost/done');
        }
    }

    public function deleteReservations(Request $request)
    {
        Reservation::find($request->id)->delete();
        return redirect()->route('mypage.show');
    }
    public function update(ReservationRequest $request, $id)
    {
        $reservation = Reservation::find($id);
        $reservation->date = $request->input('date');
        $reservation->time = $request->input('time');
        $reservation->number = $request->input('people');
        $reservation->save();

        return redirect()->route('mypage.show');
    }

    public function delete($id)
    {
        $reservation = Reservation::find($id);
        $reservation->delete();

        return redirect()->route('mypage.show');
    }

    public function generateQrCode($reservation_id)
    {
        $qrCode = QrCode::size(200)->generate($reservation_id);

        return response()->json(['qr_code' => $qrCode]);
    }
    public function pay($id)
    {
        return Redirect::away('https://buy.stripe.com/test_00g8wSbg0gU862IcMM');
    }
}
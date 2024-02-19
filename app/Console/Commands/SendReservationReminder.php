<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Mail\ReservationReminder;
use Illuminate\Support\Facades\Mail;

class SendReservationReminder extends Command
{
    protected $signature = 'reminder:send';
    protected $description = 'Send reservation reminders to users';

    public function handle()
    {
        // 予約当日の予約を取得するクエリを実行
        $reservations = Reservation::whereDate('date', today())->get();

        // 取得した予約に対してリマインダーメールを送信
        foreach ($reservations as $reservation) {
            Mail::to($reservation->user->email)->send(new ReservationReminder($reservation));
        }

        $this->info('Reminder emails sent successfully!');
    }
}
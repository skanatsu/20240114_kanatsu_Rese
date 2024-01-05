<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class UpdateAttendance extends Command
{
    protected $signature = 'app:update-attendance';
    protected $description = 'Update attendance records for cross-day processing';

    public function handle()
    {
        // 現在の日時を取得
        $currentDateTime = now();

        // 昨日の日付と今日の日付を取得
        $yesterday = $currentDateTime->copy()->subDay();
        $today = $currentDateTime;

        // 昨日 working だった場合
        $yesterdayWorkingUsers = User::where('last_status', 'working')->get();

        foreach ($yesterdayWorkingUsers as $user) {
            // 昨日の23:59:59に type が 'end' の打刻
            $endAttendance = new Attendance;
            $endAttendance->user_id = $user->id; // 必要に応じてユーザーIDを設定
            $endAttendance->type = 'end';
            $endAttendance->punch_time = $yesterday->endOfDay();
            $endAttendance->save();

            // 今日の00:00:00に type が 'start' の打刻
            $startAttendance = new Attendance;
            $startAttendance->user_id = $user->id; // 必要に応じてユーザーIDを設定
            $startAttendance->type = 'start';
            $startAttendance->punch_time = $today->startOfDay();
            $startAttendance->save();
        }
    }
}

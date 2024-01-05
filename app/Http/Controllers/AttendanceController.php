<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\User;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Pagination\Paginator;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $user_id = $request->user()->id;
        $type = $request->type;
        $now = Carbon::now()->toDateTimeString();
        $latestRecord = Attendance::where('user_id', $user_id)
            ->orderByDesc('punch_time')
            ->first();
        $attendance = new Attendance;
        $attendance->user_id = $user_id;
        $attendance->type = $type;
        $attendance->punch_time = $now;
        $start_time = $latestRecord ? $latestRecord->start_time : null;
        $break_start_time = $latestRecord ? $latestRecord->break_start_time : null;
        $break_end_time = $latestRecord ? $latestRecord->break_end_time : null;
        $latest_break_time = $latestRecord ? $latestRecord->break_time : 0;
        if ($type == 'start') {
            $attendance->start_time = $now;
            $attendance->end_time = null;
            $attendance->break_start_time = null;
            $attendance->break_end_time = null;
            $attendance->break_time = 0;
            $attendance->working_time = 0;
            auth()->user()->update(['last_status' => 'working']);
        } elseif ($type == 'break_start') {
            $attendance->start_time = $start_time;
            $attendance->end_time = null;
            $attendance->break_start_time = $now;
            $attendance->break_end_time = null;
            $attendance->break_time = $latest_break_time;
            $attendance->working_time = 0;
            auth()->user()->update(['last_status' => 'breaking']);
        } elseif ($type == 'break_end') {
            $attendance->start_time = $start_time;
            $attendance->end_time = null;
            $attendance->break_start_time = $break_start_time;
            $attendance->break_end_time = $now;
            auth()->user()->update(['last_status' => 'working']);
            $break_time_diff = $break_start_time && $now ?
                Carbon::parse($now)->diffInSeconds(Carbon::parse($break_start_time)) : 0;
            $attendance->break_time = max(0, $latest_break_time + $break_time_diff);
            $attendance->working_time = 0;
        } elseif ($type == 'end') {
            $attendance->start_time = $start_time;
            $attendance->end_time = $now;
            $attendance->break_start_time = $break_start_time;
            $attendance->break_end_time = $break_end_time;
            auth()->user()->update(['last_status' => 'OFF']);
            $attendance->break_time = $latest_break_time;
            $working_time_diff = $start_time && $now ?
                Carbon::parse($now)->diffInSeconds(Carbon::parse($start_time)) - $attendance->break_time : 0;
            $attendance->working_time = max(0, $working_time_diff);
        }
        $attendance->save();
        return response()->json([
            'attendance' => $attendance,
            'workingTime' => $attendance->working_time,
            'breakTime' => $attendance->break_time,
            'startTime' => $attendance->start_time,
            'endTime' => $attendance->end_time,
        ]);
    }
    public function index(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $currentDate = Carbon::parse($date)->format('Y-m-d');
        $prevDate = Carbon::parse($date)->subDay()->format('Y-m-d');
        $nextDate = Carbon::parse($date)->addDay()->format('Y-m-d');
        $attendances = Attendance::with('user')
            ->whereNotNull('end_time')
            ->where('working_time', '>', 0)
            ->whereDate('punch_time', $date)
            ->orderBy('punch_time', 'desc')
            ->paginate(5);
        return view('attendance', [
            'attendances' => $attendances,
            'currentDate' => $currentDate,
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
        ]);
    }

    public function show(Request $request, User $user)
    {
        // ...
        $currentDate = now();
        $prevDate = $currentDate->copy()->subMonth();
        $nextDate = $currentDate->copy()->addMonth();
        if ($request->has('date')) {
            $currentDate = Carbon::parse($request->date)->startOfMonth();
            $prevDate = $currentDate->copy()->subMonth();
            $nextDate = $currentDate->copy()->addMonth();
        }
        $nextMonthFirstDay = $currentDate->copy()->addMonth()->startOfMonth();
        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('punch_time', $currentDate->month)
            ->whereNotNull('end_time')
            ->where('working_time', '>', 0)
            ->orderBy('punch_time', 'desc')
            ->paginate(5);
        return view('attendanceshow', [
            'attendances' => $attendances,
            'user' => $user,
            'currentDate' => $currentDate,
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
            'prevDateUrl' => route('attendanceshow', ['user' => $user->id, 'date' => $prevDate->format('Y-m')]),
            'nextDateUrl' => route('attendanceshow', ['user' => $user->id, 'date' => $nextDate->format('Y-m')]),
            'nextMonthFirstDay' => $nextMonthFirstDay,
        ]);
    }
}
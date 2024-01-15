<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ShopController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/', [ShopController::class, 'index'])->name('dashboard');

Route::get('detail/:shops_{id}', [ShopController::class, 'show'])->name('detail');

Route::post('/reservation/{shopId}', [ReservationController::class, 'store'])
    ->name('reservation.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/attendance', [AttendanceController::class, 'store']);

Route::get('/userlist', [RegisteredUserController::class, 'index']);
Route::post('/userlist', [RegisteredUserController::class, 'search']);

Route::get('/attendance/{user}', [AttendanceController::class, 'show'])->name('attendanceshow');

Route::get('/thanks', [RegisteredUserController::class, 'thanks'])->name('thanks');

// Route::get('/mypage', [RegisteredUserController::class, 'mypage'])->name('mypage');

Route::middleware(['auth'])->group(function () {
    Route::get('/mypage', [RegisteredUserController::class, 'mypage'])->name('mypage');
});

Route::get('/done', [ReservationController::class, 'done'])->name('done');


require __DIR__ . '/auth.php';
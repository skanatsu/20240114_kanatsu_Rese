<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\MyPageController;
use Illuminate\Support\Facades\Auth;

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


Route::get('/', [ShopController::class, 'index'])->name('dashboard');
// Route::get('/', [ShopController::class, 'index'])->name('/'); 

Route::get('detail/{id}', [ShopController::class, 'show'])->name('detail');  // 修正

Route::post('/reservation/{shopId}', [ReservationController::class, 'store'])
    ->name('reservation.store');


Auth::routes(['verify' => true]);

// Route::middleware('auth')->group(function () {
Route::middleware(['verified'])->group(function(){
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/mypage', [MyPageController::class, 'show'])->name('mypage.show');  // 修正
});

Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset.request');

Route::get('/thanks', [RegisteredUserController::class, 'thanks'])->name('thanks');


Route::get('/done', [ReservationController::class, 'done'])->name('done');

Route::delete('/reservation/delete', [ReservationController::class, 'deleteReservations']);



Route::post('/shop/toggle-favorite/{shopId}', [ShopController::class, 'toggleFavorite'])
    ->name('shop.toggle-favorite');

Route::post('/mypage/toggle-favorite/{shopId}', [MyPageController::class, 'toggleFavorite'])
    ->name('mypage.toggle-favorite');

require __DIR__ . '/auth.php';
Auth::routes();

Route::get('/thanks', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::prefix('reservation')->group(function () {
    Route::delete('/delete/{id}', [ReservationController::class, 'delete'])->name('reservation.delete');
    Route::get('/update/{id}', [ReservationController::class, 'showUpdateForm'])->name('reservation.update.form');
    Route::put('/update/{id}', [ReservationController::class, 'update'])->name('reservation.update');
});
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\MyPageController;

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


// Route::get('/', [ShopController::class, 'index'])->name('dashboard');
Route::get('/', [ShopController::class, 'index'])->name('/'); 

Route::get('detail/{id}', [ShopController::class, 'show'])->name('detail');  // 修正

Route::post('/reservation/{shopId}', [ReservationController::class, 'store'])
    ->name('reservation.store');

// Route::get('/mypage', [MypageController::class, 'showReservationStatus'])
//     ->middleware('auth')
//     ->name('mypage');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/mypage', [MyPageController::class, 'show'])->name('mypage');  // 修正
});

Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

// Route::get('/userlist', [RegisteredUserController::class, 'index']);
// Route::post('/userlist', [RegisteredUserController::class, 'search']);

Route::get('/thanks', [RegisteredUserController::class, 'thanks'])->name('thanks');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/mypage', [RegisteredUserController::class, 'mypage'])->name('mypage');
// });

Route::get('/done', [ReservationController::class, 'done'])->name('done');

Route::delete('/reservation/delete', [ReservationController::class, 'deleteReservations']);

Route::post('/shop/toggle-favorite/{shopId}', [ShopController::class, 'toggleFavorite'])
    ->name('shop.toggle-favorite');

Route::post('/mypage/toggle-favorite/{shopId}', [MyPageController::class, 'toggleFavorite'])
    ->name('mypage.toggle-favorite');


// Route::get('/favorite-shops', [ShopController::class, 'getFavoriteShops'])
//     ->middleware('auth')
//     ->name('favorite.shops');

// Route::get('/mypage', [ReservationController::class, 'showReservationStatus'])
//     ->middleware('auth')
//     ->name('mypage');

// Route::get('/mypage', [ShopController::class, 'getFavoriteShops'])
//     ->middleware('auth')
//     ->name('mypage');






require __DIR__ . '/auth.php';
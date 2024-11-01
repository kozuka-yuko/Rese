<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ReseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ShopRepController;
use Mockery\VerificationExpectation;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/shop', [ReseController::class, 'home'])->name('home');
Route::get('/detail/{id}', [ReseController::class, 'detail'])->name('detail');
Route::get('/search', [ReseController::class, 'search'])->name('search');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('index');
    Route::get('/mypage', [ReseController::class, 'mypage'])->name('mypage');
    Route::post('/reservation/{id}', [ReseController::class, 'reservation'])->name('reservation');
    Route::delete('/reservation/delete', [ReseController::class, 'reservationDestroy'])->name('reservationDestroy');
    Route::get('/edit/{id}', [ReseController::class, 'edit'])->name('edit');
    Route::patch('/reservation/{id}', [ReseController::class, 'reservationUpdate'])->name('reservation.update');
    Route::delete('/favorite/delete', [ReseController::class, 'favoriteDestroy'])->name('favoriteDestroy');
    Route::post('/favorite/{id}', [ReseController::class, 'favorite'])->name('favorite');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    
    return back()->with('message', '確認メールを再送信しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::group(['middleware' => ['role:shoprep']], function() {
    Route::get('/shop_rep/shop', [ShopRepController::class, 'repIndex'])->name('repIndex');
    Route::get('/shop_rep/reservation_confirm', [ShopRepController::class, 'getReservation'])->name('getReservation');
});
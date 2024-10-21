<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ReseController;
use App\Http\Controllers\AuthController;
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

Route::get('/shop', [ReseController::class, 'home']);
Route::get('/detail', [ReseController::class, 'detail']);
Route::get('/search', [ReseController::class, 'search']);
Route::get('/thanks', [AuthController::class, 'thanks']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/thanks/login', [AuthController::class, 'tologin']);
    Route::get('/', [AuthController::class, 'index']);
    Route::get('/mypage', [ReseController::class, 'mypage']);
    Route::post('/reservation', [ReseController::class, 'reservation']);
    Route::delete('/reservation/delete', [ReseController::class, 'reservationDestroy']);
    Route::delete('/favorite/delete', [ReseController::class, 'favoriteDestroy']);
    Route::post('/favorite/{shop_id}', [ReseController::class, 'favorite']);
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', '確認メールを再送信しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

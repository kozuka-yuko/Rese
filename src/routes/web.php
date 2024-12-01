<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ReseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailableController;
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
    Route::get('/thanks', [AuthController::class, 'thanks'])->name('thanks');
    Route::get('/', [AuthController::class, 'index'])->name('index');
    Route::get('/mypage', [ReseController::class, 'mypage'])->name('mypage');
    Route::post('/reservation/{id}', [ReseController::class, 'reservation'])->name('reservation');
    Route::delete('/reservation/delete', [ReseController::class, 'reservationDestroy'])->name('reservationDestroy');
    Route::get('/edit/{id}', [ReseController::class, 'edit'])->name('edit');
    Route::patch('/reservation/{id}', [ReseController::class, 'reservationUpdate'])->name('reservation.update');
    Route::delete('/favorite/delete', [ReseController::class, 'favoriteDestroy'])->name('favoriteDestroy');
    Route::post('/favorite/{id}', [ReseController::class, 'favorite'])->name('favorite');
    Route::get('/qr-code/{id}', [ReseController::class, 'showQrCode'])->name('showQrCode');
    Route::post('/qr-code/visit', [ReseController::class, 'confirmVisit'])->name('confirmVisit');
    Route::get('/review/{id}', [ReseController::class, 'showCreateReview'])->name('showCreateReview');
    Route::post('/review/store', [ReseController::class, 'store'])->name('store');
    Route::get('/review-list/{id}', [ReseController::class, 'showReviewList'])->name('showReviewList');
    Route::get('/shop_rep/shop', [ShopRepController::class, 'repIndex'])->name('repIndex');
    Route::get('/shop_rep/shop_create', [ShopRepController::class, 'shopCreate'])->name('shopCreate');
    Route::post('/shop_rep/shop_create_confirm', [ShopRepController::class, 'shopCreateConfirm'])->name('shopCreateConfirm');
    Route::get('/shop_rep/shop_create_confirm', [ShopRepController::class, 'showShopCreateConfirm'])->name('showShopCreateConfirm');
    Route::post('/shop_rep/shop_create_confirm/create', [ShopRepController::class, 'newShopCreate'])->name('newShopCreate');
    Route::post('/shop_create_confirm/cancel', [ShopRepController::class, 'createCancel'])->name('createCancel');
    Route::delete('/shoprep/shop/delete/{id}', [ShopRepController::class, 'shopDestroy'])->name('shopDestroy');
    Route::get('/shop_rep/edit/{id}', [ShopRepController::class, 'shopEdit'])->name('shopEdit');
    Route::post('shop_rep/confirm_input/{id}', [ShopRepController::class, 'shopUpdateConfirm'])->name('shopUpdateConfirm');
    Route::get('shop_rep/confirm_input/{id}', [ShopRepController::class, 'showShopUpdateConfirm'])->name('showShopUpdateConfirm');
    Route::patch('shop_rep/confirm_input/update/{id}', [ShopRepController::class, 'shopUpdate'])->name('shopUpdate');
    Route::post('/cancel', [ShopRepController::class, 'cancel'])->name('cancel');
    Route::get('/shop_rep/reservation_confirm/{num?}', [ShopRepController::class, 'getReservation'])->name('getReservation');
    Route::get('/admin/management', [AdminController::class, 'adIndex'])->name('adIndex');
    Route::get('/admin/shop_rep_list', [AdminController::class, 'shopRepList'])->name('shopRepList');
    Route::get('/admin/shop_rep_list/search', [AdminController::class, 'repSearch'])->name('repSearch');
    Route::delete('admin/shop_rep_list/delete', [AdminController::class, 'shopRepDestroy'])->name('shopRepDestroy');
    Route::get('/admin/new_rep_create', [AdminController::class, 'newRepEdit'])->name('newRepEdit');
    Route::post('/admin/confirm', [AdminController::class, 'shopRepConfirm'])->name('shopRepConfirm');
    Route::get('/admin/confirm', [AdminController::class, 'showRepConfirm'])->name('showRepConfirm');
    Route::post('/admin/confirm/create', [AdminController::class, 'create'])->name('create');
    Route::get('/admin/shop_rep_update/{id}', [AdminController::class, 'updateEdit'])->name('updateEdit');
    Route::post('/admin/update_confirm/{id}', [AdminController::class, 'updateConfirm'])->name('updateConfirm');
    Route::get('/admin/update_confirm/{id}', [AdminController::class, 'showUpdateConfirm'])->name('showUpdateConfirm');
    Route::patch('/admin/update_confirm/store/{id}', [AdminController::class, 'repUpdate'])->name('repUpdate');
    Route::get('/emails/send_email', [MailableController::class, 'emailForm'])->name('emailForm');
    Route::post('/emails/send_email', [MailableController::class, 'sendEmail'])->name('sendEmail');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', '確認メールを再送信しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

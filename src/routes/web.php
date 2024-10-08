<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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

Route::middleware('auth')->group(function () {
    Route::get('/', [ReseController::class, 'index']);
    Route::get('/mypage', [ReseController::class, 'mypage']);
    Route::post('/reservation', [ReseController::class, 'reservation']);
    Route::delete('/delete',[ReseController::class, 'destroy']);
});

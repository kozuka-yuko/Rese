<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function index()
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shops = Shop::with(['area', 'genre'])->get();

        session()->flash('result', 'ログインしました');
        return view('shop', compact('areas', 'genres', 'shops'));
    }

    public function thanks()
    {
        return view('thanks');
    }

    public function tologin()
    {
        return view('auth.login');
    }
}

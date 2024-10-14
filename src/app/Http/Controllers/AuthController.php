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
        $shops = Shop::select('id', 'name', 'info', 'img_url', 'area_id', 'genre_id')->get();
        return view('shop', compact('areas', 'genres', 'shops'));
    }

    public function thanks()
    {
        return view('/thanks');
    }
}
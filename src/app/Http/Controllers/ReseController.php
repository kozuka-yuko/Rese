<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Favorite;
use App\Models\Genre;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReseController extends Controller
{
    public function index()
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shops = Shop::select('id', 'name', 'info', 'img_url', 'area_id', 'genre_id')->get();
        return view('shop', compact('areas', 'genres', 'shops'));
    }

    public function home()
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shops = Shop::select('id', 'name', 'info', 'img_url', 'area_id', 'genre_id')->get();
        return view('shop', compact('areas', 'genres', 'shops'));
    }

    public function detail(Request $request)
    {
        $shopId = $request->input('id');
        $shop = Shop::where('id', $shopId)->first();
        $times = [];
        for ($i = 9; $i <= 18; $i++) {
            $times[] = sprintf('%02d:00', $i);
        }
        $numbers = range(1, 20);

        return view('detail', compact('shop', 'times', 'numbers'));
    }

    public function search(Request $request)
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shops = Shop::with(['area', 'genre'])->AreaSearch($request->area_id)
            ->GenreSearch($request->genre_id)
            ->NameSearch($request->name_input)->get();

        return view('shop', compact('areas', 'genres', 'shops'));
    }

    public function reservation(Request $request)
    {
        $userId = Auth::id();
        $shopId = $request->input('shop_id');
        $date = $request->input('date');
        $time = $request->input('time');
        $number = $request->input('number');
        Reservation::create([
            'user_id' => $userId,
            'shop_id' => $shopId,
            'date' => $date,
            'time' => $time,
            'number' => $number,
            'status' => '予約'
        ]);
        return view('/done');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Favorite;
use App\Models\Genre;
use App\Models\Reservation;

class ReseController extends Controller
{
    public function index()
    {
        return view('shop');
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

        return view('detail', compact('shop'));
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
}

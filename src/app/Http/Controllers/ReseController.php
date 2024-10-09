<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Favorite;
use App\Models\Genre;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        $today = Carbon::today()->addDay()->format('Y-m-d');
        $shopId = $request->input('id');
        $shop = Shop::where('id', $shopId)->first();
        $times = [];
        for ($i = 9; $i <= 18; $i++) {
            $times[] = sprintf('%02d:00', $i);
        }
        $numbers = range(1, 20);

        return view('detail', compact('today', 'shop', 'times', 'numbers'));
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
        $data = $request->only(['date','time','number']);
        $data['user_id'] = $userId;
        $data['shop_id'] = $shopId;
        $data['status'] = '予約';
        
        Reservation::create($data);

        return view('/done');
    }

    public function mypage()
    {
        $user = Auth::user();
        $reservations = Reservation::where('user_id',$user->id)->get();
        $favorites = Favorite::where('user_id', $user->id)->get();

        return view('/mypage', compact('user','reservations','favorites'));
    }

    public function destroy(Request $request)
    {
        Reservation::find($request->id)->delete();

        return redirect('/mypage')->with('message','予約を削除しました');
    }
}

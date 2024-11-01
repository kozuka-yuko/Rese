<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Favorite;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\ReservationRequest;

class ReseController extends Controller
{
    public function home()
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shops = Shop::with(['area', 'genre'])->get();
        return view('shop', compact('areas', 'genres', 'shops'));
    }

    public function detail(Request $request, $id)
    {
        $today = Carbon::today()->addDay()->format('Y-m-d');
        $shop = Shop::with(['area', 'genre'])->findOrFail($id);
        $times = [];
        for ($i = 9; $i <= 19; $i++) {
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
        if ($shops->isEmpty()) {
            session()->flash('result', '該当する店舗がありませんでした');
            return view('shop', compact('areas', 'genres', 'shops'));
        } else {
            return view('shop', compact('areas', 'genres', 'shops'));
        }
    }

    public function reservation(ReservationRequest $request, $id)
    {
        $userId = Auth::id();
        $data = $request->only(['date', 'time', 'number']);
        $data['shop_id'] = $id;
        $data['user_id'] = $userId;
        $data['status'] = '予約';

        Reservation::create($data);

        return view('done');
    }

    public function mypage()
    {
        $user = Auth::user();
        $reservations = Reservation::where('user_id', $user->id)->get();
        $favorites = Favorite::where('user_id', $user->id)->with('shop')->get();
        $today = Carbon::today()->addDay()->format('Y-m-d');
        $times = [];
        for ($i = 9; $i <= 19; $i++) {
            $times[] = sprintf('%02d:00', $i);
        }
        $numbers = range(1, 20);

        return view('mypage', compact('user', 'reservations', 'favorites', 'today', 'times', 'numbers'));
    }

    public function reservationDestroy(Request $request)
    {
        Reservation::find($request->id)->delete();

        return redirect('mypage')->with('result', '予約を削除しました');
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $today = Carbon::today()->addDay()->format('Y-m-d');
        $times = [];
        for ($i = 9; $i <= 19; $i++) {
            $times[] = sprintf('%02d:00', $i);
        }
        $numbers = range(1, 20);

        return view('edit', compact('reservation', 'today', 'times', 'numbers'));
    }

    public function reservationUpdate(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());

        return redirect()->route('mypage')->with('result', '予約を変更しました');
    }

    public function favoriteDestroy(Request $request)
    {
        Favorite::find($request->id)->delete();

        return redirect('mypage')->with('result', 'お気に入りを削除しました');
    }

    public function favorite($id)
    {
        $user = Auth::user();
        $shopId = $id;

        $isfavorite = Favorite::where('user_id', $user->id)->where('shop_id', $shopId)->exists();

        if ($isfavorite) {
            Favorite::where('user_id', $user->id)->where('shop_id', $shopId)->delete();
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'shop_id' => $shopId
            ]);
        }
        return back();
    }
}

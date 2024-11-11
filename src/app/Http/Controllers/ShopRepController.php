<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ShopRepController extends Controller
{
    public function repIndex()
    {
        $user = Auth::user();
        $id = $user->shops->first()->id;
        $shop = Shop::with(['area', 'genre'])->where('id', $id)->first();

        return view('/shop_rep/shop', compact('shop'));
    }

    public function shopEdit()
    {
        $areas = Area::all();
        $genres = Genre::all();
        $user = Auth::user();
        $shop = Shop::with(['area', 'genre'])->where('id', $user->shop_id)->get();
        return view('/shop_rep/edit', compact('areas', 'genres', 'shop'));
    }

    public function getReservation(Request $request)
    {
        $num = (int)$request->num;
        $dt = Carbon::today();
        if ($num == 0) {
            $date = $dt;
        } elseif ($num > 0) {
            $date = $dt->subDays($num);
        } else {
            $date = $dt->subDays(-$num);
        }
        $fixed_date = $date->toDateString();

        $user = Auth::user();
        $reservation = Reservation::where('shop_id', $user->shop_id)->where('date', $fixed_date)->paginate(10);

        return view('/shop_rep/reservation_confirm', compact('num', 'fixed_date', 'reservation'));
    }
}

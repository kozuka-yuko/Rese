<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;

class ShopRepController extends Controller
{
    public function repIndex()
    {
        $user = Auth::user();
        $areas = Area::all();
        $genres = Genre::all();
        $shop = Shop::with(['area', 'genre'])->where('shop_id', $user->shop_id)->get();

        return view('/shop_rep/shop', compact('areas', 'genres', 'shop'));
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

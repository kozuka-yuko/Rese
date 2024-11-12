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
use Illuminate\Support\Facades\Storage;

class ShopRepController extends Controller
{
    public function repIndex()
    {
        $user = Auth::user();
        $id = $user->shops->first()->id;
        $shop = Shop::with(['area', 'genre'])->where('id', $id)->first();

        return view('/shop_rep/shop', compact('shop'));
    }

    public function shopEdit($id)
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shop = Shop::findOrFail($id);
        return view('/shop_rep/edit', compact('areas', 'genres', 'shop'));
    }

    public function shopUpdateConfirm(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $request->all();
        $path = $request->file('image')->store('images', 'local');
        session([
            'form_input' => [
                'img_url' => $path,
                'area' => $request->input('area'),
                'genre' => $request->input('genre'),
                'description' => $request->input('description')
            ]
            ]);

        return redirect()->route('showShopUpdateConfirm', compact('id'));
    }

    public function showShopUpdateConfirm($id)
    {
        $data = session()->get('form_input');
        $shop = Shop::findOrFail($id);

        return view('/admin/update_confirm', compact('data', 'shop'));
    }

    public function shopUpdate(Request $request, $id)
    {
        $data = session()->get('form_input');

        if (!$data) {
            return redirect()->route('shopEdit');
        }

        $shop = Shop::findOrFail($id);
        $data['area_id'] = $data['area'];
        unset($data['area']);
        // ここ訂正　どうやったらエリアid持ってこれる？
        $shop->update($data);

        session()->forget('form_input');

        return redirect()->route('repIndex')->with('result', '店舗情報を変更しました');
    }

    public function cancel()
    {
        $path
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
        $reservation = Reservation::where('shop_id', $user->shops->first()->id)->where('date', $fixed_date)->paginate(10);

        return view('/shop_rep/reservation_confirm', compact('num', 'fixed_date', 'reservation'));
    }
}

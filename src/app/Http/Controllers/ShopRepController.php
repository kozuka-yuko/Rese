<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Http\Requests\ShopUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopRepController extends Controller
{
    public function repIndex()
    {
        $user = Auth::user();
        $shop = $user->shops()->with(['area', 'genre'])->first();

        return view('/shop_rep/shop', compact('shop'));
    }

    public function shopCreate()
    {
        $areas = Area::all();
        $genres = Genre::all();
        return view('/shop_rep/shop_create', compact('areas', 'genres'));
    }

    public function shopCreateConfirm(ShopUpdateRequest $request)
    {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
        } else {
            $path = null;
        }
        session([
            'form_input' => [
                'name' => $request->input('name'),
                'img_url' => $path,
                'area_id' => $request->input('area'),
                'genre_id' => $request->input('genre'),
                'description' => $request->input('description')
            ]
        ]);

        return redirect()->route('showShopCreateConfirm');
    }

    public function showShopCreateConfirm()
    {
        $data = session()->get('form_input');
        $area = Area::where('id', $data['area_id'])->first();
        $genre = Genre::where('id', $data['genre_id'])->first();

        return view('/shop_rep/shop_create_confirm', compact('data', 'area', 'genre'));
    }

    public function newShopCreate()
    {
        $data = session()->get('form_input');

        if (!$data) {
            return redirect()->route('shopCreate');
        }
        
        $shop = Shop::create($data);

        /** @var \App\Models\User $shopRep */
        $shopRep = Auth::user();
        session()->forget('form_input');
        $shopRep->shops()->attach($shop->id);

        return redirect()->route('repIndex')->with('result', '店舗を登録しました');
    }

    public function createCancel()
    {
        $path = session('form_input.img_url');
        session()->forget('form_input');
        if ($path) {
            Storage::disk('local')->delete($path);
        }

        return redirect()->route('repIndex');
    }

    public function shopDestroy($id)
    {
        Shop::findOrFail($id)->delete();

        return redirect()->route('repIndex')->with('result', '削除しました');
    }

    public function shopEdit($id)
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shop = Shop::findOrFail($id);
        return view('/shop_rep/edit', compact('areas', 'genres', 'shop'));
    }

    public function shopUpdateConfirm(ShopUpdateRequest $request, $id)
    {
        $shop = Shop::findOrFail($id);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
        } else {
            $path = $shop->img_url;
        }
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
        $area = Area::where('id', $data['area'])->first();
        $genre = Genre::where('id', $data['genre'])->first();

        return view('/shop_rep/confirm_input', compact('data', 'shop', 'area', 'genre'));
    }

    public function shopUpdate(Request $request, $id)
    {
        $data = session()->get('form_input');

        if (!$data) {
            return redirect()->route('shopEdit');
        }

        $shop = Shop::findOrFail($id);

        $shop->img_url = $data['img_url'];
        $shop->area_id = $data['area'];
        $shop->genre_id = $data['genre'];
        $shop->description = $data['description'];

        $shop->save();

        session()->forget('form_input');

        return redirect()->route('repIndex')->with('result', '店舗情報を変更しました');
    }

    public function cancel()
    {
        $path = session('form_input.img_url');
        session()->forget('form_input');
        if ($path) {
            Storage::disk('local')->delete($path);
        }

        return redirect()->route('repIndex');
    }

    public function getReservation(Request $request)
    {
        $num = (int)$request->num;
        $dt = Carbon::today();
        if ($num == 0) {
            $date = $dt;
        } elseif ($num > 0) {
            $date = $dt->addDays($num);
        } else {
            $date = $dt->subDays(abs($num));
        }
        $fixed_date = $date->toDateString();
        $fixedDate = $date;

        $user = Auth::user();
        $reservations = Reservation::where('shop_id', $user->shops->first()->id)->where('date', $fixed_date)->paginate(10);

        return view('/shop_rep/reservation_confirm', compact('num', 'dt', 'fixed_date', 'fixedDate', 'reservations'));
    }
}

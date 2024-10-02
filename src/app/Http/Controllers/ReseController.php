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
        $shops = Shop::select('id','name','info','img_url','area_id','genre_id')->get();
        return view('shop',compact('shops'));
    }

    public function detail(Request $request)
    {
        $shopId = $request->input('shop_id');
        $shop = Shop::where('shop_id',$shopId)->first();

        return view('detail',compact('shop'));
    }
}

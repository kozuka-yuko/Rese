<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopRep;

class AdminController extends Controller
{
    public function adIndex()
    {
        return view('/admin/management');
    }

    public function shopRepList()
    {
        $shopRep = ShopRep::all();

        return view('/admin/shop_rep_list', compact('shopRep'));
    }
}

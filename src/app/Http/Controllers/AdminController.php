<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopRep;
use App\Models\Role;

class AdminController extends Controller
{
    public function adIndex()
    {
        return view('/admin/management');
    }

    public function shopRepList()
    {
        $shopReps = ShopRep::all();

        return view('/admin/shop_rep_list', compact('shopReps'));
    }

    public function newRepEdit()
    {
        $role = Role::all();

        return view('/admin/new_rep_create', compact('role'));
    }

    public function shopRepDestroy(Request $request)
    {
        ShopRep::find($request->id)->delete();

        return redirect('/admin/shop_rep_list')->with('result', '削除しました');
    }

    public function shopRepConfirm()
    {

        return view('/admin/confirm', compact(''));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopRep;
use App\Models\Role;
use App\Http\Requests\NewRepRequest;

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
        $roleId = 2;

        return view('/admin/new_rep_create', compact('roleId'));
    }

    public function shopRepConfirm(NewRepRequest $request)
    {
        $roleId = 2;
        $data = $request->all();
        $data['role_id'] = $roleId;

        $request->session()->put('form_input', $data);

        return redirect('/admin/confirm');
    }

    public function showRepConfirm()
    {
        $formInput = session('form_input');

        return view('/admin/confirm', compact('formInput'));
    }

    public function shopRepDestroy(Request $request)
    {
        ShopRep::find($request->id)->delete();

        return redirect('/admin/shop_rep_list')->with('result', '削除しました');
    }
}

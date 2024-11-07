<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShopRep;
use App\Models\Shop;
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

        session()->put('form_input', $data);

        return redirect()->route('showRepConfirm');
    }

    public function showRepConfirm()
    {
        $data = session()->get('form_input');

        return view('/admin/confirm', compact('data'));
    }

    public function create()
    {
        $data = session()->get('form_input');
        
        if(!$data){
            return redirect()->route('newRepEdit');
        }

        $shop = Shop::create(['name' => $data['shop_name']]);

        $newRep = collect($data)->only(['shop_rep_name', 'phone_number','email','password','role_id'])->toArray();
        $newRep['shop_id'] = $shop->id;
        $shopRep = ShopRep::create($newRep);
        $shopRep->sendEmailVerificationNotification();
        
        session()->forget('form_input');

        return redirect()->route('shopRepList')->with('result', '登録しました');
    }

    public function shopRepDestroy(Request $request)
    {
        ShopRep::find($request->id)->delete();

        return redirect()->route('shopRepList')->with('result', '削除しました');
    }
}

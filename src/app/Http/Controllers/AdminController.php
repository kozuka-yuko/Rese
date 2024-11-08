<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Http\Requests\NewRepRequest;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function adIndex()
    {
        return view('/admin/management');
    }

    public function shopRepList()
    {
        $shopReps = User::whereHas('roles', function ($query) {
            $query->where('name', 'shop_rep');
        })->with('shops')->get();

        return view('/admin/shop_rep_list', compact('shopReps'));
    }

    public function newRepEdit()
    {
        return view('/admin/new_rep_create');
    }

    public function shopRepConfirm(NewRepRequest $request)
    {
        $data = $request->all();

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

        if (!$data) {
            return redirect()->route('newRepEdit');
        }

        $shop = Shop::create(['name' => $data['shop_name']]);

        $newRep = collect($data)->only(['shop_rep_name', 'email', 'password'])->toArray();
        $newRep['name'] = $newRep['shop_rep_name'];
        unset($newRep['shop_rep_name']);
        $newRep['password'] = Hash::make($newRep['password']);
        $shopRep = User::create($newRep);
        $shopRep->assignRole('shop_rep');
        $shopId = $shop->id;
        $shopRep->shops()->attach($shopId);

        $shopRep->sendEmailVerificationNotification();

        session()->forget('form_input');

        return redirect()->route('shopRepList')->with('result', '店舗代表者を登録しました');
    }

    public function shopRepDestroy(Request $request)
    {
        User::find($request->id)->delete();

        return redirect()->route('shopRepList')->with('result', '削除しました');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Models\Area;
use App\Models\Genre;
use App\Http\Requests\NewRepRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RepUpdateRequest;

class AdminController extends Controller
{
    public function adIndex()
    {
        return view('admin.management');
    }

    public function shopRepList()
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shopReps = User::whereHas('roles', function ($query) {
            $query->where('name', 'shop_rep');
        })->with('shops')->get();

        return view('admin.shop_rep_list', compact('areas', 'genres', 'shopReps'));
    }

    public function repSearch(Request $request) 
    {
        $rep_name = $request->input('rep_name');
        $shop_name = $request->input('shop_name');

        $shopReps = User::when($rep_name, function ($query, $rep_name) {
            return $query->RepNameSearch($rep_name);
        })
        ->when($shop_name, function ($query, $shop_name) {
            return $query->ShopNameSearch($shop_name);
        })
        ->get();

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

        $data['password'] = Hash::make($data['password']);
        $shopRep = User::create($data);
        $shopRep->assignRole('shop_rep');

        $shopRep->sendEmailVerificationNotification();

        session()->forget('form_input');

        return redirect()->route('shopRepList')->with('result', '店舗代表者を登録しました');
    }

    public function shopRepDestroy(Request $request)
    {
        User::find($request->id)->delete();

        return redirect()->route('shopRepList')->with('result', '削除しました');
    }

    public function updateEdit($id)
    {
        $shopRep = User::findOrFail($id);

        return view('/admin/shop_rep_update', compact('shopRep'));
    }

    public function updateConfirm(RepUpdateRequest $request, $id)
    {
        $data = $request->all();

        session()->put('form_input', $data);

        return redirect()->route('showUpdateConfirm', compact('id'));
    }

    public function showUpdateConfirm($id)
    {
        $data = session()->get('form_input');
        $shopRep = User::findOrFail($id);

        return view('/admin/update_confirm', compact('data', 'shopRep'));
    }

    public function repUpdate($id)
    {
        $data = session()->get('form_input');

        if (!$data) {
            return redirect()->route('updateEdit');
        }

        $shopRep = User::findOrFail($id);
        $shopRep->update($data);

        session()->forget('form_input');

        return redirect()->route('shopRepList')->with('result', '店舗代表者の情報を変更しました');
    }
}

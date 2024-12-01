<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Favorite;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\ShopReviewRequest;
use App\Models\ShopReview;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReseController extends Controller
{
    public function home()
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shops = Shop::with(['area', 'genre'])->get();
        return view('shop', compact('areas', 'genres', 'shops'));
    }

    public function detail(Request $request, $id)
    {
        $today = Carbon::today()->addDay()->format('Y-m-d');
        $shop = Shop::with(['area', 'genre'])->findOrFail($id);
        $times = [];
        for ($i = 9; $i <= 19; $i++) {
            $times[] = sprintf('%02d:00', $i);
        }
        $numbers = range(1, 20);

        return view('detail', compact('today', 'shop', 'times', 'numbers'));
    }

    public function search(Request $request)
    {
        $areas = Area::all();
        $genres = Genre::all();
        $shops = Shop::with(['area', 'genre'])->AreaSearch($request->area_id)
            ->GenreSearch($request->genre_id)
            ->NameSearch($request->name_input)->get();
        if ($shops->isEmpty()) {
            session()->flash('result', '該当する店舗がありませんでした');
            return view('shop', compact('areas', 'genres', 'shops'));
        } else {
            return view('shop', compact('areas', 'genres', 'shops'));
        }
    }

    public function reservation(ReservationRequest $request, $id)
    {
        $userId = Auth::id();
        $data = $request->only(['date', 'time', 'number']);
        $data['shop_id'] = $id;
        $data['user_id'] = $userId;
        $data['status'] = '予約';

        Reservation::create($data);

        return view('done');
    }

    public function mypage()
    {
        $user = Auth::user();
        $reservations = Reservation::where('user_id', $user->id)->whereDate('date', '>=', Carbon::today())->orderBy('date', 'asc')->get();
        $favorites = Favorite::where('user_id', $user->id)->with('shop')->get();
        $today = Carbon::today()->addDay()->format('Y-m-d');
        $times = [];
        for ($i = 9; $i <= 19; $i++) {
            $times[] = sprintf('%02d:00', $i);
        }
        $numbers = range(1, 20);

        return view('mypage', compact('user', 'reservations', 'favorites', 'today', 'times', 'numbers'));
    }

    public function reservationDestroy(Request $request)
    {
        Reservation::find($request->id)->delete();

        return redirect('mypage')->with('result', '予約を削除しました');
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $today = Carbon::today()->addDay()->format('Y-m-d');
        $times = [];
        for ($i = 9; $i <= 19; $i++) {
            $times[] = sprintf('%02d:00', $i);
        }
        $numbers = range(1, 20);

        return view('edit', compact('reservation', 'today', 'times', 'numbers'));
    }

    public function reservationUpdate(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());

        return redirect()->route('mypage')->with('result', '予約を変更しました');
    }

    public function favoriteDestroy(Request $request)
    {
        Favorite::find($request->id)->delete();

        return redirect('mypage')->with('result', 'お気に入りを削除しました');
    }

    public function favorite($id)
    {
        $user = Auth::user();
        $shopId = $id;

        $isfavorite = Favorite::where('user_id', $user->id)->where('shop_id', $shopId)->exists();

        if ($isfavorite) {
            Favorite::where('user_id', $user->id)->where('shop_id', $shopId)->delete();
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'shop_id' => $shopId
            ]);
        }
        return back();
    }

    public function showQrCode($id)
    {
        $user = Auth::user();
        $reservation = Reservation::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $qrData = [
            '店舗名' => $reservation->shop->name,
            '予約者名' => $user->name,
            '来店日' => $reservation->date,
            '来店時間' => $reservation->time,
            'reservation_id' => Crypt::encryptString($reservation->id),
        ];

        $jsonData = json_encode($qrData);
        $qrCode = QrCode::size(300)->generate($jsonData);

        return view('/qr-code', compact('reservation', 'qrData', 'qrCode'));
    }

    public function confirmVisit(Request $request)
    {
        try {
            $reservationId = Crypt::decryptString($request->input('reservation_id'));
            $reservation = Reservation::findOrFail($reservationId);
            $reservation->status = 'ご来店';
            $reservation->save();

            return redirect()->back()->with('result', '来店確認が完了しました！');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '来店確認が完了していません。');
        }
    }

    public function showCreateReview($id)
    {
        $exists = ShopReview::where('user_id', $request->user()->id)->where('shop_id', $request->$id)->exists();

        $shop = Shop::findOrFail($shopId);

        if ($exists) {
        }
    }

    public function store(ShopReviewRequest $request)
    {
        $userId = Auth::id();
        $stars = $request->input('rating');
        $comment = $request->input('comment');
        $shopId = $request->input('shop_id');
        $reviewData = [
            'user_id' => $userId,
            'shop_id' => $shopId,
            'stars' => $stars,
            'comment' => $comment,
        ];
        ShopReview::create($reviewData);

        return redirect()->route('mypage')->with('result', 'レビューを投稿しました');
    }

    public function showReviewList($id)
    {
        $reviews = ShopReview::where('shop_id', $id)->get();
        $shop = Shop::findOrFail($id);

        return view('/review-list', compact('reviews', 'shop'));
    }
}

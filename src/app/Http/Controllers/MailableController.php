<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Models\User;

class MailableController extends Controller 
{
    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'groupType' => 'required|in:general,shop_rep',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        if ($validated['groupType'] === 'general') {
            $users = User::whereNull('role')->get();
        }elseif ($validated['groupType'] === 'shop_rep') {
            $users = User::where('role', 'shop_rep')->get();
        }

        $details = [
            'title' => $validated['title'],
            'body' => $validated['body'],
        ];
        
        foreach ($users as $user){
        Mail::mailer('admin_mailer')->to($user->email)->queue(new SendEmail($details));
        }

        return redirect()->back()->with('result', 'メールが送信されました');
    }
}

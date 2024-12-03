<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Models\User;

class MailableController extends Controller
{
    public function emailForm()
    {
        return view('/emails/send_email');
    }

    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'groupType' => 'required|in:general,shop_rep',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        if ($validated['groupType'] === 'general') {
            $users = User::doesntHave('roles')->get();
        } elseif ($validated['groupType'] === 'shop_rep') {
            $users = User::role('shop_rep')->get();
        }

        $details = [
            'title' => $validated['title'],
            'body' => $validated['body'],
        ];

        foreach ($users as $user) {
            SendEmailJob::dispatch($details, $user->email);
        }

        return redirect()->back()->with('result', 'メールが送信されました');
    }
}

<?php


namespace App\Http\Controllers;

use App\Notifications\EmailHaloNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class EmailNotificationController extends Controller
{
    public function send(Request $request)
    {
        Notification::route('mail', 'yaufaniadam@gmail.com')->notify(new EmailHaloNotification());

        return redirect('/')->with('success', 'Notifikasi Email terkirim!');
    }
}

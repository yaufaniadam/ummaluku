<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class WhatsAppChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toWhatsApp($notifiable);

        $token = "uOw1hkJsad6NtRjOdJ0ESgeDfxx6PFjsy9mR6fXreDYeVTFcqNEanc3";
        $secret_key = "T17RBPFm";

        $phone = $message['phone'];  // E.g. 6281223xxx
        $text = urlencode($message['message']);

        $url = "https://sby.wablas.com/api/send-message?token=$token.$secret_key&phone=$phone&message=$text";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);
        curl_close($ch);

        // Optional: log or handle response
        return $response;
    }
}

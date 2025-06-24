<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

/**
 * @method array toWhatsApp(object $notifiable)
 */

class WhatsAppChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        // Panggil method toWhatsApp dari class notifikasi
        $data = $notification->toWhatsApp($notifiable);

        if (!$data || empty($data['phone']) || empty($data['message'])) {
            return;
        }

        // Ambil token dari file .env untuk keamanan
        $token = config('services.wablas.token');
        $secret_key = config('services.wablas.secret');

        // Gunakan Http Client Laravel
        Http::asForm()->withHeaders([
            "Authorization" => "{$token}.{$secret_key}",
        ])->post("https://sby.wablas.com/api/send-message", [
            'phone' => $data['phone'],
            'message' => $data['message'],
        ]);
    }
}
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PendaftaranBerhasil extends Notification implements ShouldQueue // <-- Implementasi ShouldQueue
{
    use Queueable; // <-- Gunakan trait Queueable

    protected $password;

    public function __construct($generatedPassword)
    {
        $this->password = $generatedPassword;
    }

    public function via(object $notifiable): array
    {
        // Untuk saat ini kita aktifkan email. WhatsApp bisa ditambahkan nanti.
        return ['mail']; 
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Pendaftaran Berhasil - Universitas Muhammadiyah Maluku')
                    ->greeting('Assalamualaikum Wr. Wb. ' . $notifiable->name . ',')
                    ->line('Pendaftaran awal Anda di Universitas Muhammadiyah Maluku telah berhasil kami terima.')
                    ->line('Berikut adalah data login Anda untuk mengakses portal pendaftar:')
                    ->line('Email: ' . $notifiable->email)
                    ->line('Password: ' . $this->password)
                    ->line('Mohon segera login dan ganti password Anda demi keamanan.')
                    ->action('Login Sekarang', url('/login'))
                    ->line('Terima kasih telah mendaftar!');
    }
    
    // public function toWhatsapp(...) {
    //     // Logika untuk mengirim via WhatsApp API (seperti Twilio, WATI, dll.) akan ada di sini
    // }
}
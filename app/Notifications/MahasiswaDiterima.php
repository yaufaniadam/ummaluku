<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\WhatsAppChannel; 

class MahasiswaDiterima extends Notification implements ShouldQueue
{
    use Queueable;

    public $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

    /**
     * Tentukan channel notifikasi yang akan digunakan.
     */
    public function via(object $notifiable): array
    {
        return ['mail', WhatsAppChannel::class]; // Kirim via email dan WhatsAppChannel kustom kita
    }

    /**
     * Mendefinisikan format pesan email.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     $acceptedProgram = $this->application->programChoices->where('is_accepted', true)->first()->program;

    //     return (new MailMessage)
    //                 ->subject('Selamat! Anda Diterima di Universitas Muhammadiyah Maluku')
    //                 ->greeting('Assalamualaikum Wr. Wb. ' . $notifiable->name . ',')
    //                 ->line('Selamat! Berdasarkan hasil seleksi, Anda dinyatakan DITERIMA sebagai calon mahasiswa baru di Universitas Muhammadiyah Maluku.')
    //                 ->line('Program Studi: **' . $acceptedProgram->name_id . ' (' . $acceptedProgram->degree . ')**')
    //                 ->line('Silakan login ke dashboard pendaftar Anda untuk melihat informasi mengenai registrasi ulang dan pembayaran.')
    //                 ->action('Buka Dashboard Pendaftar', route('pendaftar.dashboard'))
    //                 ->line('Terima kasih atas partisipasi Anda.');
    // }
    
    /**
     * Mendefinisikan format pesan WhatsApp.
     */
    public function toWhatsApp(object $notifiable): array
    {
        return [
            'phone' => '08562563456', // Ambil nomor HP dari data prospective
            'message' => "Assalamualaikum  Selamat! Anda dinyatakan DITERIMA di Universitas Muhammadiyah Maluku."
        ];
    }
}
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\WhatsAppChannel;
use App\Models\Application;
use App\Models\User;

class PendaftaranBaru extends Notification implements ShouldQueue // <-- Implementasi ShouldQueue
{
    use Queueable; 

    protected $user;
    public Application $application;

    public function __construct(User $user, Application $application)
    {
        $this->user = $user;
        $this->application = $application;
    }


    public function via(object $notifiable): array
    {
        // Untuk saat ini kita aktifkan email. WhatsApp bisa ditambahkan nanti.
        return ['mail', WhatsAppChannel::class, 'database'];
        // return ['mail']; 
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pendaftaran Mahasiswa Baru - Universitas Muhammadiyah Maluku')
            ->greeting('Assalamualaikum Wr. Wb. ' . $notifiable->name . ',')
            ->line('Ada calon mahasiswa yang melakukan pendaftaran di sistem');
            // ->line('Berikut adalah data login Anda untuk mengakses portal pendaftar:')
            // ->line('Email: ' . $notifiable->email)
            // ->line('Password: ' . $this->password)
            // ->line('Mohon segera login dan ganti password Anda demi keamanan.')
            // ->action('Login Sekarang', url('/login'))
            // ->line('Terima kasih telah mendaftar!');
    }

    public function toWhatsApp(object $notifiable): array
    {

        $message = "Assalamualaikum Wr. Wb. $notifiable->name \n\n" .
            "Ada calon mahasiswa yang melakukan pendaftaran di sistem.\n" .
                   "Terima kasih!";
        
        return [
            'phone' => '08562563456', // Ambil nomor HP dari data prospective
            'message' => $message
        ];
    }
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Pendaftar baru telah masuk: ' . $notifiable->name,
            'icon'    => 'fas fa-user-plus text-info', // Ikon dari Font Awesome
            'url'     => route('admin.pendaftaran.show', $this->application->id),
        ];
    }
}

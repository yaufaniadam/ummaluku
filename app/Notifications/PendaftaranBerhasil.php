<?php

namespace App\Notifications;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\WhatsAppChannel;
use App\Models\User;

class PendaftaranBerhasil extends Notification 
// implements ShouldQueue // <-- Implementasi ShouldQueue
{
    // use Queueable; 

    protected $user;
    protected $password;

     public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password; 
    }


    public function via(object $notifiable): array
    {
        // Untuk saat ini kita aktifkan email. WhatsApp bisa ditambahkan nanti.
        return ['mail', WhatsAppChannel::class]; 
        // return ['mail']; 
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
    
   public function toWhatsApp(object $notifiable): string
    {

        return "Assalamualaikum Wr. Wb. $notifiable->name \n\n" .
               "Pendaftaran awal Anda di Universitas Muhammadiyah Maluku telah berhasil kami terima.\n" .
               "Berikut ini data login Anda:\n" .
               "Email: *{$notifiable->email}*\n" .
               "Password: *{$this->password}*\n\n" .
               "Mohon segera login di sini " . url('login') . " dan mengganti password Anda demi keamanan.";
               "Terima kasih!";
        // return "Assalamualaikum Wr. W";
    }
}
<?php

namespace App\Notifications;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class MenungguVerifikasi extends Notification
// implements ShouldQueue // <-- Implementasi ShouldQueue
{
    // use Queueable; 

    protected $applicationId;

    public function __construct(string $applicationId)
    {
         $this->applicationId = $applicationId;
    }

    public function via(object $notifiable): array
    {
        // Untuk saat ini kita aktifkan email. WhatsApp bisa ditambahkan nanti.
        return ['mail', 'database'];
        // return ['mail']; 
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Dokumen Menunggu Verifikasi - PMB UM Maluku')
            ->greeting('Assalamualaikum Wr. Wb. ' . $notifiable->name . ',')
            ->line('Dokumen pendaftaran menunggu verifikasi Anda.')
            ->line('Dokumen pendaftaran menunggu verifikasi Anda.')
            ->action('Buka dashboard admin.', route('admin.pendaftaran.show', $this->applicationId))
            ->line('Terima kasih');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Dokumen menunggu verifikasi',
            'icon'    => 'fas fa-user-plus text-info',
            'url'     => route('admin.pendaftaran.show', $this->applicationId),
        ];
    }
}

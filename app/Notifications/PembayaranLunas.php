<?php

namespace App\Notifications;

use App\Models\ReRegistrationInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PembayaranLunas extends Notification
{
    use Queueable;

    protected $invoice;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\ReRegistrationInvoice $invoice
     */
    public function __construct(ReRegistrationInvoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject("Selamat! Pembayaran Anda Telah Lunas - PMB UM Maluku")
                    ->greeting('Yth. ' . $notifiable->name . ',')
                    ->line("Kabar gembira! Seluruh pembayaran untuk invoice **{$this->invoice->registration_number}** telah kami terima dan statusnya kini **LUNAS**.")
                    ->line('Anda telah menyelesaikan kewajiban pembayaran pendaftaran ulang. Terima kasih!')
                    ->action('Lihat Detail Invoice', url('/dashboard/pembayaran')); // Ganti dengan URL yang sesuai
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Pembayaran untuk invoice {$this->invoice->registration_number} telah lunas.",
            'icon'    => 'fas fa-check-circle text-success',
            'url'     => '/dashboard/pembayaran', // Ganti dengan URL yang sesuai
        ];
    }
}   
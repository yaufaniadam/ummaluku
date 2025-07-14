<?php

namespace App\Notifications;

use App\Models\ReRegistrationInstallment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Number;

class PembayaranCicilanDiterima extends Notification
{
    use Queueable;

    protected $installment;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\ReRegistrationInstallment $installment
     */
    public function __construct(ReRegistrationInstallment $installment)
    {
        $this->installment = $installment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(object $notifiable): array
    {
        // Kirim melalui email dan simpan di database
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
        $amount = Number::currency($this->installment->amount, 'IDR');
        $invoiceNumber = $this->installment->invoice->registration_number;

        return (new MailMessage)
                    ->subject("Pembayaran Cicilan Diterima - PMB UM Maluku")
                    ->greeting('Yth. ' . $notifiable->name . ',')
                    ->line("Kami memberitahukan bahwa pembayaran cicilan Anda sebesar **{$amount}** untuk invoice **{$invoiceNumber}** telah kami terima dan verifikasi.")
                    ->line('Terima kasih telah melakukan pembayaran.')
                    ->action('Lihat Riwayat Pembayaran', url('/dashboard/pembayaran')); // Ganti dengan URL yang sesuai
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(object $notifiable): array
    {
        $amount = Number::currency($this->installment->amount, 'IDR');

        return [
            'message' => "Pembayaran cicilan sebesar {$amount} telah diterima.",
            'icon'    => 'fas fa-money-check-alt text-success',
            'url'     => '/dashboard/pembayaran', // Ganti dengan URL yang sesuai
        ];
    }
}
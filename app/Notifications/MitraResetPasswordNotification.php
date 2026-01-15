<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MitraResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Bangun URL dengan explicit APP_URL dari config
        $resetUrl = config('app.url') . '/mitra/reset-password/' . $this->token . '?email=' . urlencode($notifiable->getEmailForPasswordReset());

        return (new MailMessage)
                    ->subject('Reset Password Mitra FoodLink')
                    ->greeting('Halo Mitra FoodLink,')
                    ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.')
                    ->action('Reset Password', $resetUrl)
                    ->line('Link ini akan kadaluwarsa dalam 60 menit.')
                    ->line('Jika Anda tidak meminta reset password, abaikan email ini.')
                    ->salutation('Salam Hormat, Tim FoodLink');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}

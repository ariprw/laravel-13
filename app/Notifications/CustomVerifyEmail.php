<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends VerifyEmail
{
    /**
     * Build the verification URL.
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verify',
            now()->addMinutes(60 * 24 * 1),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1(
                    $notifiable->getEmailForVerification()
                ),
            ]
        );
    }

    /**
     * Build the mail representation.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject(
                'Verifikasi Email - '.config('app.name')
            )
            ->greeting(
                'Halo '.$notifiable->name.','
            )
            ->line(
                'Terima kasih telah mendaftar di '.config('app.name').'.'
            )
            ->line(
                'Silakan klik tombol di bawah untuk memverifikasi alamat email Anda.'
            )
            ->action(
                'Verifikasi Email',
                $verificationUrl
            )
            ->line(
                'Link verifikasi ini berlaku selama 1 hari.'
            )
            ->line(
                'Setelah email diverifikasi, akun Anda tetap menunggu aktivasi administrator.'
            );
    }
}
<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomForgotPassword extends ResetPassword
{
    /**
     * Build the mail representation.
     */
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route('reset.password', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject(
                'Reset Password - '.config('app.name')
            )
            ->greeting(
                'Halo '.$notifiable->name.','
            )
            ->line(
                'Kami menerima permintaan untuk mereset password akun Anda di '.config('app.name').'.'
            )
            ->line(
                'Silakan klik tombol di bawah untuk membuat password baru.'
            )
            ->action(
                'Reset Password',
                $resetUrl
            )
            ->line(
                'Link reset password ini berlaku sesuai dengan waktu kedaluwarsa token reset password.'
            )
            ->line(
                'Abaikan email ini jika Anda tidak meminta reset password.'
            );
    }
}
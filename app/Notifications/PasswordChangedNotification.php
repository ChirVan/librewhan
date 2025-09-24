<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your password was changed')
            ->line('This is a confirmation that your account password was successfully changed.')
            ->line('If you did not perform this action, please contact support or use the password reset link to secure your account.');
    }
}

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
        $body = '<p>This is a confirmation that your account password was successfully changed.</p>'
              . '<p>If you did not perform this action, please contact support or use the password reset link to secure your account.</p>';

        return (new MailMessage)
            ->subject('Your password was changed')
            ->view('emails.formatted', [
                'title' => 'Your password was changed',
                'bodyHtml' => $body,
            ]);
    }
}

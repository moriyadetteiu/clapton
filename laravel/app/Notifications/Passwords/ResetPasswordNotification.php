<?php

namespace App\Notifications\Passwords;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $enableMinutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

        $baseUrl = config('auth.password_reset_url');
        $query = http_build_query([
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset()
        ]);
        $url = "{$baseUrl}?{$query}";

        return (new MailMessage)
            ->subject('パスワードリセット')
            ->line('パスワードの再設定が行えます。')
            ->action('パスワードリセット', $url)
            ->line("このリンクは {$enableMinutes} 分間有効です。");
    }
}

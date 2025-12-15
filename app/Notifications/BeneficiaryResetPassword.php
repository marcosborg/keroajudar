<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class BeneficiaryResetPassword extends ResetPassword
{
    use Queueable;

    /**
     * Get the reset notification mail message for the given URL.
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Recuperar password de beneficiário')
            ->line('Recebemos um pedido para redefinir a sua password.')
            ->action('Definir nova password', $url)
            ->line('Se não fez este pedido, ignore este email.');
    }
}

<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmailBase
{
    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Confirma tu correo electrónico - Spoon\'s Barber Shop 💈')
            ->greeting('¡Bienvenido a la familia! 👋')
            ->line('Estás a un solo paso de poder agendar tus cortes con nosotros. Por favor, haz clic en el botón de abajo para verificar tu dirección de correo electrónico.')
            ->action('Verificar mi Correo', $url)
            ->line('Si tú no creaste una cuenta en Spoon\'s Barber Shop, no te preocupes, puedes ignorar o eliminar este mensaje en este instante.')
            ->salutation('Saludos, el equipo de Spoon\'s Barber Shop ✂️');
    }
}
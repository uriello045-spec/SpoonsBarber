<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    use Queueable;

    public $token;

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
        // Construimos la URL mágica a la que el usuario le dará clic
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Restablecer Contraseña - Spoon\'s Barber Shop 💈')
            ->greeting('¡Hola ' . $notifiable->name . '! 👋')
            ->line('Recibes este correo porque solicitaste restablecer la contraseña de tu cuenta.')
            ->action('Restablecer Contraseña', $url)
            ->line('Este enlace de recuperación es seguro y caducará en 60 minutos.')
            ->line('Si tú no solicitaste un cambio de contraseña, no te preocupes, puedes ignorar este correo sin problema.')
            ->salutation('Saludos, el equipo de Spoon\'s Barber Shop ✂️');
    }
}
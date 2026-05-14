<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentReminder extends Notification
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Formateamos la hora para que se vea bonita (ej. 10:45 AM)
        $horaFormateada = Carbon::parse($this->appointment->hora)->format('h:i A');

        return (new MailMessage)
                    ->subject('¡Tu cita es en 15 minutos! ✂️')
                    ->greeting('Hola ' . $notifiable->name . ',')
                    ->line('Te recordamos que tu cita para "' . $this->appointment->servicio . '" en Spoon\'s Barber Shop está a punto de comenzar.')
                    ->line('⏰ Hora: ' . $horaFormateada)
                    ->line('Te esperamos con gusto. ¡No llegues tarde!')
                    ->action('Ver mis citas', url('/citas'))
                    ->salutation('Atentamente, El equipo de Spoon\'s Barber Shop');
    }
}
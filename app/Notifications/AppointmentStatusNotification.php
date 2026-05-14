<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentStatusNotification extends Notification
{
    use Queueable;

    public $appointment;
    public $estado;

    public function __construct(Appointment $appointment, $estado)
    {
        $this->appointment = $appointment;
        $this->estado = $estado;
    }

    public function via($notifiable)
    {
        return ['mail']; 
    }

    public function toMail($notifiable)
    {
        $subject = 'Actualización de tu cita - Spoon\'s Barber Shop';
        
        if ($this->estado == 'confirmada') {
            $subject = '✅ ¡Tu cita está confirmada! - Spoon\'s Barber Shop';
        } elseif ($this->estado == 'cancelada') {
            $subject = '❌ Tu cita ha sido cancelada - Spoon\'s Barber Shop';
        } elseif ($this->estado == 'completada') {
            $subject = '✂️ ¡Gracias por tu visita! Califícanos - Spoon\'s Barber Shop';
        }

        return (new MailMessage)
                    ->subject($subject)
                    ->view('emails.appointment_status', [
                        'appointment' => $this->appointment,
                        'estado' => $this->estado
                    ]);
    }
}
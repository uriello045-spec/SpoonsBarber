<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class AppointmentStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;
    public $estado;

    public function __construct(Appointment $appointment, $estado)
    {
        $this->appointment = $appointment;
        $this->estado = $estado;
    }

    /**
     * Define por qué canales se enviará (correo, base de datos, SMS, etc.)
     */
    public function via($notifiable)
    {
        // Por ahora solo correo. ¡Si luego quieres campanita en la web, solo agregas 'database'!
        return ['mail']; 
    }

    /**
     * Construye el correo electrónico.
     */
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

        // Usamos la misma vista hermosa que creaste en resources/views/emails/appointment_status.blade.php
        return (new MailMessage)
                    ->subject($subject)
                    ->view('emails.appointment_status', [
                        'appointment' => $this->appointment,
                        'estado' => $this->estado
                    ]);
    }
}
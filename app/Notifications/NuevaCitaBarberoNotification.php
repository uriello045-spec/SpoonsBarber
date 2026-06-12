<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;

class NuevaCitaBarberoNotification extends Notification
{
    use Queueable;

    public $cita;

    public function __construct(Appointment $cita)
    {
        $this->cita = $cita;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Extraemos los datos para que el barbero los lea fácil
        $cliente = $this->cita->user ? $this->cita->user->name : 'Cliente Físico';
        $fecha = \Carbon\Carbon::parse($this->cita->fecha)->format('d/m/Y');
        $hora = \Carbon\Carbon::parse($this->cita->hora)->format('h:i A');

        return (new MailMessage)
            ->subject('📅 ¡Nueva Cita Agendada!')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Un cliente acaba de registrar una nueva cita en el sistema.')
            ->line('**Detalles de la Cita:**')
            ->line('👤 **Cliente:** ' . $cliente)
            ->line('✂️ **Servicio:** ' . $this->cita->servicio)
            ->line('📅 **Fecha:** ' . $fecha)
            ->line('⏰ **Hora:** ' . $hora)
            ->action('Ver Agenda de Citas', url('/admin/citas'))
            ->line('Por favor, revisa tu panel para asegurarte de estar disponible en ese horario.');
    }
}
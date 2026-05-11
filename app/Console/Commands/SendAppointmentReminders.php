<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Notifications\AppointmentReminder;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    // El nombre del comando en la terminal
    protected $signature = 'reminders:send';

    // La descripción
    protected $description = 'Envía recordatorios a los clientes 15 minutos antes de su cita';

    public function handle()
    {
        // 1. 🚨 SÚPER IMPORTANTE: Forzamos la zona horaria de México
        $tz = 'America/Mexico_City';
        
        // Calculamos la hora exacta dentro de 15 minutos
        $targetTime = Carbon::now($tz)->addMinutes(15);

        // 2. Separamos la fecha y la hora para buscar en la BD
        $fechaObjetivo = $targetTime->toDateString(); // Ej. 2026-02-25
        $horaObjetivo = $targetTime->format('H:i');   // Ej. 16:00

        // Esto nos mostrará en la terminal qué hora está buscando realmente el robot
        $this->info("Buscando citas para el: {$fechaObjetivo} a las {$horaObjetivo}...");

        // 3. Buscamos las citas para ese momento exacto
        // (Agregué 'pendiente' y 'aceptada' por si tienes citas en ese estado que no pasaste a confirmada)
        $citas = Appointment::whereIn('estado', ['pendiente', 'confirmada', 'aceptada'])
            ->where('fecha', $fechaObjetivo)
            ->where('hora', 'like', $horaObjetivo . '%')
            ->with('user')
            ->get();

        // 4. Si encuentra citas, enviamos el correo a cada usuario
        if ($citas->isEmpty()) {
            $this->info('No hay citas para esa hora. El robot vuelve a dormir.');
        } else {
            foreach ($citas as $cita) {
                if ($cita->user) {
                    $cita->user->notify(new AppointmentReminder($cita));
                    $this->info('✅ Recordatorio enviado a: ' . $cita->user->email);
                }
            }
        }

        $this->info('Revisión de recordatorios completada.');
    }
}
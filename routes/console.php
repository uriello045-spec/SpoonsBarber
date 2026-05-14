<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // <--- AGREGADO: Importante para programar tareas
use App\Models\User; // 🌟 AÑADIDO: Para buscar usuarios en la base de datos
use Carbon\Carbon;   // 🌟 AÑADIDO: Para calcular las fechas y el tiempo

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ─────────────────────────────────────────────────────────────
// PROGRAMACIÓN DE TAREAS (CRON JOBS)
// ─────────────────────────────────────────────────────────────

// 1. Ejecutar el envío de recordatorios cada minuto
// Buscará citas que sean en 15 minutos exactos y enviará el aviso.
Schedule::command('reminders:send')->everyMinute();

// 2. 🌟 TAREA DE LIMPIEZA: Eliminar cuentas basura 🌟
// Busca usuarios que no han verificado su correo después de 48 horas y los elimina.
Schedule::call(function () {
    // Versión optimizada: Borra directo desde la base de datos en una sola instrucción
    User::whereNull('email_verified_at')
        ->where('created_at', '<', Carbon::now()->subDays(2))
        ->delete();
})->daily(); // Se ejecuta de forma silenciosa una vez al día en la madrugada
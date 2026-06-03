<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; 
use App\Models\User; 
use Carbon\Carbon;  

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

// 3. 🛡️ RESPALDOS AUTOMÁTICOS DE SEGURIDAD 🛡️
// Empaca y comprime la base de datos completa (.sql adentro de un .zip) todos los días a las 2:00 AM
Schedule::command('backup:run --only-db')->dailyAt('02:00');

// Limpia los archivos .zip muy viejos para que Hostinger no se llene (Se ejecuta cada 3 meses). 
// NOTA: Esto solo borra las copias viejas (los .zip de hace mucho tiempo), NO borra NADA de tu base de datos actual. ¡Tus estadísticas quedan 100% a salvo!
Schedule::command('backup:clean')->quarterly();
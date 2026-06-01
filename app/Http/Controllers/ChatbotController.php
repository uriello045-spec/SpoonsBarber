<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Appointment;
use App\Models\Service; 
use App\Models\Setting; 
use Carbon\Carbon;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot.index');
    }

    public function reset()
    {
        session()->forget('chat_history');
        session()->save();
        return response()->json(['reply' => '🤖 ¡Memoria borrada exitosamente!']);
    }

    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $userMessage = trim($request->message);
        
        // 🌟 TRUCO DE DESARROLLADOR: Comando oculto para limpiar memoria rápida en pruebas
        if (strtolower($userMessage) === 'reiniciar') {
            session()->forget('chat_history');
            session()->save();
            return response()->json(['reply' => '🔄 Memoria del bot reseteada. ¡Hola de nuevo! ¿En qué te puedo ayudar?']);
        }

        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json(['reply' => '¡Ups! Parece que no tengo mi clave mágica configurada 😅. Contacta al admin.']);
        }

        $user = Auth::user(); 

        if (!$user) {
            return response()->json(['reply' => '¡Hey! Para agendar una cita necesitas iniciar sesión primero 😅.']);
        }

        if (!$user->terms_accepted) {
            return response()->json([
                'reply' => '🔒 <b>¡Hola!</b> Antes de poder platicar y agendar tu cita, es necesario que aceptes nuestros Términos y Condiciones que aparecen en pantalla. ¡Te espero! 💈'
            ]);
        }

        // 🛡️ ESCUDO 1: LIMITADOR DE VELOCIDAD
        $llave = 'chatbot_user_' . $user->id;
        if (RateLimiter::tooManyAttempts($llave, 5)) {
            $segundos = RateLimiter::availableIn($llave);
            return response()->json([
                'reply' => "⚠️ <b>¡Vas muy rápido!</b> Por favor, espera {$segundos} segundos antes de enviar otro mensaje. 🤖"
            ]);
        }
        RateLimiter::hit($llave, 60);

        // 🛡️ ESCUDO 2: LÍMITE DE CITAS PENDIENTES
        $citasPendientes = Appointment::where('user_id', $user->id)->where('estado', 'pendiente')->count();
        if ($citasPendientes >= 2) {
            return response()->json(['reply' => '🛡️ <b>¡Límite alcanzado!</b> Ya tienes 2 citas pendientes en el sistema. Asiste o cancela alguna antes de poder agendar otra.']);
        }

        $tz = 'America/Mexico_City';
        $ahora = Carbon::now($tz);
        
        $hoy = $ahora->copy()->startOfDay();
        $manana = $ahora->copy()->addDay()->startOfDay();

        $diaSemanaHoy = $hoy->dayOfWeek; 
        $diaSemanaManana = $manana->dayOfWeek;

        // HORARIOS DESDE LA BD
        $aperturaSemana = Setting::where('key', 'apertura_semana')->value('value') ?? '08:00';
        $cierreSemana = Setting::where('key', 'cierre_semana')->value('value') ?? '21:00';
        $cerradoSemana = Setting::where('key', 'cerrado_semana')->value('value') == 'true';

        $aperturaSabado = Setting::where('key', 'apertura_sabado')->value('value') ?? '08:00';
        $cierreSabado = Setting::where('key', 'cierre_sabado')->value('value') ?? '21:00';
        $cerradoSabado = Setting::where('key', 'cerrado_sabado')->value('value') == 'true';

        $aperturaDomingo = Setting::where('key', 'apertura_domingo')->value('value') ?? '08:00';
        $cierreDomingo = Setting::where('key', 'cierre_domingo')->value('value') ?? '21:00';
        $cerradoDomingo = Setting::where('key', 'cerrado_domingo')->value('value') == 'true';

        $horariosApertura = [
            0 => ['inicio' => $aperturaDomingo, 'fin' => $cierreDomingo, 'cerrado' => $cerradoDomingo],
            1 => ['inicio' => $aperturaSemana, 'fin' => $cierreSemana, 'cerrado' => $cerradoSemana],
            2 => ['inicio' => $aperturaSemana, 'fin' => $cierreSemana, 'cerrado' => $cerradoSemana],
            3 => ['inicio' => $aperturaSemana, 'fin' => $cierreSemana, 'cerrado' => $cerradoSemana],
            4 => ['inicio' => $aperturaSemana, 'fin' => $cierreSemana, 'cerrado' => $cerradoSemana],
            5 => ['inicio' => $aperturaSemana, 'fin' => $cierreSemana, 'cerrado' => $cerradoSemana],
            6 => ['inicio' => $aperturaSabado, 'fin' => $cierreSabado, 'cerrado' => $cerradoSabado],
        ];

        $aperturaHoy = $horariosApertura[$diaSemanaHoy];
        $aperturaManana = $horariosApertura[$diaSemanaManana];

        $horaCierreHoy = Carbon::parse($ahora->format('Y-m-d') . ' ' . $aperturaHoy['fin'] . ':00', $tz); 
        $horaLimiteRecepcion = $horaCierreHoy->copy()->subMinutes(45); 

        $mensajeEstadoHoy = "";

        if ($aperturaHoy['cerrado']) {
            $mensajeEstadoHoy = "⛔ HOY ESTAMOS CERRADOS.";
        } elseif ($ahora->gt($horaLimiteRecepcion)) {
            $mensajeEstadoHoy = "⛔ YA NO SE RECIBEN CITAS POR HOY. Ofrece solo para MAÑANA.";
        } else {
            $citasHoyDB = Appointment::leftJoin('services', 'appointments.servicio', '=', 'services.nombre')
                ->whereDate('appointments.fecha', $hoy)
                ->where('appointments.estado', '!=', 'cancelada')
                ->get(['appointments.hora', 'appointments.duracion_minutos']);

            $intervalosHoy = [];
            foreach($citasHoyDB as $c) {
                $ini = Carbon::parse($c->hora);
                $fin = $ini->copy()->addMinutes($c->duracion_minutos ?? 45); 
                $intervalosHoy[] = $ini->format('H:i') . ' a ' . $fin->format('H:i');
            }
            $textoOcupadoHoy = empty($intervalosHoy) ? "Ninguna cita, todo libre" : implode(", ", $intervalosHoy);
            $mensajeEstadoHoy = "Abierto de {$aperturaHoy['inicio']} a {$aperturaHoy['fin']}. Ocupado HOY: [{$textoOcupadoHoy}].";
        }

        if ($aperturaManana['cerrado']) {
            $mensajeEstadoManana = "⛔ MAÑANA ESTAREMOS CERRADOS.";
        } else {
            $citasMananaDB = Appointment::leftJoin('services', 'appointments.servicio', '=', 'services.nombre')
                ->whereDate('appointments.fecha', $manana)
                ->where('appointments.estado', '!=', 'cancelada')
                ->get(['appointments.hora', 'appointments.duracion_minutos']);

            $intervalosManana = [];
            foreach($citasMananaDB as $c) {
                $ini = Carbon::parse($c->hora);
                $fin = $ini->copy()->addMinutes($c->duracion_minutos ?? 45);
                $intervalosManana[] = $ini->format('H:i') . ' a ' . $fin->format('H:i');
            }
            $textoOcupadoManana = empty($intervalosManana) ? "Ninguna, todo libre" : implode(", ", $intervalosManana);
            $mensajeEstadoManana = "Abre de {$aperturaManana['inicio']} a {$aperturaManana['fin']}. Ocupado MAÑANA: [{$textoOcupadoManana}].";
        }

        $serviciosActivos = Service::all();
        $textoServiciosDinamicos = "";
        $nombresServiciosOficiales = [];

        if ($serviciosActivos->isEmpty()) {
            $textoServiciosDinamicos = "<br>💈 Corte Clásico: $100 (Dura 45 min)\n";
            $nombresServiciosOficiales[] = "Corte Clásico";
        } else {
            foreach ($serviciosActivos as $s) {
                $textoServiciosDinamicos .= "<br>💈 {$s->nombre}: $" . number_format($s->precio, 2) . " (Dura {$s->duracion_minutos} min)\n";
                $nombresServiciosOficiales[] = $s->nombre;
            }
        }
        $listaNombresStr = implode(", ", $nombresServiciosOficiales);

        $horaActualTexto = $ahora->format('H:i'); 

        // 🌟 INSTRUCCIONES "A PRUEBA DE TONTOS" Y LÓGICA INTELIGENTE 🌟
        $systemInstruction = "Eres el asistente virtual de Spoon’s Barber Shop. Habla en español, amigable y profesional.

DATOS EN TIEMPO REAL:
- HORA ACTUAL: {$horaActualTexto}. (Usa esto para calcular la lógica de la hora).
- Hoy ({$hoy->format('Y-m-d')}): {$mensajeEstadoHoy} 
- Mañana ({$manana->format('Y-m-d')}): {$mensajeEstadoManana}

🚨 REGLAS SUPREMAS DE CONVERSACIÓN Y RAZONAMIENTO:
1. EDUCA AL USUARIO: Si el cliente solo saluda, dile: 'Para agendarte rápido, dímelo todo junto: Día (hoy/mañana), Hora y Corte.'
2. 🧠 LÓGICA DE HORA INTELIGENTE: Si el usuario te pide cita para HOY a un número como 'a las 3', 'a las 4', 'a las 5', y según la HORA ACTUAL esa hora de la mañana ya pasó, ASUME AUTOMÁTICAMENTE QUE ES DE LA TARDE (PM). ¡NO LE PREGUNTES SI ES AM O PM, tú encárgate de deducirlo y conviértelo!
3. MEMORIA: Si ya tienes el Día, el Servicio y la Hora, AGENDA. No repitas preguntas.
4. ⚠️ OBLIGATORIO - FORMATO MILITAR (24H): Al generar el código para agendar, LA HORA DEBE SER EN 24 HRS. (Ej: 'a las 3' de la tarde se convierte en 15:00. 'a las 5' de la tarde se convierte en 17:00). NUNCA uses 03:00 para la tarde porque el sistema de la barbería marcará error.

🌟 SERVICIOS Y PRECIOS OFICIALES:
{$textoServiciosDinamicos}

🔥 INSTRUCCIÓN SECRETA PARA AGENDAR (SOLO cuando tengas FECHA, HORA EN FORMATO 24H y SERVICIO EXACTO de la lista):
Imprime EXACTAMENTE este código al final de tu respuesta: 
[AGENDAR|YYYY-MM-DD|HH:MM|NombreDelServicioOficial]";

        $history = session()->get('chat_history', []);

        $history[] = [
            "role" => "user", 
            "parts" => [["text" => $userMessage]]
        ];

        try {
            $response = Http::withoutVerifying()
                ->retry(3, 1500)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    "system_instruction" => [
                        "parts" => [["text" => $systemInstruction]]
                    ],
                    "contents" => $history
                ]);

            if ($response->successful()) {
                $reply = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '¡Estoy pensando... 🤔';
                $replyClean = str_replace(['**', '*'], '', trim($reply));

                // ✂️ VALIDACIÓN Y AGENDAMIENTO
                if (preg_match('/\[AGENDAR\|([^|]+)\|([^|]+)\|([^\]]+)\]/', $reply, $matches)) {
                    $fecha = trim($matches[1]);
                    $hora = trim($matches[2]);
                    $servicio = trim($matches[3]);

                    $inicioNuevo = Carbon::parse($fecha . ' ' . $hora, $tz);
                    
                    if ($inicioNuevo->gt($ahora->copy()->addDays(60))) {
                        $cleanReply = preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean);
                        $cleanReply .= "<br><br>🚫 <b>Demasiado lejos:</b> No podemos agendar citas con más de 2 meses de anticipación.";
                        return response()->json(['reply' => $cleanReply]);
                    }

                    if ($inicioNuevo->isPast()) {
                        $cleanReply = preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean);
                        $cleanReply .= "<br><br>🚫 <b>Esa hora ya pasó.</b> Por favor elige un horario futuro.";
                        
                        $history[] = ["role" => "model", "parts" => [["text" => $cleanReply]]];
                        session()->put('chat_history', $history);
                        session()->save();

                        return response()->json(['reply' => $cleanReply]);
                    }

                    if ($inicioNuevo->isToday()) {
                        $limitePermitido = $ahora->copy()->addMinutes(30);
                        if ($inicioNuevo->lt($limitePermitido)) {
                            $cleanReply = preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean);
                            $cleanReply .= "<br><br>⏱️ <b>¡Lo siento!</b> Necesitamos al menos 30 minutos de anticipación. Por favor, dime un horario un poco más tarde (después de las " . $limitePermitido->format('h:i A') . ").";
                            
                            $history[] = ["role" => "model", "parts" => [["text" => $cleanReply]]];
                            session()->put('chat_history', $history);
                            session()->save();

                            return response()->json(['reply' => $cleanReply]);
                        }
                    }

                    $servicioReal = Service::where('nombre', $servicio)->first();
                    if (!$servicioReal) {
                        $cleanReply = preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean);
                        $cleanReply .= "<br><br>🚨 <b>Error de catálogo:</b> El servicio que intentas agendar no existe en nuestro sistema.";
                        return response()->json(['reply' => $cleanReply]);
                    }

                    $diaElegido = $inicioNuevo->dayOfWeek;
                    $horarioElegido = $horariosApertura[$diaElegido];

                    if ($horarioElegido['cerrado']) {
                        $cleanReply = preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean);
                        $cleanReply .= "<br><br>🚫 <b>Ese día la barbería está cerrada.</b> Por favor intenta elegir otro día.";
                        return response()->json(['reply' => $cleanReply]);
                    }

                    $duracionNueva = $servicioReal->duracion_minutos;
                    $finNuevo = $inicioNuevo->copy()->addMinutes($duracionNueva);

                    $horaAperturaSistema = Carbon::parse($fecha . ' ' . $horarioElegido['inicio'] . ':00', $tz);
                    $horaCierreSistema = Carbon::parse($fecha . ' ' . $horarioElegido['fin'] . ':00', $tz);

                    if ($inicioNuevo->lt($horaAperturaSistema)) {
                        $cleanReply = preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean);
                        $cleanReply .= "<br><br>🚫 <b>Aún no abrimos a esa hora.</b> Empezamos a las " . $horaAperturaSistema->format('h:i A') . ".";
                        
                        $history[] = ["role" => "model", "parts" => [["text" => $cleanReply]]];
                        session()->put('chat_history', $history);
                        session()->save();

                        return response()->json(['reply' => $cleanReply]);
                    }

                    if ($finNuevo->gt($horaCierreSistema)) {
                        $cleanReply = preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean);
                        $cleanReply .= "<br><br>🚫 <b>No es posible agendar a esa hora.</b> El servicio termina después de nuestra hora de cierre (" . $horaCierreSistema->format('h:i A') . ").";
                        return response()->json(['reply' => $cleanReply]);
                    }

                    try {
                        $mensajeExito = "";
                        DB::transaction(function () use ($fecha, $inicioNuevo, $finNuevo, $tz, $user, $hora, $servicio, $duracionNueva, &$mensajeExito) {
                            
                            $citasDelDia = Appointment::leftJoin('services', 'appointments.servicio', '=', 'services.nombre')
                                ->where('appointments.fecha', $fecha)
                                ->where('appointments.estado', '!=', 'cancelada')
                                ->lockForUpdate() 
                                ->get(['appointments.hora', 'appointments.duracion_minutos']);

                            foreach ($citasDelDia as $cita) {
                                $inicioExistente = Carbon::parse($fecha . ' ' . $cita->hora, $tz);
                                $duracionExistente = $cita->duracion_minutos ?? 45;
                                $finExistente = $inicioExistente->copy()->addMinutes($duracionExistente);

                                if ($inicioNuevo->lt($finExistente) && $finNuevo->gt($inicioExistente)) {
                                    throw new \Exception('ocupado');
                                }
                            }

                            Appointment::create([
                                'user_id' => $user->id,
                                'fecha' => $fecha,
                                'hora' => $hora,
                                'servicio' => $servicio,
                                'duracion_minutos' => $duracionNueva,
                                'estado' => 'pendiente'
                            ]);

                            $mensajeExito = "<br><br>🎉 <b>¡Cita Confirmada!</b><br>Servicio: <b>{$servicio}</b><br>Fecha: " . $inicioNuevo->format('d/m/Y') . "<br>Hora: <b>" . $inicioNuevo->format('h:i A') . "</b>";
                        });

                        $cleanReply = preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean);
                        
                        $history[] = ["role" => "model", "parts" => [["text" => $cleanReply]]];
                        session()->put('chat_history', $history);
                        session()->save();

                        return response()->json(['reply' => $cleanReply . $mensajeExito]);

                    } catch (\Exception $e) {
                        if ($e->getMessage() == 'ocupado') {
                            $cleanReply = preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean);
                            $cleanReply .= "<br><br>⚠️ <b>¡Ups! Ese horario ya está ocupado.</b> Alguien más reservó hace un segundo. ¿Te gustaría intentar en otra hora?";
                            
                            $history[] = ["role" => "model", "parts" => [["text" => $cleanReply]]];
                            session()->put('chat_history', $history);
                            session()->save();

                            return response()->json(['reply' => $cleanReply]);
                        }
                        throw $e; 
                    }
                }

                $history[] = ["role" => "model", "parts" => [["text" => preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean)]]];
                session()->put('chat_history', $history);
                session()->save();

                return response()->json(['reply' => preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean)]);
            }

            return response()->json(['reply' => 'Error de conexión (' . $response->status() . '). Intenta más tarde. 🔧']);
        } catch (\Exception $e) {
            Log::error('Error en chatbot: ' . $e->getMessage());
            return response()->json(['reply' => 'Error técnico, pero estoy intentando restablecer la conexión. Por favor, envía tu mensaje de nuevo.']);
        }
    }
}
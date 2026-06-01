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

    // 🌟 Función para limpiar la memoria del bot
    public function reset()
    {
        session()->forget('chat_history');
        session()->save();
        return response()->json(['reply' => '🤖 ¡Memoria borrada! He olvidado nuestra plática anterior. ¿En qué te ayudo ahora?']);
    }

    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $userMessage = trim($request->message);
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json(['reply' => '¡Ups! Parece que no tengo mi clave mágica configurada 😅. Contacta al admin.']);
        }

        $user = Auth::user(); 

        if (!$user) {
            return response()->json(['reply' => '¡Hey! Para agendar una cita necesitas iniciar sesión primero 😅.']);
        }

        // 🛡️ ESCUDO LEGAL: VERIFICAR TÉRMINOS Y CONDICIONES
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
                'reply' => "⚠️ <b>¡Vas muy rápido!</b> Por motivos de seguridad, mi cerebro necesita descansar. Por favor, espera {$segundos} segundos antes de enviar otro mensaje. 🤖"
            ]);
        }

        RateLimiter::hit($llave, 60);

        // 🛡️ ESCUDO 2: EL CLIENTE TROLL EN EL BOT
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

        // 🌟 EL CHATBOT APRENDE LOS HORARIOS DINÁMICOS DESDE LA BASE DE DATOS 🌟
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
            $mensajeEstadoHoy = "⛔ ESTADO: HOY ESTAMOS CERRADOS. OBLIGATORIO: No ofrezcas citas para hoy.";
        } elseif ($ahora->gt($horaLimiteRecepcion)) {
            $mensajeEstadoHoy = "⛔ ESTADO: YA NO SE RECIBEN CITAS POR HOY (Hora actual: {$ahora->format('H:i')}, Cierre: {$aperturaHoy['fin']}). Ya no hay tiempo suficiente. OBLIGATORIO: No ofrezcas citas para hoy, solo para MAÑANA.";
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
            $textoOcupadoHoy = empty($intervalosHoy) ? "Ninguna, todo libre" : implode(", ", $intervalosHoy);
            
            $mensajeEstadoHoy = "Abierto de {$aperturaHoy['inicio']} a {$aperturaHoy['fin']}. Bloques Ocupados HOY: [{$textoOcupadoHoy}].";
        }

        // MAÑANA
        if ($aperturaManana['cerrado']) {
            $mensajeEstadoManana = "⛔ ESTADO: MAÑANA ESTAREMOS CERRADOS. OBLIGATORIO: No ofrezcas citas para mañana.";
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
            $mensajeEstadoManana = "Abre de {$aperturaManana['inicio']} a {$aperturaManana['fin']}. Bloques Ocupados MAÑANA: [{$textoOcupadoManana}].";
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

        // 🌟 INSTRUCCIONES MEJORADAS PARA QUE NO OLVIDE EL CONTEXTO
        $systemInstruction = "Eres el asistente virtual de Spoon’s Barber Shop. 
Habla SIEMPRE en español, amigable y profesional. Usa etiquetas <br> y emojis para listas (NUNCA uses asteriscos).

DATOS DE TIEMPO REAL:
- HORA ACTUAL: {$horaActualTexto}.
- Hoy ({$hoy->format('Y-m-d')}): {$mensajeEstadoHoy} 
- Mañana ({$manana->format('Y-m-d')}): {$mensajeEstadoManana}

🚨 REGLAS SUPREMAS DE MEMORIA Y CONVERSACIÓN:
1. REVISA EL HISTORIAL DE LA CONVERSACIÓN: Si el usuario ya te dijo el DÍA y la HORA en mensajes anteriores, NO se los vuelvas a preguntar. Recuérdalos.
2. Si ya tienes el Día y la Hora, pero falta el Corte, solo pregunta: '¿Qué corte te gustaría realizarte?' y en cuanto te lo diga, PROCEDE A AGENDAR.
3. SI EL ESTADO DE HOY DICE 'YA NO SE RECIBEN CITAS' o 'CERRADO', debes decir: 'Por hoy ya estamos cerrados. ¿Te gustaría agendar para mañana?'.
4. Si el usuario pide un servicio informal ('Corte moderno', 'fade'), asócialo al nombre oficial que más se le parezca.

🌟 NUESTROS SERVICIOS Y PRECIOS OFICIALES: 🌟
{$textoServiciosDinamicos}

🔥 INSTRUCCIÓN SECRETA PARA AGENDAR (SOLO cuando tengas FECHA, HORA y SERVICIO):
Imprime EXACTAMENTE este código al final de tu respuesta: 
[AGENDAR|YYYY-MM-DD|HH:MM|NombreDelServicioOficial]
(Hora en formato 24h, ej: 20:00).";

        // 🧠 OBTENER MEMORIA DEL CHAT DE LA SESIÓN
        $history = session()->get('chat_history', []);

        // Añadir solo el mensaje limpio del usuario para no confundir a la IA
        $history[] = [
            "role" => "user", 
            "parts" => [["text" => $userMessage]]
        ];

        try {
            // 🛡️ Retry (3 intentos), pasamos instrucciones de sistema y el historial completo.
            $response = Http::withoutVerifying()
                ->retry(3, 1500)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
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

                    if ($inicioNuevo->isToday()) {
                        $limitePermitido = $ahora->copy()->addMinutes(30);
                        if ($inicioNuevo->lt($limitePermitido)) {
                            $cleanReply = preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean);
                            $cleanReply .= "<br><br>⏱️ <b>¡Lo siento!</b> Necesitamos al menos 30 minutos de anticipación. Por favor, dime un horario un poco más tarde.";
                            return response()->json(['reply' => $cleanReply]);
                        }
                    }

                    $servicioReal = Service::where('nombre', $servicio)->first();
                    if (!$servicioReal) {
                        $cleanReply = preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean);
                        $cleanReply .= "<br><br>🚨 <b>Error de seguridad:</b> El servicio que intentas agendar no existe en nuestro sistema. Por favor elige uno del catálogo.";
                        return response()->json(['reply' => $cleanReply]);
                    }

                    // 🕒 EL BOT VERIFICA QUE LA BARBERÍA ESTÉ ABIERTA ESE DÍA ESPECÍFICO
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
                        $cleanReply .= "<br><br>🚫 <b>Aún no abrimos a esa hora.</b><br>Ese día empezamos a trabajar a las " . $horaAperturaSistema->format('h:i A') . ".";
                        return response()->json(['reply' => $cleanReply]);
                    }

                    if ($finNuevo->gt($horaCierreSistema)) {
                        $cleanReply = preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean);
                        $cleanReply .= "<br><br>🚫 <b>No es posible agendar a esa hora.</b><br>El servicio '{$servicio}' dura {$duracionNueva} min y terminaría a las " . $finNuevo->format('h:i A') . ".<br>Nosotros cerramos a las " . $horaCierreSistema->format('h:i A') . ".";
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
                        
                        // Guardar respuesta final en la memoria con un push asegurado
                        $history[] = ["role" => "model", "parts" => [["text" => $cleanReply]]];
                        session()->put('chat_history', $history);
                        session()->save();

                        return response()->json(['reply' => $cleanReply . $mensajeExito]);

                    } catch (\Exception $e) {
                        if ($e->getMessage() == 'ocupado') {
                            $cleanReply = preg_replace('/\[AGENDAR\|.*\]/', '', $replyClean);
                            $cleanReply .= "<br><br>⚠️ <b>¡Ups! Ese horario ya está ocupado.</b><br>Alguien más reservó ese bloque hace un segundo. ¿Te gustaría intentar en otra hora?";
                            
                            $history[] = ["role" => "model", "parts" => [["text" => $cleanReply]]];
                            session()->put('chat_history', $history);
                            session()->save();

                            return response()->json(['reply' => $cleanReply]);
                        }
                        throw $e; 
                    }
                }

                // Si no mandó a agendar nada, guardamos la respuesta normal en la memoria
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
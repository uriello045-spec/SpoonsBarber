<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Service; 
use App\Models\Setting; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Notifications\AppointmentStatusNotification;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 🛠️ CORRECCIÓN 1: Redirigir al panel de administración si es barbero O superadmin
        if ($user->role === 'barbero' || $user->role === 'superadmin' || $user->is_superadmin) {
            return redirect()->route('admin.appointments');
        }

        $appointments = Appointment::where('user_id', $user->id)
                                   ->whereIn('estado', ['pendiente', 'confirmada'])
                                   ->orderBy('fecha', 'asc')
                                   ->orderBy('hora', 'asc')
                                   ->get();
        
        $services = Service::all(); 
        $shopStatus = Setting::firstOrCreate(['key' => 'shop_status'], ['value' => 'open']);
        
        $horarios = [
            'semana' => [
                'apertura' => Setting::where('key', 'apertura_semana')->value('value') ?? '08:00',
                'cierre' => Setting::where('key', 'cierre_semana')->value('value') ?? '21:00',
                'cerrado' => Setting::where('key', 'cerrado_semana')->value('value') === 'true',
                'texto' => Setting::where('key', 'horario_semana')->value('value') ?? '08:00 AM - 09:00 PM'
            ],
            'sabado' => [
                'apertura' => Setting::where('key', 'apertura_sabado')->value('value') ?? '08:00',
                'cierre' => Setting::where('key', 'cierre_sabado')->value('value') ?? '21:00',
                'cerrado' => Setting::where('key', 'cerrado_sabado')->value('value') === 'true',
                'texto' => Setting::where('key', 'horario_sabado')->value('value') ?? '08:00 AM - 09:00 PM'
            ],
            'domingo' => [
                'apertura' => Setting::where('key', 'apertura_domingo')->value('value') ?? '08:00',
                'cierre' => Setting::where('key', 'cierre_domingo')->value('value') ?? '21:00',
                'cerrado' => Setting::where('key', 'cerrado_domingo')->value('value') === 'true',
                'texto' => Setting::where('key', 'horario_domingo')->value('value') ?? '08:00 AM - 09:00 PM'
            ]
        ];
        
        return view('appointments.index', compact('appointments', 'services', 'shopStatus', 'horarios'));
    }

    public function adminIndex()
    {
        $user = Auth::user();

        // 🛠️ CORRECCIÓN 2: Permitir el acceso si es barbero O superadmin
        if ($user->role !== 'barbero' && $user->role !== 'superadmin' && !$user->is_superadmin) {
            abort(403, 'Acceso solo para personal de la barbería.');
        }

        $appointments = Appointment::with('user')
                                   ->whereIn('estado', ['pendiente', 'confirmada'])
                                   ->orderBy('fecha', 'asc')
                                   ->orderBy('hora', 'asc')
                                   ->get();

        $services = Service::all(); 

        return view('admin.appointments', compact('appointments', 'services'));
    }

    public function store(Request $request)
    {
        $shopStatus = Setting::where('key', 'shop_status')->first();
        if ($shopStatus && $shopStatus->value === 'closed') {
            return back()->withErrors('Lo sentimos, la barbería se encuentra cerrada temporalmente.');
        }

        $request->validate([
            'fecha' => 'required|date|after_or_equal:today|before_or_equal:+60 days', 
            'hora' => 'required',
            'servicio' => 'required|string|max:255|exists:services,nombre', 
        ]);

        $citasPendientes = Appointment::where('user_id', Auth::id())->where('estado', 'pendiente')->count();
        if ($citasPendientes >= 2) {
            return back()->withErrors('🛡️ Tienes demasiadas citas pendientes. Por favor asiste o cancela alguna antes de agendar otra.');
        }

        $tz = 'America/Mexico_City';
        $fechaHoraSolicitada = \Carbon\Carbon::parse($request->fecha . ' ' . $request->hora, $tz);
        $ahora = \Carbon\Carbon::now($tz);

        if ($fechaHoraSolicitada->isPast()) {
            return back()->withErrors('El horario seleccionado ya pasó. Por favor elige otro.');
        }

        if ($fechaHoraSolicitada->isToday()) {
            $limitePermitido = $ahora->copy()->addMinutes(30);
            if ($fechaHoraSolicitada->lt($limitePermitido)) {
                return back()->withErrors('Debes agendar con al menos 30 minutos de anticipación.');
            }
        }
        
        $diaSemana = $fechaHoraSolicitada->dayOfWeek; 
        
        if ($diaSemana == 0) { 
            $aperturaStr = Setting::where('key', 'apertura_domingo')->value('value') ?? '08:00';
            $cierreStr = Setting::where('key', 'cierre_domingo')->value('value') ?? '21:00';
            $isCerrado = Setting::where('key', 'cerrado_domingo')->value('value') == 'true';
        } elseif ($diaSemana == 6) { 
            $aperturaStr = Setting::where('key', 'apertura_sabado')->value('value') ?? '08:00';
            $cierreStr = Setting::where('key', 'cierre_sabado')->value('value') ?? '21:00';
            $isCerrado = Setting::where('key', 'cerrado_sabado')->value('value') == 'true';
        } else { 
            $aperturaStr = Setting::where('key', 'apertura_semana')->value('value') ?? '08:00';
            $cierreStr = Setting::where('key', 'cierre_semana')->value('value') ?? '21:00';
            $isCerrado = Setting::where('key', 'cerrado_semana')->value('value') == 'true';
        }

        if ($isCerrado) {
            return back()->withErrors('Lo sentimos, ese día la barbería está cerrada.')->withInput();
        }

        $horaApertura = \Carbon\Carbon::parse($request->fecha . ' ' . $aperturaStr . ':00', $tz);
        $horaCierre = \Carbon\Carbon::parse($request->fecha . ' ' . $cierreStr . ':00', $tz);

        if ($fechaHoraSolicitada->lt($horaApertura)) {
            return back()->withErrors(['hora' => 'Ese día abrimos a las ' . $horaApertura->format('h:i A') . '.'])->withInput();
        }

        $servicioData = Service::where('nombre', $request->servicio)->first();
        $duracion = $servicioData ? $servicioData->duracion_minutos : 30;
        $horaFin = $fechaHoraSolicitada->copy()->addMinutes($duracion);

        if ($horaFin->gt($horaCierre)) {
            return back()->withErrors(['hora' => 'El servicio dura ' . $duracion . ' min y termina después del cierre (' . $horaCierre->format('h:i A') . ').'])->withInput();
        }

        $inicioNuevo = \Carbon\Carbon::parse($request->hora);
        $finNuevo = $inicioNuevo->copy()->addMinutes($duracion);

        try {
            DB::transaction(function () use ($request, $inicioNuevo, $finNuevo, $duracion) {
                $citasDelDia = Appointment::leftJoin('services', 'appointments.servicio', '=', 'services.nombre')
                    ->where('appointments.fecha', $request->fecha)
                    ->where('appointments.estado', '!=', 'cancelada')
                    ->lockForUpdate() 
                    ->get(['appointments.hora', 'appointments.duracion_minutos']);

                foreach ($citasDelDia as $cita) {
                    $inicioExistente = \Carbon\Carbon::parse($cita->hora);
                    $duracionExistente = $cita->duracion_minutos ?? 45;
                    $finExistente = $inicioExistente->copy()->addMinutes($duracionExistente);

                    if ($inicioNuevo->lt($finExistente) && $finNuevo->gt($inicioExistente)) {
                        throw new \Exception('El horario de ' . $inicioNuevo->format('H:i') . ' interfiere con otra cita.');
                    }
                }

                Appointment::create([
                    'user_id' => Auth::id(), 
                    'fecha' => $request->fecha, 
                    'hora' => $request->hora,
                    'servicio' => $request->servicio, 
                    'duracion_minutos' => $duracion, 
                    'estado' => 'pendiente',
                ]);
            });

            return back()->with('success', 'Cita agendada correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $user = Auth::user();

        // 🛠️ NOTA: Aquí está bien, porque los súperadmin NO son rol 'cliente', entonces pueden editar
        if ($user->role === 'cliente' && $appointment->user_id !== $user->id) {
            abort(403, 'No tienes permiso para editar esta cita.');
        }

        if ($request->has('estado') && !$request->has('fecha')) {
            $nuevoEstado = $request->input('estado');
            $appointment->estado = $nuevoEstado;
            $appointment->save();

            if (in_array($nuevoEstado, ['confirmada', 'cancelada', 'completada'])) {
                $appointment->load('user'); 
                $appointment->user->notify(new AppointmentStatusNotification($appointment, $nuevoEstado));
            }
            
            $msg = $nuevoEstado == 'completada' ? '✅ Corte finalizado. Se ha ocultado de la Agenda y guardado en Estadísticas.' : 'Estado actualizado.';
            return back()->with('success', $msg);
        }

        $validated = $request->validate([
            'fecha' => 'required|date|after_or_equal:today|before_or_equal:+60 days',
            'hora' => 'required', 'servicio' => 'required|string|max:255|exists:services,nombre',
        ]);

        $tz = 'America/Mexico_City';
        $fechaHoraSolicitada = \Carbon\Carbon::parse($request->fecha . ' ' . $request->hora, $tz);
        $ahora = \Carbon\Carbon::now($tz);

        if ($fechaHoraSolicitada->isPast()) return back()->withErrors('El horario ya pasó.');
        if ($fechaHoraSolicitada->isToday() && $fechaHoraSolicitada->lt($ahora->copy()->addMinutes(30))) {
            return back()->withErrors('Debes reprogramar con 30 min de anticipación.');
        }
        
        $diaSemana = $fechaHoraSolicitada->dayOfWeek; 
        if ($diaSemana == 0) { 
            $aperturaStr = Setting::where('key', 'apertura_domingo')->value('value') ?? '08:00';
            $cierreStr = Setting::where('key', 'cierre_domingo')->value('value') ?? '21:00';
            $isCerrado = Setting::where('key', 'cerrado_domingo')->value('value') == 'true';
        } elseif ($diaSemana == 6) { 
            $aperturaStr = Setting::where('key', 'apertura_sabado')->value('value') ?? '08:00';
            $cierreStr = Setting::where('key', 'cierre_sabado')->value('value') ?? '21:00';
            $isCerrado = Setting::where('key', 'cerrado_sabado')->value('value') == 'true';
        } else { 
            $aperturaStr = Setting::where('key', 'apertura_semana')->value('value') ?? '08:00';
            $cierreStr = Setting::where('key', 'cierre_semana')->value('value') ?? '21:00';
            $isCerrado = Setting::where('key', 'cerrado_semana')->value('value') == 'true';
        }

        if ($isCerrado) return back()->withErrors('Ese día la barbería está cerrada.')->withInput();

        $horaApertura = \Carbon\Carbon::parse($request->fecha . ' ' . $aperturaStr . ':00', $tz);
        $horaCierre = \Carbon\Carbon::parse($request->fecha . ' ' . $cierreStr . ':00', $tz);

        if ($fechaHoraSolicitada->lt($horaApertura)) return back()->withErrors(['hora' => 'Ese día abrimos a las ' . $horaApertura->format('h:i A') . '.'])->withInput();

        $servicioData = Service::where('nombre', $request->servicio)->first();
        $duracion = $servicioData ? $servicioData->duracion_minutos : 30;
        $horaFin = $fechaHoraSolicitada->copy()->addMinutes($duracion);

        if ($horaFin->gt($horaCierre)) return back()->withErrors(['hora' => 'El servicio excede el horario de cierre.'])->withInput();

        $inicioNuevo = \Carbon\Carbon::parse($request->hora);
        $finNuevo = $inicioNuevo->copy()->addMinutes($duracion);

        try {
            DB::transaction(function () use ($request, $id, $inicioNuevo, $finNuevo, $appointment, $validated, $duracion) {
                $citasDelDia = Appointment::leftJoin('services', 'appointments.servicio', '=', 'services.nombre')
                    ->where('appointments.fecha', $request->fecha)->where('appointments.estado', '!=', 'cancelada')
                    ->where('appointments.id', '!=', $id)->lockForUpdate()->get(['appointments.hora', 'appointments.duracion_minutos']);

                foreach ($citasDelDia as $citaExistente) {
                    $inicioExistente = \Carbon\Carbon::parse($citaExistente->hora);
                    $duracionExistente = $citaExistente->duracion_minutos ?? 45;
                    $finExistente = $inicioExistente->copy()->addMinutes($duracionExistente);

                    if ($inicioNuevo->lt($finExistente) && $finNuevo->gt($inicioExistente)) {
                        throw new \Exception('El nuevo horario interfiere con otra cita programada.');
                    }
                }
                
                $validated['duracion_minutos'] = $duracion; 
                $appointment->update($validated);
            });
            return redirect()->route('appointments.index')->with('success', '¡Cita reprogramada!');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    // 🌟 AQUÍ ESTÁ LA MAGIA: LA FUNCIÓN DESTROY ACTUALIZADA 🌟
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        
        if (Auth::user()->role === 'cliente' && $appointment->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso.');
        }

        // Enviamos la notificación ANTES de borrar la cita
        $appointment->load('user'); 
        if ($appointment->user) {
            $appointment->user->notify(new AppointmentStatusNotification($appointment, 'cancelada'));
        }

        // Ahora sí eliminamos
        $appointment->delete();
        
        return back()->with('success', 'Cita eliminada y cliente notificado.');
    }
    
    public function edit($id)
    {
        $appointment = Appointment::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $services = Service::all();
        
        $horarios = [
            'semana' => [ 'apertura' => Setting::where('key', 'apertura_semana')->value('value') ?? '08:00', 'cierre' => Setting::where('key', 'cierre_semana')->value('value') ?? '21:00', 'cerrado' => Setting::where('key', 'cerrado_semana')->value('value') === 'true', 'texto' => Setting::where('key', 'horario_semana')->value('value') ?? '08:00 AM - 09:00 PM' ],
            'sabado' => [ 'apertura' => Setting::where('key', 'apertura_sabado')->value('value') ?? '08:00', 'cierre' => Setting::where('key', 'cierre_sabado')->value('value') ?? '21:00', 'cerrado' => Setting::where('key', 'cerrado_sabado')->value('value') === 'true', 'texto' => Setting::where('key', 'horario_sabado')->value('value') ?? '08:00 AM - 09:00 PM' ],
            'domingo' => [ 'apertura' => Setting::where('key', 'apertura_domingo')->value('value') ?? '08:00', 'cierre' => Setting::where('key', 'cierre_domingo')->value('value') ?? '21:00', 'cerrado' => Setting::where('key', 'cerrado_domingo')->value('value') === 'true', 'texto' => Setting::where('key', 'horario_domingo')->value('value') ?? '08:00 AM - 09:00 PM' ]
        ];

        return view('appointments.edit', compact('appointment', 'services', 'horarios'));
    }

    public function storeExpress(Request $request)
    {
        $request->validate(['servicio' => 'required|string|max:255']);

        $tz = 'America/Mexico_City';
        $ahora = \Carbon\Carbon::now($tz);

        // 🛡️ ESCUDO 1: Verificar si el barbero ya está ocupado en este preciso momento
        $citasDeHoy = Appointment::where('fecha', $ahora->format('Y-m-d'))
            ->whereNotIn('estado', ['cancelada', 'completada'])
            ->get();

        foreach ($citasDeHoy as $cita) {
            $inicioExistente = \Carbon\Carbon::parse($cita->fecha . ' ' . $cita->hora, $tz);
            $duracionExistente = $cita->duracion_minutos ?? 45;
            $finExistente = $inicioExistente->copy()->addMinutes($duracionExistente);

            if ($ahora->gte($inicioExistente) && $ahora->lt($finExistente)) {
                $minutosRestantes = (int) ceil($ahora->diffInSeconds($finExistente) / 60);
                
                return back()->withErrors("🚨 ¡Agenda Bloqueada! Ya hay un servicio en curso que termina en aprox. {$minutosRestantes} min. Finalízalo para poder registrar otro.");
            }
        }

        $walkInUser = \App\Models\User::firstOrCreate(
            ['email' => 'sucursal@spoonsbarber.com'],
            ['name' => 'Cliente Físico', 'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)), 'role' => 'cliente']
        );

        $servicioData = Service::where('nombre', $request->servicio)->first();
        $duracion = $servicioData ? $servicioData->duracion_minutos : 45;

        Appointment::create([
            'user_id' => $walkInUser->id,
            'fecha' => $ahora->format('Y-m-d'),
            'hora' => $ahora->format('H:i'),
            'servicio' => $request->servicio,
            'duracion_minutos' => $duracion, 
            'estado' => 'confirmada', 
        ]);

        return back()->with('success', "⚡ ¡Corte Express registrado! La agenda se ha bloqueado por {$duracion} minutos.");
    }

    public function validateTime(Request $request)
    {
        $date = $request->date;
        $time = $request->time;
        $servicioNombre = $request->servicio ?? ''; 

        if (!$date || !$time) {
            return response()->json(['valid' => false, 'message' => 'Por favor selecciona fecha y hora.']);
        }

        $tz = 'America/Mexico_City';
        $fechaHoraSolicitada = \Carbon\Carbon::parse($date . ' ' . $time, $tz);
        $ahora = \Carbon\Carbon::now($tz);
        
        if ($fechaHoraSolicitada->gt($ahora->copy()->addDays(60))) {
            return response()->json(['valid' => false, 'message' => '🚫 Demasiado lejos. Máximo 2 meses de anticipación.']);
        }

        if ($fechaHoraSolicitada->isPast()) {
            return response()->json(['valid' => false, 'message' => '🚫 Esta hora ya pasó. Por favor elige un horario futuro.']);
        }

        if ($fechaHoraSolicitada->isToday()) {
            $limitePermitido = $ahora->copy()->addMinutes(30);
            if ($fechaHoraSolicitada->lt($limitePermitido)) {
                return response()->json(['valid' => false, 'message' => '⏱️ Debes agendar con al menos 30 minutos de anticipación.']);
            }
        }

        $diaSemana = $fechaHoraSolicitada->dayOfWeek; 
        
        if ($diaSemana == 0) {
            $aperturaStr = Setting::where('key', 'apertura_domingo')->value('value') ?? '08:00';
            $cierreStr = Setting::where('key', 'cierre_domingo')->value('value') ?? '21:00';
            $isCerrado = Setting::where('key', 'cerrado_domingo')->value('value') == 'true';
        } elseif ($diaSemana == 6) {
            $aperturaStr = Setting::where('key', 'apertura_sabado')->value('value') ?? '08:00';
            $cierreStr = Setting::where('key', 'cierre_sabado')->value('value') ?? '21:00';
            $isCerrado = Setting::where('key', 'cerrado_sabado')->value('value') == 'true';
        } else {
            $aperturaStr = Setting::where('key', 'apertura_semana')->value('value') ?? '08:00';
            $cierreStr = Setting::where('key', 'cierre_semana')->value('value') ?? '21:00';
            $isCerrado = Setting::where('key', 'cerrado_semana')->value('value') == 'true';
        }

        if ($isCerrado) {
            return response()->json(['valid' => false, 'message' => '🚫 Ese día la barbería se encuentra cerrada.']);
        }

        $servicioDB = Service::where('nombre', $servicioNombre)->first();
        $duracion = 45; 
        if ($servicioDB) {
            $duracion = $servicioDB->duracion_minutos;
        } else {
            $sLower = strtolower($servicioNombre);
            if (str_contains($sLower, 'combo') || str_contains($sLower, 'diseño') || str_contains($sLower, 'greca')) $duracion = 60;
            elseif (str_contains($sLower, 'barba') || str_contains($sLower, 'ceja')) $duracion = 30;
        }

        $horaFinCalculada = $fechaHoraSolicitada->copy()->addMinutes($duracion);
        $horaAperturaSistema = \Carbon\Carbon::parse($date . ' ' . $aperturaStr . ':00', $tz);
        $horaCierreSistema = \Carbon\Carbon::parse($date . ' ' . $cierreStr . ':00', $tz);

        if ($fechaHoraSolicitada->lt($horaAperturaSistema)) {
            return response()->json(['valid' => false, 'message' => "🚫 Ese día abrimos a las " . $horaAperturaSistema->format('h:i A') . "."]);
        }

        if ($horaFinCalculada->gt($horaCierreSistema)) {
            return response()->json([
                'valid' => false, 
                'message' => "🚫 El servicio dura {$duracion} min y termina a las " . $horaFinCalculada->format('h:i A') . ". Cerramos a las " . $horaCierreSistema->format('h:i A') . "."
            ]);
        }

        $citasDelDia = DB::table('appointments')
            ->leftJoin('services', 'appointments.servicio', '=', 'services.nombre')
            ->where('appointments.fecha', $date)
            ->where('appointments.estado', '!=', 'cancelada')
            ->get(['appointments.hora', 'appointments.duracion_minutos']);

        $inicioNuevo = \Carbon\Carbon::parse($time);
        $finNuevo = $inicioNuevo->copy()->addMinutes($duracion); 

        foreach ($citasDelDia as $cita) {
            $inicioExistente = \Carbon\Carbon::parse($cita->hora);
            $duracionExistente = $cita->duracion_minutos ?? 45;
            $finExistente = $inicioExistente->copy()->addMinutes($duracionExistente);

            if ($inicioNuevo->lt($finExistente) && $finNuevo->gt($inicioExistente)) {
                return response()->json(['valid' => false, 'message' => '⚠️ Horario ocupado de ' . $inicioExistente->format('H:i') . ' a ' . $finExistente->format('H:i') . '.']);
            }
        }

        return response()->json(['valid' => true, 'message' => '✅ ¡Horario libre! Puedes confirmar tu cita.']);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage; 
use App\Models\Appointment;
use App\Models\Reference;
use App\Models\ChatbotResponse;
use App\Models\User;
use App\Models\Service; 
use App\Models\Setting; 
use App\Models\Gallery; 

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            /** @var \App\Models\User|null $user */
            $user = $request->user();
            // 🛠️ BLINDAJE 1: Permitir el paso si es barbero o superadmin
            if ($user && $user->role !== 'barbero' && $user->role !== 'superadmin' && !$user->is_superadmin) {
                abort(403, '🚨 ACCESO DENEGADO: Intento de intrusión detectado. Área exclusiva del staff.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $citas = Appointment::with('user')
                            ->whereIn('estado', ['pendiente', 'confirmada'])
                            ->latest()
                            ->take(5)
                            ->get();
                            
        $reseñas = Reference::with('user')->latest()->take(5)->get();
        $clientes = User::where('role', 'cliente')->count();
        $shopStatus = Setting::firstOrCreate(['key' => 'shop_status'], ['value' => 'open']);

        return view('admin.dashboard', compact('citas', 'reseñas', 'clientes', 'shopStatus'));
    }

    public function toggleShop()
    {
        $status = Setting::firstOrCreate(['key' => 'shop_status'], ['value' => 'open']);
        $status->value = ($status->value === 'open') ? 'closed' : 'open';
        $status->save();

        $mensaje = $status->value === 'open' ? '✅ Barbería ABIERTA. Ya pueden agendar.' : '🛑 Barbería CERRADA. Se pausaron las reservas.';
        return back()->with('success', $mensaje);
    }

    public function statistics()
    {
        $totalCitas = Appointment::count();
        $citasCompletadas = Appointment::where('estado', 'completada')->count();
        $citasCanceladas = Appointment::where('estado', 'cancelada')->count();
        
        $ingresos = Appointment::where('appointments.estado', 'completada')
                    ->join('services', 'appointments.servicio', '=', 'services.nombre')
                    ->sum('services.precio');

        $perdidas = Appointment::where('appointments.estado', 'cancelada')
                    ->join('services', 'appointments.servicio', '=', 'services.nombre')
                    ->sum('services.precio');

        $topCliente = Appointment::select('user_id')
            ->selectRaw('count(*) as total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->with('user')
            ->first();

        $citasPorEstado = Appointment::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')->toArray();

        $ingresosPorMes = Appointment::where('appointments.estado', 'completada')
            ->join('services', 'appointments.servicio', '=', 'services.nombre')
            ->select(
                DB::raw('SUBSTR(appointments.fecha, 1, 7) as mes'),
                DB::raw('SUM(services.precio) as total')
            )
            ->groupBy('mes')
            ->orderBy('mes', 'ASC')
            ->take(6)
            ->pluck('total', 'mes')->toArray();

        return view('admin.statistics', compact('totalCitas', 'citasCompletadas', 'citasCanceladas', 'ingresos', 'perdidas', 'topCliente', 'citasPorEstado', 'ingresosPorMes'));
    }

    public function chatbotManager()
    {
        $respuestas = ChatbotResponse::all();
        return view('admin.chatbot.index', compact('respuestas'));
    }

    public function storeChatbotResponse(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|unique:chatbot_responses,keyword',
            'response' => 'required|string'
        ]);

        ChatbotResponse::create($request->only('keyword', 'response'));
        return back()->with('success', 'Respuesta agregada correctamente.');
    }

    public function updateChatbotResponse(Request $request, $id)
    {
        $respuesta = ChatbotResponse::findOrFail($id);
        $request->validate([
            'keyword' => 'required|string|unique:chatbot_responses,keyword,' . $id,
            'response' => 'required|string'
        ]);

        $respuesta->update($request->only('keyword', 'response'));
        return back()->with('success', 'Respuesta actualizada correctamente.');
    }

    public function deleteChatbotResponse($id)
    {
        $respuesta = ChatbotResponse::findOrFail($id);
        $respuesta->delete();
        return back()->with('success', 'Respuesta eliminada correctamente.');
    }

    public function barbers()
    {
        $barbers = User::where('role', 'barbero')->latest()->get();
        return view('admin.barbers.index', compact('barbers'));
    }

    public function barbersCreate()
    {
        return view('admin.barbers.create');
    }

    public function barbersStore(Request $request)
    {
        // 🛠️ BLINDAJE: Validación segura en array cuando hay Expresiones Regulares complejas
        $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|numeric|digits:10',
            'password' => 'required|string|min:8|max:20|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone, 
            'password' => Hash::make($request->password),
            'role'     => 'barbero', 
            'is_superadmin' => false,
            'email_verified_at' => now(), 
        ]);

        return redirect()->route('admin.barbers.index')->with('success', '¡Nuevo barbero registrado exitosamente! ✂️');
    }

    public function barbersEdit(Request $request, $id)
    {
        $barber = User::where('role', 'barbero')->findOrFail($id);
        /** @var User $currentUser */
        $currentUser = $request->user();

        if ($barber->is_superadmin && !$currentUser->is_superadmin) {
            abort(403, '🛡️ ACCESO DENEGADO: No tienes permisos para editar la cuenta maestra.');
        }

        return view('admin.barbers.edit', compact('barber'));
    }

    public function barbersUpdate(Request $request, $id)
    {
        $barber = User::where('role', 'barbero')->findOrFail($id);
        /** @var User $currentUser */
        $currentUser = $request->user();

        if ($barber->is_superadmin && !$currentUser->is_superadmin) {
            abort(403, '🛡️ ACCESO DENEGADO: Intento de modificación bloqueado.');
        }

        $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u'],
            'email'    => 'required|email|unique:users,email,' . $barber->id,
            'phone'    => 'nullable|numeric|digits:10',
            'password' => 'nullable|string|min:8|max:20', 
        ]);

        $barber->name = $request->name;
        $barber->email = $request->email;
        $barber->phone = $request->phone;

        if ($request->filled('password')) {
            $barber->password = Hash::make($request->password);
        }

        $barber->save();
        return redirect()->route('admin.barbers.index')->with('success', '¡Datos del barbero actualizados correctamente!');
    }

    public function barbersDestroy(Request $request, $id)
    {
        try {
            $cuentaAEliminar = User::findOrFail($id);
            /** @var User $yoLogueado */
            $yoLogueado = $request->user();
            
            if (!$yoLogueado->is_superadmin || !Hash::check($request->superadmin_password, $yoLogueado->password)) {
                return response()->json(['success' => false, 'message' => 'Contraseña incorrecta o no tienes permisos de SuperAdmin.'], 403);
            }

            if ($cuentaAEliminar->is_superadmin) {
                return response()->json(['success' => false, 'message' => 'Acción bloqueada: No puedes eliminar una cuenta maestra.'], 403);
            }

            $cuentaAEliminar->delete();
            return response()->json(['success' => true, 'message' => 'Cuenta eliminada exitosamente con autorización del SuperAdmin.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Ocurrió un error en el servidor.'], 500);
        }
    }

    public function storeGallery(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp', 
        ]);

        try {
            $file = $request->file('foto');
            
            if (!$file->isValid()) {
                return response()->json(['success' => false, 'message' => '🚨 El archivo es demasiado pesado o está corrupto.'], 400); 
            }

            // 🛠️ BLINDAJE DE ALMACENAMIENTO: Usar el Storage oficial de Laravel
            $rutaTemporal = $file->getPathname();
            $md5Subida = md5_file($rutaTemporal);
            $fotosExistentes = Gallery::where('activa', true)->get();
            
            foreach ($fotosExistentes as $fotoExistente) {
                if (empty($fotoExistente->imagen)) continue;
                
                if (Storage::disk('public')->exists($fotoExistente->imagen)) {
                    $rutaFisica = Storage::disk('public')->path($fotoExistente->imagen);
                    if (md5_file($rutaFisica) === $md5Subida) {
                        return response()->json(['success' => false, 'message' => '🚨 ¡Esa foto ya existe en la galería! Sube una imagen diferente.'], 400); 
                    }
                }
            }

            // Guardado nativo y seguro de Laravel
            $path = $file->store('galeria', 'public');

            if(class_exists('\App\Models\Gallery')) {
                Gallery::create(['imagen' => $path, 'activa' => true]);
            }

            return response()->json(['success' => true, 'message' => '¡Foto subida exitosamente a la galería!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al guardar la foto.'], 500);
        }
    }

    public function destroyGallery($id)
    {
        try {
            $foto = Gallery::findOrFail($id);
            
            // 🛠️ BLINDAJE: Borrado seguro usando el Storage de Laravel
            if ($foto->imagen && Storage::disk('public')->exists($foto->imagen)) {
                Storage::disk('public')->delete($foto->imagen);
            }
            
            $foto->delete();
            return response()->json(['success' => true, 'message' => '¡Foto eliminada de la galería exitosamente!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Hubo un error al intentar eliminar la foto.'], 500);
        }
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:3|max:50', 
            'precio' => 'required|numeric|min:1|max:9999', 
            'categoria' => 'required|string',
            'duracion' => 'required|integer|min:30|max:60', 
            'descripcion' => 'nullable|string|max:120', 
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp', 
        ], [
            'duracion.min' => 'El servicio no puede durar menos de 30 minutos.',
            'duracion.max' => 'El servicio no puede exceder los 60 minutos (1 hora).',
            'precio.max' => 'El precio ingresado es irreal. Verifica la cantidad.',
        ]);

        try {
            $path = null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                if (!$file->isValid()) return response()->json(['success' => false, 'message' => '🚨 Archivo bloqueado o muy pesado.'], 400); 
                
                $rutaTemporal = $file->getPathname();
                $md5Subida = md5_file($rutaTemporal);
                $serviciosExistentes = Service::whereNotNull('imagen')->get();
                
                foreach ($serviciosExistentes as $servicioExistente) {
                    if (empty($servicioExistente->imagen)) continue;
                    
                    if (Storage::disk('public')->exists($servicioExistente->imagen)) {
                        $rutaFisica = Storage::disk('public')->path($servicioExistente->imagen);
                        if (md5_file($rutaFisica) === $md5Subida) {
                            return response()->json(['success' => false, 'message' => '🚨 Esta foto ya está siendo usada en "' . $servicioExistente->nombre . '". Sube una diferente.'], 400); 
                        }
                    }
                }
                
                // Guardado seguro en disco público
                $path = $file->store('galeria', 'public');
            }

            Service::create([
                'nombre' => $request->nombre,
                'precio' => $request->precio,
                'duracion_minutos' => $request->duracion,
                'categoria' => $request->categoria,
                'descripcion' => $request->descripcion,
                'imagen' => $path,
            ]);

            return response()->json(['success' => true, 'message' => '¡Servicio agregado al catálogo!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al guardar el servicio: ' . $e->getMessage()], 500);
        }
    }

    public function destroyService($id)
    {
        $servicio = Service::findOrFail($id);
        
        // 🛠️ BLINDAJE: Borrado seguro usando el Storage
        if ($servicio->imagen && Storage::disk('public')->exists($servicio->imagen)) {
            Storage::disk('public')->delete($servicio->imagen);
        }
        
        $servicio->delete();

        return response()->json(['success' => true, 'message' => 'Servicio eliminado correctamente de la Base de Datos.']);
    }

    public function updateSettings(Request $request)
    {
        $rules = [
            'apertura_semana' => 'nullable', 'cierre_semana' => 'nullable',
            'apertura_sabado' => 'nullable', 'cierre_sabado' => 'nullable',
            'apertura_domingo' => 'nullable', 'cierre_domingo' => 'nullable',
        ];
        
        if ($request->has('precio_corte')) $rules['precio_corte'] = 'numeric|min:0';
        if ($request->has('precio_barba')) $rules['precio_barba'] = 'numeric|min:0';
        if ($request->has('precio_ceja')) $rules['precio_ceja'] = 'numeric|min:0';
        if ($request->has('precio_greca')) $rules['precio_greca'] = 'numeric|min:0';

        $request->validate($rules);

        try {
            $settings = $request->except('_token');
            
            $formatTime = function($start, $end, $isClosed) {
                if ($isClosed === 'true' || $isClosed === true) return 'Cerrado';
                return \Carbon\Carbon::parse($start)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($end)->format('h:i A');
            };

            if ($request->has('apertura_semana')) {
                $settings['horario_semana'] = $formatTime($request->apertura_semana, $request->cierre_semana, $request->cerrado_semana ?? 'false');
                $settings['horario_sabado'] = $formatTime($request->apertura_sabado, $request->cierre_sabado, $request->cerrado_sabado ?? 'false');
                $settings['horario_domingo'] = $formatTime($request->apertura_domingo, $request->cierre_domingo, $request->cerrado_domingo ?? 'false');
            }

            foreach ($settings as $key => $value) {
                if ($value !== null) {
                    Setting::updateOrCreate(['key' => $key], ['value' => $value]);
                }
            }

            if ($request->has('precio_corte')) {
                Service::whereIn('categoria', ['Clásico', 'Clasico', 'Moderno'])->update(['precio' => $request->precio_corte]);
            }
            if ($request->has('precio_barba')) {
                Service::where('nombre', 'like', '%barba%')->update(['precio' => $request->precio_barba]);
            }
            if ($request->has('precio_ceja')) {
                Service::where('nombre', 'like', '%ceja%')->update(['precio' => $request->precio_ceja]);
            }
            if ($request->has('precio_greca')) {
                Service::where(function($q) {
                    $q->where('nombre', 'like', '%greca%')
                      ->orWhere('nombre', 'like', '%diseño%')
                      ->orWhere('nombre', 'like', '%diseno%');
                })->update(['precio' => $request->precio_greca]);
            }

            return response()->json(['success' => true, 'message' => '¡Datos actualizados correctamente y catálogo sincronizado!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Hubo un error al guardar los datos: ' . $e->getMessage()], 500);
        }
    }

    public function verifyMaster(Request $request)
    {
        try {
            /** @var \App\Models\User|null $yo */
            $yo = $request->user();
            if ($yo && $yo->is_superadmin && Hash::check($request->password, $yo->password)) {
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'message' => 'Contraseña incorrecta o sin permisos.'], 403);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Ocurrió un error en el servidor.'], 500);
        }
    }
}
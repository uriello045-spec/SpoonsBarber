<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reference;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class ReferenceController extends Controller
{
    public function index()
    {
        $references = Reference::with('user')->latest()->get();
        
        $puedeComentar = false;
        $citaElegible = null;

        // 🛡️ LÓGICA DE RESEÑAS VERIFICADAS (Solo clientes con cita reciente)
        if (Auth::check()) {
            // 1. Buscamos una cita completada en las últimas 24 horas
            $citaElegible = Appointment::where('user_id', Auth::id())
                ->where('estado', 'completada')
                ->where('updated_at', '>=', now()->subHours(24))
                ->latest('updated_at')
                ->first();

            // 2. Si tiene una cita reciente, verificamos que NO la haya comentado ya
            if ($citaElegible) {
                $yaComento = Reference::where('user_id', Auth::id())
                    ->where('created_at', '>=', $citaElegible->updated_at)
                    ->exists();

                if (!$yaComento) {
                    $puedeComentar = true;
                }
            }
        }

        return view('references.index', compact('references', 'puedeComentar', 'citaElegible'));
    }

    public function store(Request $request)
    {
        // 🛡️ DOBLE VALIDACIÓN DE SEGURIDAD (Para evitar que un hacker lo salte)
        $citaElegible = Appointment::where('user_id', Auth::id())
            ->where('estado', 'completada')
            ->where('updated_at', '>=', now()->subHours(24))
            ->latest('updated_at')
            ->first();

        if (!$citaElegible) {
            return back()->withErrors(['comentario' => '🔒 No tienes citas recientes completadas para evaluar.']);
        }

        $yaComento = Reference::where('user_id', Auth::id())
            ->where('created_at', '>=', $citaElegible->updated_at)
            ->exists();

        if ($yaComento) {
            return back()->withErrors(['comentario' => '⚠️ Ya enviaste una reseña para esta cita.']);
        }

        // 🛡️ CADENERO ESTRICTO: Mínimo 10, Máximo 250, Calificación obligatoria
        $request->validate([
            'comentario' => 'required|string|min:10|max:250',
            'calificacion' => 'required|integer|min:1|max:5',
        ], [
            'comentario.min' => 'Tu reseña es muy corta. Por favor escribe al menos 10 letras.',
            'comentario.max' => 'Tu reseña es demasiado larga. El máximo es 250 letras.',
            'calificacion.required' => 'Por favor selecciona una calificación de estrellas.',
        ]);

        // 🛡️ ESCUDO ANTI-XSS
        $comentarioLimpio = strip_tags($request->comentario);

        Reference::create([
            'user_id' => Auth::id(),
            'comentario' => $comentarioLimpio,
            'calificacion' => $request->calificacion,
        ]);

        return back()->with('success', '¡Gracias por compartir tu experiencia con nosotros!');
    }

    // 🗑️ MÉTODO PARA ELIMINAR RESEÑAS TROLL
    public function destroy($id)
    {
        // Verificación extra de seguridad en el backend
        if (Auth::user()->role !== 'barbero' && !Auth::user()->is_superadmin) {
            return response()->json(['success' => false, 'message' => 'No tienes permisos para realizar esta acción.']);
        }

        $resena = Reference::find($id); 
        
        if($resena) {
            $resena->delete();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'No se encontró la reseña en el sistema.']);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleLoginController extends Controller
{
    // 1. Redirige al cliente a la página de Google para que ponga su correo
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Google nos regresa aquí con la información del usuario
    public function handleGoogleCallback()
    {
        try {
            // Obtenemos los datos desde Google
            $googleUser = Socialite::driver('google')->user();

            // Buscamos si ya existe un usuario con ese Google ID o ese correo
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Si el usuario ya existe pero no tenía su google_id registrado, se lo actualizamos
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
                
                // Lo logueamos
                Auth::login($user);
            } else {
                // Si NO existe, creamos una cuenta nueva para él
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'role' => 'cliente', // O el rol por defecto que uses
                    'password' => null, // Dejamos la contraseña nula
                    // 'email_verified_at' => now(), // Descomenta esta línea si quieres que su correo ya cuente como verificado
                ]);

                Auth::login($user);
            }

            // Lo mandamos a su panel de control
            return redirect()->intended('/dashboard'); // Cambia '/dashboard' por la ruta a donde quieres que vayan al iniciar sesión

        } catch (\Exception $e) {
            // Si algo sale mal (ej. el usuario cancela), lo regresamos al login con un error
            return redirect('/login')->withErrors(['email' => 'Hubo un problema al iniciar sesión con Google.']);
        }
    }
}
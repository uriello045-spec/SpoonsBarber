<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
                
                // Si su cuenta no estaba verificada, se la verificamos
                if (!$user->email_verified_at) {
                    $user->email_verified_at = now();
                    $user->save();
                }

                Auth::login($user);
            } else {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'role' => 'cliente', 
                    'password' => Hash::make(Str::random(16)), 
                    'email_verified_at' => now(), 
                    'terms_accepted' => false, // 🌟 ESTO ES LO NUEVO: NO HA ACEPTADO AÚN
                ]);

                Auth::login($user);
            }

            return redirect()->intended('/dashboard'); 

        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Hubo un problema al iniciar sesión con Google.']);
        }
    }
}
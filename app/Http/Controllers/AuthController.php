<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password; // <--- Importado para recuperar contraseña
use Illuminate\Support\Str; // <--- Importado para recuperar contraseña
use Illuminate\Auth\Events\PasswordReset; // <--- Importado para recuperar contraseña
use App\Models\User;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            // 🌟 AÑADIDO: Validación del checkbox de términos
            'terms' => 'required|accepted',
        ], [
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.unique' => 'Este correo ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            // 🌟 AÑADIDO: Mensaje de error personalizado
            'terms.accepted' => 'Debes aceptar los Términos y Condiciones para registrarte.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'cliente', 
            // 🌟 AÑADIDO: Guardamos el consentimiento en la base de datos
            'terms_accepted' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 🌟 NUEVO: Todos pasan primero por la pantalla de carga tecnológica 🌟
            return redirect()->route('loading');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // =========================================================================
    // LÓGICA DE RECUPERACIÓN DE CONTRASEÑA
    // =========================================================================

    public function showForgotPassword() {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request) {
        $request->validate(['email' => 'required|email']);
        
        $status = Password::sendResetLink($request->only('email'));
        
        return $status == Password::RESET_LINK_SENT
                    ? back()->with(['status' => '¡Te hemos enviado un enlace de recuperación a tu correo!'])
                    : back()->withErrors(['email' => 'No pudimos encontrar un usuario con ese correo electrónico.']);
    }

    public function showResetPassword(Request $request, $token) {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->forceFill(['password' => Hash::make($password)])->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
        });

        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('success', '¡Tu contraseña ha sido restablecida exitosamente! Ya puedes iniciar sesión.')
                    : back()->withErrors(['email' => ['El token de recuperación es inválido o ha expirado.']]);
    }
}
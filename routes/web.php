<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GoogleLoginController;
use App\Models\Service; 
use App\Models\Setting; 

// ─────────────────────────────────────────────────────────────
// 🌐 RUTAS PÚBLICAS Y LEGALES
// ─────────────────────────────────────────────────────────────
Route::get('/', function () { return redirect()->route('login'); })->name('home');
Route::get('/catalogo', function () { return view('catalogo'); })->name('catalogo');

// 🌟 RUTAS PARA LOS DOCUMENTOS LEGALES (Nuevas) 🌟
Route::view('/terminos', 'legal.terminos')->name('terminos');
Route::view('/privacidad', 'legal.privacidad')->name('privacidad');
Route::view('/cookies', 'legal.cookies')->name('cookies');

// 🌟 RUTA PARA EL SWEETALERT DE TÉRMINOS 🌟
Route::post('/aceptar-terminos-rapido', function(\Illuminate\Http\Request $request) {
    $request->user()->update(['terms_accepted' => true]);
    return response()->json(['success' => true]);
})->middleware('auth')->name('terminos.aceptar');


Route::get('/email/verify', function () { return view('auth.verify-email'); })->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '¡Enlace enviado!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ─────────────────────────────────────────────────────────────
// 🔑 AUTENTICACIÓN Y GOOGLE
// ─────────────────────────────────────────────────────────────
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register'])->name('register.post');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/olvide-mi-contrasena', [AuthController::class, 'showForgotPassword'])->middleware('guest')->name('password.request');
Route::post('/olvide-mi-contrasena', [AuthController::class, 'sendResetLink'])->middleware('guest')->name('password.email');
Route::get('/restablecer-contrasena/{token}', [AuthController::class, 'showResetPassword'])->middleware('guest')->name('password.reset');
Route::post('/restablecer-contrasena', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.update');

Route::get('/login/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('login.google.callback');

Route::get('/loading', function () { return view('loading'); })->middleware(['auth'])->name('loading');
Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['auth', 'verified', 'no-cache'])->name('dashboard');

// ─────────────────────────────────────────────────────────────
// 📅 CLIENTES (Citas y Referencias)
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'no-cache'])->group(function () {
    Route::get('/citas', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/citas', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/citas/{id}/editar', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/citas/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/citas/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    Route::get('/referencias', [ReferenceController::class, 'index'])->name('references.index');
    Route::post('/referencias', [ReferenceController::class, 'store'])->name('references.store');
    Route::delete('/referencias/{id}', [ReferenceController::class, 'destroy'])->name('references.destroy'); // 👈 NUEVA RUTA DE BORRADO

    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot', [ChatbotController::class, 'send'])->name('chatbot.send');

    Route::post('/api/validate-appointment-time', [AppointmentController::class, 'validateTime'])->name('api.validate.time');
});

// ─────────────────────────────────────────────────────────────
// 💈 ADMINISTRACIÓN (Barberos)
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:barbero', 'no-cache'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/toggle-shop', [AdminController::class, 'toggleShop'])->name('admin.toggleShop');
    Route::post('/admin/citas/express', [AppointmentController::class, 'storeExpress'])->name('admin.appointments.express');
    Route::get('/admin/estadisticas', [AdminController::class, 'statistics'])->name('admin.statistics');
    Route::get('/admin/citas', [AppointmentController::class, 'adminIndex'])->name('admin.appointments');
    
    Route::put('/appointments/{id}', [AppointmentController::class, 'update'])->name('admin.appointments.update');
    
    Route::get('/admin/chatbot', [AdminController::class, 'chatbotManager'])->name('admin.chatbot');
    Route::post('/admin/chatbot', [AdminController::class, 'storeChatbotResponse'])->name('admin.chatbot.store');
    Route::put('/admin/chatbot/{id}', [AdminController::class, 'updateChatbotResponse'])->name('admin.chatbot.update');
    Route::delete('/admin/chatbot/{id}', [AdminController::class, 'deleteChatbotResponse'])->name('admin.chatbot.delete');

    Route::get('/admin/barberos', [AdminController::class, 'barbers'])->name('admin.barbers.index');
    Route::get('/admin/barberos/crear', [AdminController::class, 'barbersCreate'])->name('admin.barbers.create');
    Route::post('/admin/barberos', [AdminController::class, 'barbersStore'])->name('admin.barbers.store');
    Route::get('/admin/barberos/{id}/editar', [AdminController::class, 'barbersEdit'])->name('admin.barbers.edit');
    Route::put('/admin/barberos/{id}', [AdminController::class, 'barbersUpdate'])->name('admin.barbers.update');
    Route::delete('/admin/barberos/{id}', [AdminController::class, 'barbersDestroy'])->name('admin.barbers.destroy');

    Route::post('/admin/galeria', [AdminController::class, 'storeGallery'])->name('admin.galeria.store');
    Route::delete('/admin/galeria/{id}', [AdminController::class, 'destroyGallery'])->name('admin.galeria.destroy'); 
    Route::post('/admin/servicios', [AdminController::class, 'storeService'])->name('admin.servicios.store');
    Route::delete('/admin/servicios/{id}', [AdminController::class, 'destroyService'])->name('admin.servicios.destroy');
    
    Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::post('/admin/verify-master', [AdminController::class, 'verifyMaster'])->middleware('throttle:5,1')->name('admin.verifyMaster');

    Route::get('/diagnostico-correo', function () {
    return response()->json(config('mail.mailers.smtp'));
});
});
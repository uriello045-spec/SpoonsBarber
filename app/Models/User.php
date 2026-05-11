<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Appointment;
use App\Models\Reference;
use App\Notifications\CustomResetPassword;
use App\Notifications\CustomVerifyEmail; // <--- NUEVO: Importamos el correo de verificación

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_superadmin', // 🌟 AÑADIDO: Permite guardar este dato
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_superadmin' => 'boolean', // 🌟 AÑADIDO: Le dice a Laravel que es True o False
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function references()
    {
        return $this->hasMany(Reference::class);
    }

    // =====================================================================
    // 🌟 CORREOS PERSONALIZADOS (ESPAÑOL Y DISEÑO BARBERÍA) 🌟
    // =====================================================================
    
    /**
     * 1. Sobreescribir correo de "Recuperar Contraseña"
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    /**
     * 2. Sobreescribir correo de "Verificar Email" (NUEVO)
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
}
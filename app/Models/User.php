<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Appointment;
use App\Models\Reference;
use App\Notifications\CustomResetPassword;
use App\Notifications\CustomVerifyEmail; 

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'is_superadmin', 
        'google_id',      // <-- Aseguramos que google_id esté aquí
        'terms_accepted', // 🌟 AÑADIDO: Permite guardar si aceptó términos
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_superadmin' => 'boolean', 
        'terms_accepted' => 'boolean', // 🌟 AÑADIDO: Lo convierte a True/False automáticamente
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function references()
    {
        return $this->hasMany(Reference::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
}
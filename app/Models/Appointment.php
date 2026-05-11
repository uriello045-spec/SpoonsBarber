<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fecha',
        'hora',
        'servicio',
        'estado',
    ];

    /**
     * Relación con el usuario (cliente) que hizo la cita
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accesor: Formato bonito de la fecha (opcional, útil en vistas)
     */
    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->fecha)->format('d/m/Y');
    }

    /**
     * Accesor: Formato bonito de la hora (opcional)
     */
    public function getFormattedTimeAttribute()
    {
        return \Carbon\Carbon::parse($this->hora)->format('H:i');
    }

    /**
     * Scope: Citas pendientes (útil en consultas)
     */
    public function scopePending($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope: Citas completadas
     */
    public function scopeCompleted($query)
    {
        return $query->where('estado', 'completada');
    }

    /**
     * Scope: Citas de hoy (útil para el barbero)
     */
    public function scopeToday($query)
    {
        return $query->whereDate('fecha', now()->toDateString());
    }
}
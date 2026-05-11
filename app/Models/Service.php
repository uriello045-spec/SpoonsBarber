<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // 👇 ESTO ES LO QUE TE FALTA. Da permiso para guardar todos los datos nuevos.
    protected $fillable = [
        'nombre',
        'precio',
        'duracion_minutos',
        'categoria',    // <-- NUEVO
        'descripcion',  // <-- NUEVO
        'imagen',       // <-- NUEVO
    ];
}
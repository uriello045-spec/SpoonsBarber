<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    // Esto es vital: le da permiso a Laravel de guardar la foto en estas columnas
    protected $fillable = ['imagen', 'activa', 'orden'];
}
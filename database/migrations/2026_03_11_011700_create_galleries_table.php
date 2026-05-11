<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('imagen'); // Guardará la ruta de la foto, ej: "galeria/foto1.jpeg"
            $table->boolean('activa')->default(true); // Útil por si quieres ocultar una foto sin borrarla de la BD
            $table->integer('orden')->default(0); // Por si luego quieres ordenar cuál sale primero
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('galleries');
    }
};
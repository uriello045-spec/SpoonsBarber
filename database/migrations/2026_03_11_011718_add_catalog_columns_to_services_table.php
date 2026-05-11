<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Agregamos las 3 columnas nuevas justo después de duracion_minutos
            $table->enum('categoria', ['clasico', 'moderno', 'extra'])->default('clasico')->after('duracion_minutos');
            $table->text('descripcion')->nullable()->after('categoria');
            $table->string('imagen')->nullable()->after('descripcion');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Por si algún día quieres deshacer este cambio, esto borra solo estas 3 columnas
            $table->dropColumn(['categoria', 'descripcion', 'imagen']);
        });
    }
};
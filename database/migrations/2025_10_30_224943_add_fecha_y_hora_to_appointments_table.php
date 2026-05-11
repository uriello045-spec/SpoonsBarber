<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------
        // PARTE 1: ARREGLAR LA TABLA APPOINTMENTS (CITAS)
        // ---------------------------------------------------------
        
        // Si NO existe la tabla, la crea desde cero (para corregir tu error anterior)
        if (!Schema::hasTable('appointments')) {
            Schema::create('appointments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->date('fecha')->nullable();
                $table->string('hora')->nullable();
                $table->string('servicio')->nullable();
                $table->string('estado')->default('pendiente');
                $table->timestamps();
            });
        } 
        // Si YA existe, verifica que no le falten columnas
        else {
            Schema::table('appointments', function (Blueprint $table) {
                if (!Schema::hasColumn('appointments', 'fecha')) {
                    $table->date('fecha')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('appointments', 'hora')) {
                    $table->string('hora')->nullable()->after('fecha');
                }
                if (!Schema::hasColumn('appointments', 'servicio')) {
                    $table->string('servicio')->nullable()->after('hora');
                }
                if (!Schema::hasColumn('appointments', 'estado')) {
                    $table->string('estado')->default('pendiente')->after('servicio');
                }
            });
        }

        // ---------------------------------------------------------
        // PARTE 2: AGREGAR DURACIÓN A SERVICES (LO QUE TE FALTABA)
        // ---------------------------------------------------------
        
        // Verifica si existe la tabla 'services' Y si le falta la columna 'duracion_minutos'
        if (Schema::hasTable('services') && !Schema::hasColumn('services', 'duracion_minutos')) {
            Schema::table('services', function (Blueprint $table) {
                // 👇 Aquí agregamos la columna necesaria para tu validación de las 9:00 PM
                $table->integer('duracion_minutos')->default(45)->after('precio'); 
            });
        }
    }

    public function down(): void
    {
        // En caso de revertir, limpiamos con cuidado
        Schema::dropIfExists('appointments');
        
        if (Schema::hasColumn('services', 'duracion_minutos')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropColumn('duracion_minutos');
            });
        }
    }
};
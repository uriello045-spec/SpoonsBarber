<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('appointments', function (Blueprint $table) {
            // Agregamos las nuevas columnas SIN tocar lo que ya existe
            if (!Schema::hasColumn('appointments', 'precio')) {
                $table->decimal('precio', 8, 2)->nullable()->after('servicio');
            }
            if (!Schema::hasColumn('appointments', 'duracion_minutos')) {
                $table->integer('duracion_minutos')->default(45)->after('precio');
            }
        });
    }

    public function down(): void {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['precio', 'duracion_minutos']);
        });
    }
};
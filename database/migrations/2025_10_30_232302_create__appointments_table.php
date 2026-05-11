<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 👇 VALIDACIÓN: Solo crea la tabla si NO existe previamente
        if (!Schema::hasTable('appointments')) {
            Schema::create('appointments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->date('fecha');
                $table->time('hora');
                $table->string('servicio');
                $table->string('estado')->default('pendiente');
                $table->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('appointments');
    }
};
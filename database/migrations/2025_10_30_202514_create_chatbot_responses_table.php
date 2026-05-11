<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('chatbot_responses', function (Blueprint $table) {
            $table->id();
            $table->string('keyword')->unique();
            $table->text('response');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('chatbot_responses');
    }
};

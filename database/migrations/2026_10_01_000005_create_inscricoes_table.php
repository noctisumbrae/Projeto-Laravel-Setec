<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inscricoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('edicao_id')->constrained('edicoes')->cascadeOnDelete();
            $table->string('status', 20)->default('pendente');

            // Regra de negócio: uma inscrição por participante em cada edição.
            $table->unique(['user_id', 'edicao_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscricoes');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inscricao_atividade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscricao_id')->constrained('inscricoes')->cascadeOnDelete();
            $table->foreignId('atividade_id')->constrained('atividades')->cascadeOnDelete();
            $table->string('status', 20)->default('selecionada');

            $table->unique(['inscricao_id', 'atividade_id']);
            $table->index(['atividade_id', 'status']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscricao_atividade');
    }
};
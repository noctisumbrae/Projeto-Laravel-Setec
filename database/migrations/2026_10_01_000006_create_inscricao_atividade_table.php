<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * EQUIPE 2 - Atividades escolhidas em cada inscrição (tabela pivô).
 * status: selecionada (não reserva vaga) | confirmada (Equipe 3, após Pix aprovado) | lotada
 * As Equipes 4 e 5 usam as linhas com status = 'confirmada' (presença e certificado).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscricao_atividade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscricao_id')->constrained('inscricoes')->cascadeOnDelete();
            $table->foreignId('atividade_id')->constrained('atividades')->cascadeOnDelete();
            $table->string('status', 20)->default('selecionada');
            $table->timestamps();

            $table->unique(['inscricao_id', 'atividade_id']);
            $table->index(['atividade_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscricao_atividade');
    }
};

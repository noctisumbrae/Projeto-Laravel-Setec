<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * EQUIPE 2 - Inscrição de um participante em UMA edição.
 * status: pendente (aguardando pagamento) | confirmada (alterado pela Equipe 3) | cancelada
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscricoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('edicao_id')->constrained('edicoes')->cascadeOnDelete();
            $table->string('status', 20)->default('pendente');
            $table->timestamps();

            // Regra de negócio: uma inscrição por participante em cada edição.
            $table->unique(['user_id', 'edicao_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscricoes');
    }
};

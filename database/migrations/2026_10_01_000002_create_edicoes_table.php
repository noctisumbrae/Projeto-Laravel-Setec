<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PROVISÓRIO - pertence à EQUIPE 1 (Administração).
 * Foi criado aqui apenas para o módulo da Equipe 2 funcionar sozinho.
 * Quando a Equipe 1 entregar a versão oficial, apague este arquivo e use o dela
 * (os nomes das colunas abaixo são o "contrato" que a Equipe 2 espera).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edicoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedSmallInteger('ano')->unique();
            $table->text('descricao')->nullable();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->boolean('ativa')->default(false);
            $table->decimal('valor_inscricao', 8, 2)->default(0);
            $table->date('inscricao_inicio_fatec')->nullable();
            $table->date('inscricao_fim_fatec')->nullable();
            $table->boolean('inscricoes_externas_habilitadas')->default(false);
            $table->date('inscricao_inicio_externo')->nullable();
            $table->date('inscricao_fim_externo')->nullable();
            $table->unsignedSmallInteger('limite_palestras')->nullable();  // null = sem limite
            $table->unsignedSmallInteger('limite_minicursos')->nullable(); // null = sem limite
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edicoes');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('atividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edicao_id')->constrained('edicoes')->cascadeOnDelete();
            $table->foreignId('local_id')->constrained('locais');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('tipo', 20);                 // palestra | minicurso
            $table->string('responsavel');              // palestrante ou instrutor
            $table->dateTime('inicio');
            $table->unsignedSmallInteger('duracao_minutos');
            $table->unsignedInteger('capacidade');
            $table->timestamps();

            $table->index(['edicao_id', 'inicio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atividades');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presencas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inscricao_atividade_id')
                ->unique()
                ->constrained('inscricao_atividade')
                ->cascadeOnDelete();

            $table->boolean('presente');
            $table->dateTime('registrada_em')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presencas');
    }
};

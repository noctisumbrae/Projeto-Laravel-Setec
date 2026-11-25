<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificados', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inscricao_id')
                ->unique()
                ->constrained('inscricoes')
                ->restrictOnDelete();

            $table->uuid('codigo_validacao')->unique();

            $table->unsignedInteger('carga_horaria_minutos');

            $table->dateTime('emitido_em');

            $table->string('status', 20)
                ->default('emitido');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificados');
    }
};


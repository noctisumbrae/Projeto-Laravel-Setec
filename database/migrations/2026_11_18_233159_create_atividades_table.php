
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atividades', function (Blueprint $table) {
            $table->id();

            $table->string('titulo');
            $table->text('descricao');

            $table->enum('tipo', [
                'palestra',
                'minicurso',
            ]);

            $table->string('responsavel');
            $table->date('data');
            $table->time('horario');
            $table->unsignedInteger('duracao');
            $table->unsignedInteger('capacidade');

            $table->foreignId('local_id')
                ->constrained('locals')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atividades');
    }
};

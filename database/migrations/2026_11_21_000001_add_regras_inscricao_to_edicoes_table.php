<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('edicoes', function (Blueprint $table) {
            if (! Schema::hasColumn('edicoes', 'inscricao_interna_inicio')) $table->dateTime('inscricao_interna_inicio')->nullable();
            if (! Schema::hasColumn('edicoes', 'inscricao_interna_fim')) $table->dateTime('inscricao_interna_fim')->nullable();
            if (! Schema::hasColumn('edicoes', 'inscricao_externa_inicio')) $table->dateTime('inscricao_externa_inicio')->nullable();
            if (! Schema::hasColumn('edicoes', 'inscricao_externa_fim')) $table->dateTime('inscricao_externa_fim')->nullable();
            if (! Schema::hasColumn('edicoes', 'permite_inscricao_externa')) $table->boolean('permite_inscricao_externa')->default(false);
            if (! Schema::hasColumn('edicoes', 'limite_palestras')) $table->unsignedInteger('limite_palestras')->default(0);
            if (! Schema::hasColumn('edicoes', 'limite_minicursos')) $table->unsignedInteger('limite_minicursos')->default(0);
            if (! Schema::hasColumn('edicoes', 'valor_inscricao')) $table->decimal('valor_inscricao', 8, 2)->default(0);
        });
    }

    public function down(): void
    {
        $colunas = [
            'inscricao_interna_inicio', 'inscricao_interna_fim',
            'inscricao_externa_inicio', 'inscricao_externa_fim',
            'permite_inscricao_externa', 'limite_palestras', 'limite_minicursos', 'valor_inscricao',
        ];
        $existentes = array_values(array_filter($colunas, fn (string $coluna) => Schema::hasColumn('edicoes', $coluna)));

        if ($existentes !== []) {
            Schema::table('edicoes', fn (Blueprint $table) => $table->dropColumn($existentes));
        }
    }
};

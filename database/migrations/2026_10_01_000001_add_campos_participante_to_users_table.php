<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * EQUIPE 2 - Acrescenta os dados do participante na tabela users do Laravel.
 * Assim existe uma única tabela de login para todo o sistema (combinado com a Equipe 6).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf', 11)->nullable()->unique()->after('email');
            $table->string('telefone', 11)->nullable()->after('cpf');
            $table->string('ra', 20)->nullable()->after('telefone');                       // RA do aluno Fatec
            $table->string('tipo_participante', 20)->default('externo')->after('ra');      // aluno_fatec | externo
            $table->string('perfil', 20)->default('participante')->after('tipo_participante'); // admin | participante
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['cpf']);
            $table->dropColumn(['cpf', 'telefone', 'ra', 'tipo_participante', 'perfil']);
        });
    }
};

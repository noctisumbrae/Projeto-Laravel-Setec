<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf', 11)
                ->nullable()
                ->unique();
            $table->string('telefone', 11)
                ->nullable();
            $table->string('ra', 20)
                ->nullable();
            $table->string('tipo_participante', 20)
                ->default('externo');
            $table->string('perfil', 20)
                ->default('participante');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['cpf']);
            $table->dropColumn([
                'cpf',
                'telefone',
                'ra',
                'tipo_participante',
                'perfil',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('atividades', function (Blueprint $table) {
            $table->foreignId('edicao_id')
                ->nullable()
                ->constrained('edicoes')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('atividades', function (Blueprint $table) {
            $table->dropConstrainedForeignId('edicao_id');
        });
    }
};

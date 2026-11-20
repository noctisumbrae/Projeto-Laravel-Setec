<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edicoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->unsignedSmallInteger('ano')->unique();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->text('descricao')->nullable();
            $table->string('status', 20)
                  ->default('planejada')
                  ->index();

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('edicoes');
    }
};

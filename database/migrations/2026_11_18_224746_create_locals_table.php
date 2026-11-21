
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locals', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', [
                'sala',
                'laboratorio',
                'auditorio'
            ]);
            $table->unsignedInteger('capacidade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locals');
    }
};

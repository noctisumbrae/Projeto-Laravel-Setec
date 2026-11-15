<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('participant_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('registered_at');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->boolean('is_manual')->default(false);
            $table->foreignId('corrected_by')->nullable()->constrained('users');
            $table->text('correction_justification')->nullable();
            $table->timestamps();

            $table->unique(['activity_id', 'participant_id']);
            $table->index(['activity_id', 'registered_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
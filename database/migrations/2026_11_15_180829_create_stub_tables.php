<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ⚠️ STUB TEMPORÁRIO — Equipe 1
        if (! Schema::hasTable('editions')) {
            Schema::create('editions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->year('year')->nullable();
                $table->timestamps();
            });
        }

        // ⚠️ STUB TEMPORÁRIO — Equipe 1
        if (! Schema::hasTable('activities')) {
            Schema::create('activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('edition_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->string('location')->nullable();
                $table->timestamps();
            });
        }

        // ⚠️ STUB TEMPORÁRIO — Equipe 3
        if (! Schema::hasTable('activity_participant')) {
            Schema::create('activity_participant', function (Blueprint $table) {
                $table->id();
                $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
                $table->foreignId('participant_id')->constrained('users')->cascadeOnDelete();
                $table->string('status')->default('pending_payment');
                $table->timestamps();
                $table->unique(['activity_id', 'participant_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_participant');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('editions');
    }
};
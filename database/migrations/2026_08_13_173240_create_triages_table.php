<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('triages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->constrained()->onDelete('cascade');
            $table->foreignId('nurse_id')->constrained('users');
            $table->string('bp')->nullable();
            $table->decimal('temp', 4, 1)->nullable();
            $table->integer('pulse')->nullable();
            $table->integer('spo2')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->enum('priority', ['Emergency', 'Very Urgent', 'Urgent', 'Standard', 'Non-Urgent'])->default('Standard');
            $table->text('chief_complaint')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('triages');
    }
};

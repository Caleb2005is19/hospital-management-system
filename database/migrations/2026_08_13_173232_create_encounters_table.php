<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('encounter_number')->unique(); // e.g., ENC-2026-00001
            $table->enum('type', ['OPD', 'IPD', 'ER'])->default('OPD');
            $table->enum('status', [
                'registered',
                'waiting_triage',
                'triaged',
                'waiting_doctor',
                'in_consultation',
                'waiting_lab',
                'waiting_pharmacy',
                'admitted',
                'discharged'
            ])->default('registered');
            $table->foreignId('assigned_doctor_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encounters');
    }
};

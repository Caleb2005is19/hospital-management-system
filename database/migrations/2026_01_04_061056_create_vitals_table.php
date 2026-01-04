<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vitals', function (Blueprint $table) {
            $table->id();
            // Link to Patient (User) and Nurse (User)
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('nurse_id')->nullable()->constrained('users')->onDelete('set null');

            // Vital Signs
            $table->string('temperature')->nullable(); // e.g., "37.5 C"
            $table->string('blood_pressure')->nullable(); // e.g., "120/80"
            $table->string('heart_rate')->nullable(); // e.g., "72 bpm"
            $table->text('nurse_note')->nullable(); // Observations

            $table->timestamps(); // Automatically records time of check
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vitals');
    }
};

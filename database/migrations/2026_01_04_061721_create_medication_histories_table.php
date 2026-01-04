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
        Schema::create('medication_histories', function (Blueprint $table) {
            $table->id();

            // Links
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade'); // Links to the Doctor's Prescription
            $table->foreignId('nurse_id')->constrained('users'); // Who gave it?

            $table->string('status')->default('Given'); // Given, Missed, Delayed
            $table->text('remarks')->nullable(); // e.g., "Patient refused" or "Given with food"

            $table->timestamp('administered_at'); // Exact time
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_histories');
    }
};

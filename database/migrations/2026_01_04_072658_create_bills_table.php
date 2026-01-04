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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users'); // Who pays?
            $table->foreignId('appointment_id')->constrained(); // Which visit?

            $table->decimal('doctor_charge', 10, 2); // e.g. 500.00
            $table->decimal('medicine_charge', 10, 2); // Sum of drugs
            $table->decimal('room_charge', 10, 2); // e.g. 3 days * 1000
            $table->decimal('total_amount', 10, 2); // Grand Total

            $table->string('status')->default('Unpaid'); // Unpaid, Paid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};

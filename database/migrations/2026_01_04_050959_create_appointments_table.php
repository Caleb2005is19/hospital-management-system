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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Link to Patient (User table) and Doctor (User table)
            // We use 'users' table for both because Doctors are also Users
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('users')->onDelete('set null');

            $table->date('date');
            $table->time('time');
            $table->string('status')->default('pending'); // pending, confirmed, completed
            $table->text('reason')->nullable();
            $table->text('prescription')->nullable(); // Simple text for now

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

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
        Schema::create('nursing_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users'); // Who is this about?
            $table->foreignId('nurse_id')->constrained('users');   // Who wrote it?

            $table->text('note'); // The observation
            $table->string('type')->default('Routine'); // Routine, Critical, Doctor Round

            $table->timestamps(); // Created_at is the timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nursing_notes');
    }
};

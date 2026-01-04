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
        Schema::create('dispensed_medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained(); // Links to Patient/Doctor
            $table->foreignId('drug_id')->constrained(); // Links to Inventory

            $table->integer('quantity'); // How many pills given
            $table->decimal('cost', 8, 2); // Price calculated at that moment

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispensed_medicines');
    }
};

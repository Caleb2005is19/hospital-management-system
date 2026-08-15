<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users');
            $table->foreignId('pharmacist_id')->nullable()->constrained('users');
            $table->string('dosage')->nullable(); // e.g. 500mg
            $table->string('frequency')->nullable(); // e.g. 8 hourly (TDS)
            $table->string('duration')->nullable(); // e.g. 5 days
            $table->integer('quantity_prescribed');
            $table->integer('quantity_dispensed')->default(0);
            $table->enum('status', ['pending', 'dispensed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique(); // e.g., INV-2026-00001
            $table->decimal('consultation_fee', 10, 2)->default(500.00);
            $table->decimal('lab_total', 10, 2)->default(0.00);
            $table->decimal('pharmacy_total', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->decimal('amount_paid', 10, 2)->default(0.00);
            $table->enum('status', ['unpaid', 'partially_paid', 'paid'])->default('unpaid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};


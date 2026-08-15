<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cashier_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_number')->unique(); // e.g. SESS-20260815-001
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('opening_float', 12, 2)->default(0.00);
            $table->decimal('closing_cash_actual', 12, 2)->nullable();
            $table->decimal('expected_cash', 12, 2)->default(0.00);
            $table->decimal('expected_mpesa', 12, 2)->default(0.00);
            $table->decimal('expected_card', 12, 2)->default(0.00);
            $table->decimal('expected_insurance', 12, 2)->default(0.00);
            $table->decimal('variance_cash', 12, 2)->nullable();
            $table->text('variance_reason')->nullable();
            $table->string('status')->default('open'); // open, closed
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashier_sessions');
    }
};

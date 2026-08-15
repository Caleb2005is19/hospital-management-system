<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'cashier_session_id')) {
                $table->foreignId('cashier_session_id')->nullable()->constrained('cashier_sessions')->nullOnDelete();
            }
            if (!Schema::hasColumn('payments', 'payment_number')) {
                $table->string('payment_number')->nullable()->unique();
            }
            if (!Schema::hasColumn('payments', 'status')) {
                $table->string('status')->default('completed'); // completed, reversed, refunded
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['cashier_session_id', 'payment_number', 'status']);
        });
    }
};

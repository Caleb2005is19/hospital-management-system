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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('transaction_type'); // RECEIVE, DISPENSE, OTC_SALE, ADJUST_ADD, ADJUST_DEDUCT, DAMAGE_EXPIRED
            $table->integer('quantity_change'); // e.g. +100 or -15
            $table->integer('balance_before');
            $table->integer('balance_after');
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('reason')->nullable(); // Mandatory notes for manual edits
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};

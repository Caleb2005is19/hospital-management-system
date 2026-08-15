<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('item_name'); // e.g. Amoxicillin 500mg
            $table->string('category')->default('Medication'); // Medication, Consumable, Surgical
            $table->integer('stock_quantity')->default(0);
            $table->decimal('unit_price', 10, 2)->default(0.00); // e.g. 15.00 KSh per pill
            $table->string('dosage_form')->nullable(); // Tablet, Syrup, Injection, Capsule
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};


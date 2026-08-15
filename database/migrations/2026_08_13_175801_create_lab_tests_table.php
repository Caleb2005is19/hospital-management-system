<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Full Blood Count (FBC)
            $table->string('code')->unique(); // e.g. LAB-FBC
            $table->decimal('price', 10, 2)->default(0.00); // e.g. 500.00 KSh
            $table->string('sample_type')->nullable(); // e.g. Whole Blood, Urine, Swab
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_tests');
    }
};


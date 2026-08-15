<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_catalogues', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. SRV-CONS-001, SRV-LAB-002
            $table->string('name');
            $table->string('category'); // Consultation, Laboratory, Radiology, Procedure, Ward, Nursing
            $table->string('department'); // OPD, Lab, Radiology, Theatre, Inpatient
            $table->decimal('unit_price', 12, 2);
            $table->decimal('cost_price', 12, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_catalogues');
    }
};

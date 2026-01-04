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
        Schema::table('beds', function (Blueprint $table) {
            // Add status (Available/Occupied)
            $table->string('status')->default('Available');

            // Add patient_id if it's missing too
            if (!Schema::hasColumn('beds', 'patient_id')) {
                $table->string('patient_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beds', function (Blueprint $table) {
            //
        });
    }
};

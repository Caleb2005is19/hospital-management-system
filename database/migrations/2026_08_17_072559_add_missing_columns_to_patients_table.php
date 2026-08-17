<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'national_id')) {
                $table->string('national_id')->nullable()->index()->after('patient_number');
            }
            if (!Schema::hasColumn('patients', 'patient_number')) {
                $table->string('patient_number')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('patients', 'next_of_kin_name')) {
                $table->string('next_of_kin_name')->nullable()->after('address');
            }
            if (!Schema::hasColumn('patients', 'next_of_kin_phone')) {
                $table->string('next_of_kin_phone')->nullable()->after('next_of_kin_name');
            }
            if (!Schema::hasColumn('patients', 'allergies')) {
                $table->text('allergies')->nullable()->after('next_of_kin_phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'national_id',
                'next_of_kin_name',
                'next_of_kin_phone',
                'allergies'
            ]);
        });
    }
};

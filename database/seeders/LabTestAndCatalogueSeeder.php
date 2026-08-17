<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LabTestAndCatalogueSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $labTests = [
            [
                'name' => 'Complete Blood Count (CBC / FBC)',
                'code' => 'LAB-CBC',
                'sample_type' => 'Whole Blood (EDTA)',
                'price' => 800.00,
                'cost_price' => 350.00,
            ],
            [
                'name' => 'Malaria Rapid Diagnostic Test (mRDT)',
                'code' => 'LAB-MRDT',
                'sample_type' => 'Capillary / Venous Blood',
                'price' => 300.00,
                'cost_price' => 120.00,
            ],
            [
                'name' => 'Blood Slide for Malaria Parasites (BS for MPs)',
                'code' => 'LAB-BSMP',
                'sample_type' => 'Whole Blood',
                'price' => 400.00,
                'cost_price' => 150.00,
            ],
            [
                'name' => 'Urinalysis (Routine & Microscopy)',
                'code' => 'LAB-URIN',
                'sample_type' => 'Midstream Urine',
                'price' => 500.00,
                'cost_price' => 180.00,
            ],
            [
                'name' => 'Random Blood Sugar (RBS)',
                'code' => 'LAB-RBS',
                'sample_type' => 'Capillary Blood',
                'price' => 250.00,
                'cost_price' => 80.00,
            ],
            [
                'name' => 'Fasting Blood Sugar (FBS)',
                'code' => 'LAB-FBS',
                'sample_type' => 'Fluoride Plasma',
                'price' => 300.00,
                'cost_price' => 100.00,
            ],
            [
                'name' => 'Glycated Hemoglobin (HbA1c)',
                'code' => 'LAB-HBA1C',
                'sample_type' => 'Whole Blood (EDTA)',
                'price' => 1800.00,
                'cost_price' => 900.00,
            ],
            [
                'name' => 'Renal Function Tests (Urea, Electrolytes, Creatinine)',
                'code' => 'LAB-RFT',
                'sample_type' => 'Serum',
                'price' => 1500.00,
                'cost_price' => 650.00,
            ],
            [
                'name' => 'Liver Function Tests (LFTs)',
                'code' => 'LAB-LFT',
                'sample_type' => 'Serum',
                'price' => 1600.00,
                'cost_price' => 700.00,
            ],
            [
                'name' => 'Lipid Profile (Total Cholesterol, HDL, LDL, Triglycerides)',
                'code' => 'LAB-LIPID',
                'sample_type' => 'Serum',
                'price' => 1800.00,
                'cost_price' => 800.00,
            ],
            [
                'name' => 'Stool Routine and Microscopy (Ova & Cysts)',
                'code' => 'LAB-STOOL',
                'sample_type' => 'Fresh Stool',
                'price' => 450.00,
                'cost_price' => 150.00,
            ],
            [
                'name' => 'Typhoid Widal / Typhidot Test',
                'code' => 'LAB-TYPH',
                'sample_type' => 'Serum',
                'price' => 600.00,
                'cost_price' => 220.00,
            ],
            [
                'name' => 'HIV 1/2 Rapid Antibody Screening',
                'code' => 'LAB-HIV',
                'sample_type' => 'Whole Blood / Serum',
                'price' => 300.00,
                'cost_price' => 100.00,
            ],
            [
                'name' => 'Urine Pregnancy Test (hCG)',
                'code' => 'LAB-HCG',
                'sample_type' => 'Urine',
                'price' => 300.00,
                'cost_price' => 80.00,
            ],
            [
                'name' => 'Serum Uric Acid',
                'code' => 'LAB-URIC',
                'sample_type' => 'Serum',
                'price' => 750.00,
                'cost_price' => 300.00,
            ],
            [
                'name' => 'C-Reactive Protein (CRP)',
                'code' => 'LAB-CRP',
                'sample_type' => 'Serum',
                'price' => 1200.00,
                'cost_price' => 500.00,
            ],
            [
                'name' => 'Erythrocyte Sedimentation Rate (ESR)',
                'code' => 'LAB-ESR',
                'sample_type' => 'Citrated Whole Blood',
                'price' => 400.00,
                'cost_price' => 120.00,
            ],
            [
                'name' => 'ABO & Rhesus Blood Grouping',
                'code' => 'LAB-BGRP',
                'sample_type' => 'Whole Blood (EDTA)',
                'price' => 500.00,
                'cost_price' => 150.00,
            ],
        ];

        foreach ($labTests as $item) {
            // 1. Populate lab_tests table
            DB::table('lab_tests')->updateOrInsert(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'sample_type' => $item['sample_type'],
                    'price' => $item['price'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            // 2. Populate service_catalogues table
            DB::table('service_catalogues')->updateOrInsert(
                ['code' => $item['code']],
                [
                    'name' => $item['name'],
                    'category' => 'Laboratory',
                    'department' => 'Laboratory',
                    'unit_price' => $item['price'],
                    'cost_price' => $item['cost_price'],
                    'is_active' => true,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}

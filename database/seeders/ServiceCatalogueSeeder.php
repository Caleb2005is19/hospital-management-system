<?php

namespace Database\Seeders;

use App\Models\ServiceCatalogue;
use Illuminate\Database\Seeder;

class ServiceCatalogueSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // Consultation
            [
                'code' => 'SRV-CONS-001',
                'name' => 'General Outpatient Consultation (OPD)',
                'category' => 'Consultation',
                'department' => 'OPD',
                'unit_price' => 500.00,
                'cost_price' => 0.00,
            ],
            [
                'code' => 'SRV-CONS-002',
                'name' => 'Specialist Consultation',
                'category' => 'Consultation',
                'department' => 'OPD',
                'unit_price' => 1500.00,
                'cost_price' => 0.00,
            ],
            [
                'code' => 'SRV-CONS-003',
                'name' => 'Emergency / Casualty Consultation',
                'category' => 'Consultation',
                'department' => 'OPD',
                'unit_price' => 1000.00,
                'cost_price' => 0.00,
            ],

            // Laboratory Diagnostic Tests
            [
                'code' => 'SRV-LAB-001',
                'name' => 'Full Blood Count (CBC/FBC)',
                'category' => 'Laboratory',
                'department' => 'Lab',
                'unit_price' => 800.00,
                'cost_price' => 200.00,
            ],
            [
                'code' => 'SRV-LAB-002',
                'name' => 'Malaria Rapid Diagnostic Test (mRDT) / BS for MPS',
                'category' => 'Laboratory',
                'department' => 'Lab',
                'unit_price' => 300.00,
                'cost_price' => 80.00,
            ],
            [
                'code' => 'SRV-LAB-003',
                'name' => 'Random Blood Sugar (RBS)',
                'category' => 'Laboratory',
                'department' => 'Lab',
                'unit_price' => 200.00,
                'cost_price' => 50.00,
            ],
            [
                'code' => 'SRV-LAB-004',
                'name' => 'Urinalysis (Dipstick & Microscopy)',
                'category' => 'Laboratory',
                'department' => 'Lab',
                'unit_price' => 350.00,
                'cost_price' => 90.00,
            ],
            [
                'code' => 'SRV-LAB-005',
                'name' => 'Stool Analysis',
                'category' => 'Laboratory',
                'department' => 'Lab',
                'unit_price' => 400.00,
                'cost_price' => 100.00,
            ],

            // Nursing Procedures
            [
                'code' => 'SRV-NUR-001',
                'name' => 'Intramuscular / Intravenous Injection Administration',
                'category' => 'Procedure',
                'department' => 'OPD',
                'unit_price' => 200.00,
                'cost_price' => 40.00,
            ],
            [
                'code' => 'SRV-NUR-002',
                'name' => 'Wound Dressing & Cleaning (Minor)',
                'category' => 'Procedure',
                'department' => 'OPD',
                'unit_price' => 400.00,
                'cost_price' => 120.00,
            ],
            [
                'code' => 'SRV-NUR-003',
                'name' => 'Wound Dressing (Major/Surgical)',
                'category' => 'Procedure',
                'department' => 'OPD',
                'unit_price' => 800.00,
                'cost_price' => 250.00,
            ],
            [
                'code' => 'SRV-NUR-004',
                'name' => 'Urethral Catheterization',
                'category' => 'Procedure',
                'department' => 'OPD',
                'unit_price' => 1000.00,
                'cost_price' => 350.00,
            ],

            // Inpatient / Ward Accommodation
            [
                'code' => 'SRV-WARD-001',
                'name' => 'General Ward Bed Charge (Per Day)',
                'category' => 'Ward',
                'department' => 'Inpatient',
                'unit_price' => 1500.00,
                'cost_price' => 0.00,
            ],
            [
                'code' => 'SRV-WARD-002',
                'name' => 'Private Room Bed Charge (Per Day)',
                'category' => 'Ward',
                'department' => 'Inpatient',
                'unit_price' => 4000.00,
                'cost_price' => 0.00,
            ],
        ];

        foreach ($services as $service) {
            ServiceCatalogue::updateOrCreate(
                ['code' => $service['code']],
                $service
            );
        }
    }
}

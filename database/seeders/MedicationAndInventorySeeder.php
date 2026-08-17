<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicationAndInventorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $medications = [
            // Analgesics & Antipyretics
            [
                'code' => 'MED-PARA-500',
                'name' => 'Paracetamol 500mg Tabs',
                'category' => 'Analgesic',
                'dosage_form' => 'Tablet',
                'quantity' => 1000,
                'price' => 5.00,
                'cost_price' => 2.00,
            ],
            [
                'code' => 'MED-IBU-400',
                'name' => 'Ibuprofen 400mg Tabs',
                'category' => 'NSAID',
                'dosage_form' => 'Tablet',
                'quantity' => 800,
                'price' => 10.00,
                'cost_price' => 4.00,
            ],
            [
                'code' => 'MED-DICLO-50',
                'name' => 'Diclofenac Sodium 50mg Tabs',
                'category' => 'NSAID',
                'dosage_form' => 'Tablet',
                'quantity' => 500,
                'price' => 15.00,
                'cost_price' => 6.00,
            ],
            [
                'code' => 'MED-TRAM-50',
                'name' => 'Tramadol 50mg Caps',
                'category' => 'Opioid Analgesic',
                'dosage_form' => 'Capsule',
                'quantity' => 300,
                'price' => 25.00,
                'cost_price' => 10.00,
            ],

            // Antibiotics & Anti-infectives
            [
                'code' => 'MED-AMOX-500',
                'name' => 'Amoxicillin 500mg Caps',
                'category' => 'Antibiotic',
                'dosage_form' => 'Capsule',
                'quantity' => 1200,
                'price' => 15.00,
                'cost_price' => 7.00,
            ],
            [
                'code' => 'MED-AUGM-625',
                'name' => 'Augmentin (Amoxicillin/Clavulanate) 625mg Tabs',
                'category' => 'Antibiotic',
                'dosage_form' => 'Tablet',
                'quantity' => 400,
                'price' => 60.00,
                'cost_price' => 30.00,
            ],
            [
                'code' => 'MED-AZITH-500',
                'name' => 'Azithromycin 500mg Tabs',
                'category' => 'Antibiotic',
                'dosage_form' => 'Tablet',
                'quantity' => 350,
                'price' => 50.00,
                'cost_price' => 22.00,
            ],
            [
                'code' => 'MED-CIPRO-500',
                'name' => 'Ciprofloxacin 500mg Tabs',
                'category' => 'Antibiotic',
                'dosage_form' => 'Tablet',
                'quantity' => 600,
                'price' => 20.00,
                'cost_price' => 8.00,
            ],
            [
                'code' => 'MED-METRO-400',
                'name' => 'Metronidazole 400mg Tabs',
                'category' => 'Antibacterial/Antiprotozoal',
                'dosage_form' => 'Tablet',
                'quantity' => 900,
                'price' => 10.00,
                'cost_price' => 4.00,
            ],
            [
                'code' => 'MED-CEFTR-1G',
                'name' => 'Ceftriaxone 1g Powder for Injection',
                'category' => 'Antibiotic Injectable',
                'dosage_form' => 'Vial',
                'quantity' => 250,
                'price' => 250.00,
                'cost_price' => 120.00,
            ],

            // Antimalarials
            [
                'code' => 'MED-AL-6X4',
                'name' => 'Artemether/Lumefantrine (AL) 20/120mg (24s)',
                'category' => 'Antimalarial',
                'dosage_form' => 'Tablet',
                'quantity' => 450,
                'price' => 150.00,
                'cost_price' => 60.00,
            ],
            [
                'code' => 'MED-ARTS-60',
                'name' => 'Artesunate 60mg Injection',
                'category' => 'Antimalarial Injectable',
                'dosage_form' => 'Vial',
                'quantity' => 150,
                'price' => 400.00,
                'cost_price' => 200.00,
            ],

            // Antihypertensives & Cardiovascular
            [
                'code' => 'MED-AMLO-5',
                'name' => 'Amlodipine 5mg Tabs',
                'category' => 'Antihypertensive',
                'dosage_form' => 'Tablet',
                'quantity' => 700,
                'price' => 12.00,
                'cost_price' => 5.00,
            ],
            [
                'code' => 'MED-ENAL-10',
                'name' => 'Enalapril 10mg Tabs',
                'category' => 'Antihypertensive',
                'dosage_form' => 'Tablet',
                'quantity' => 600,
                'price' => 15.00,
                'cost_price' => 6.00,
            ],
            [
                'code' => 'MED-LOSA-50',
                'name' => 'Losartan Potassium 50mg Tabs',
                'category' => 'Antihypertensive',
                'dosage_form' => 'Tablet',
                'quantity' => 550,
                'price' => 25.00,
                'cost_price' => 10.00,
            ],
            [
                'code' => 'MED-ATEN-50',
                'name' => 'Atenolol 50mg Tabs',
                'category' => 'Antihypertensive',
                'dosage_form' => 'Tablet',
                'quantity' => 400,
                'price' => 12.00,
                'cost_price' => 4.50,
            ],

            // Antidiabetics
            [
                'code' => 'MED-METF-500',
                'name' => 'Metformin HCl 500mg Tabs',
                'category' => 'Antidiabetic',
                'dosage_form' => 'Tablet',
                'quantity' => 850,
                'price' => 10.00,
                'cost_price' => 4.00,
            ],
            [
                'code' => 'MED-GLIB-5',
                'name' => 'Glibenclamide 5mg Tabs',
                'category' => 'Antidiabetic',
                'dosage_form' => 'Tablet',
                'quantity' => 500,
                'price' => 8.00,
                'cost_price' => 3.00,
            ],

            // Gastrointestinal & Antihistamines
            [
                'code' => 'MED-OMEP-20',
                'name' => 'Omeprazole 20mg Caps',
                'category' => 'Proton Pump Inhibitor',
                'dosage_form' => 'Capsule',
                'quantity' => 1100,
                'price' => 15.00,
                'cost_price' => 5.00,
            ],
            [
                'code' => 'MED-CETR-10',
                'name' => 'Cetirizine 10mg Tabs',
                'category' => 'Antihistamine',
                'dosage_form' => 'Tablet',
                'quantity' => 900,
                'price' => 10.00,
                'cost_price' => 3.50,
            ],

            // Respiratory & Inhalers
            [
                'code' => 'MED-SALB-INH',
                'name' => 'Salbutamol 100mcg Inhaler',
                'category' => 'Bronchodilator',
                'dosage_form' => 'Inhaler',
                'quantity' => 120,
                'price' => 350.00,
                'cost_price' => 180.00,
            ],

            // IV Fluids
            [
                'code' => 'MED-NS-500',
                'name' => 'Normal Saline (0.9% NaCl) 500ml IV',
                'category' => 'IV Fluid',
                'dosage_form' => 'Infusion Bottle',
                'quantity' => 300,
                'price' => 180.00,
                'cost_price' => 90.00,
            ],
            [
                'code' => 'MED-RL-500',
                'name' => "Ringer's Lactate (Hartmann's) 500ml IV",
                'category' => 'IV Fluid',
                'dosage_form' => 'Infusion Bottle',
                'quantity' => 250,
                'price' => 200.00,
                'cost_price' => 100.00,
            ],
            [
                'code' => 'MED-D5W-500',
                'name' => '5% Dextrose in Water 500ml IV',
                'category' => 'IV Fluid',
                'dosage_form' => 'Infusion Bottle',
                'quantity' => 200,
                'price' => 190.00,
                'cost_price' => 95.00,
            ],
        ];

        foreach ($medications as $med) {
            // 1. Populate drugs table
            DB::table('drugs')->updateOrInsert(
                ['name' => $med['name']],
                [
                    'quantity' => $med['quantity'],
                    'price' => $med['price'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            // 2. Populate inventories table
            DB::table('inventories')->updateOrInsert(
                ['item_name' => $med['name']],
                [
                    'category' => $med['category'],
                    'stock_quantity' => $med['quantity'],
                    'unit_price' => $med['price'],
                    'dosage_form' => $med['dosage_form'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            // 3. Populate service_catalogues table
            DB::table('service_catalogues')->updateOrInsert(
                ['code' => $med['code']],
                [
                    'name' => $med['name'],
                    'category' => 'Pharmacy',
                    'department' => 'Pharmacy',
                    'unit_price' => $med['price'],
                    'cost_price' => $med['cost_price'],
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

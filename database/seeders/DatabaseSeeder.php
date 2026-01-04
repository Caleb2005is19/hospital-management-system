<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // Create some beds
        \App\Models\Bed::create(['ward_name' => 'General Ward', 'bed_number' => 'Gen-01']);
        \App\Models\Bed::create(['ward_name' => 'General Ward', 'bed_number' => 'Gen-02']);
        \App\Models\Bed::create(['ward_name' => 'ICU', 'bed_number' => 'ICU-01']);
        \App\Models\Bed::create(['ward_name' => 'ICU', 'bed_number' => 'ICU-02']);
        \App\Models\Bed::create(['ward_name' => 'Maternity', 'bed_number' => 'Mat-01']);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}

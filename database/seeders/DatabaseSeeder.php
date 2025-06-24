<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Carer;
use App\Models\Client;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the database.
     */
    public function run(): void {


        // Create test carers
        Carer::create([
            'name' => 'John Smith',
            'email' => 'john.smith@example.com',
            'phone' => '1234567890',
            'qualifications' => 'Registered Nurse'
        ]);

        Carer::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.johnson@example.com',
            'phone' => '0987654321',
            'qualifications' => 'Healthcare Assistant'
        ]);

        // Create test clients
        Client::create([
            'name' => 'Alice Brown',
            'email' => 'alice.brown@example.com',
            'phone' => '1122334455',
            'address' => '123 Main St, City',
            'care_needs' => 'Daily assistance with medication'
        ]);

        Client::create([
            'name' => 'Bob Wilson',
            'email' => 'bob.wilson@example.com',
            'phone' => '5544332211',
            'address' => '456 Oak Ave, Town',
            'care_needs' => 'Mobility assistance and meal preparation'
        ]);
    }
}

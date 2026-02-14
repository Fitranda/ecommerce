<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo tenant
        $tenant = Tenant::create([
            'id' => 'demo',
            'name' => 'Demo Store',
            'email' => 'admin@demo.test',
            'plan' => 'premium',
        ]);

        $tenant->domains()->create(['domain' => 'demo']);
    }
}

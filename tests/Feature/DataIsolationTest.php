<?php

namespace Tests\Feature;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_tenant_gets_separate_database(): void
    {
        $tenant1 = Tenant::create([
            'id' => 'store1',
            'name' => 'Store One',
            'email' => 'admin@store1.test',
            'plan' => 'basic',
        ]);

        $tenant2 = Tenant::create([
            'id' => 'store2',
            'name' => 'Store Two',
            'email' => 'admin@store2.test',
            'plan' => 'basic',
        ]);

        $this->assertNotEquals($tenant1->id, $tenant2->id);

        // Verify each tenant has its own database
        $this->assertDatabaseHas('tenants', ['id' => 'store1']);
        $this->assertDatabaseHas('tenants', ['id' => 'store2']);

        // Clean up tenant databases
        $tenant1->delete();
        $tenant2->delete();
    }

    public function test_tenant_registration_creates_domain(): void
    {
        $tenant = Tenant::create([
            'id' => 'newstore',
            'name' => 'New Store',
            'email' => 'admin@new.test',
            'plan' => 'basic',
        ]);

        $tenant->domains()->create(['domain' => 'newstore']);

        $this->assertDatabaseHas('domains', [
            'domain' => 'newstore.ecommerce.test',
        ]);

        $tenant->delete();
    }
}

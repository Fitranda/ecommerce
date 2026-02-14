<?php

namespace Tests\Feature;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DataIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        // Clean up MySQL tenant databases created during tests
        DB::statement('DROP DATABASE IF EXISTS tenant_store1');
        DB::statement('DROP DATABASE IF EXISTS tenant_store2');
        DB::statement('DROP DATABASE IF EXISTS tenant_newstore');

        parent::tearDown();
    }

    public function test_each_tenant_gets_separate_database(): void
    {
        // Drop leftover databases from previous runs
        DB::statement('DROP DATABASE IF EXISTS tenant_store1');
        DB::statement('DROP DATABASE IF EXISTS tenant_store2');

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

        // Verify each tenant has its own record in central DB
        $this->assertDatabaseHas('tenants', ['id' => 'store1']);
        $this->assertDatabaseHas('tenants', ['id' => 'store2']);

        // Verify actual MySQL databases were created
        $databases = DB::select('SHOW DATABASES');
        $dbNames = array_map(fn ($db) => $db->Database, $databases);
        $this->assertContains('tenant_store1', $dbNames);
        $this->assertContains('tenant_store2', $dbNames);
    }

    public function test_tenant_registration_creates_domain(): void
    {
        DB::statement('DROP DATABASE IF EXISTS tenant_newstore');

        $tenant = Tenant::create([
            'id' => 'newstore',
            'name' => 'New Store',
            'email' => 'admin@new.test',
            'plan' => 'basic',
        ]);

        $tenant->domains()->create(['domain' => 'newstore']);

        $this->assertDatabaseHas('domains', [
            'domain' => 'newstore',
            'tenant_id' => 'newstore',
        ]);
    }
}

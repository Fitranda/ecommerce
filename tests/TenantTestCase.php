<?php

namespace Tests;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

abstract class TenantTestCase extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        // Drop leftover test database from previous runs
        DB::statement('DROP DATABASE IF EXISTS tenant_test');

        // Create a test tenant (this creates the tenant_test database)
        $this->tenant = Tenant::create([
            'id' => 'test',
            'name' => 'Test Store',
            'email' => 'admin@test.test',
            'plan' => 'basic',
        ]);

        $this->tenant->domains()->create(['domain' => 'test']);

        // Initialize tenancy
        tenancy()->initialize($this->tenant);

        // Run tenant migrations
        $this->artisan('migrate', [
            '--path' => 'database/migrations/tenant',
            '--database' => 'tenant',
        ]);

        // Set the URL to tenant subdomain
        URL::forceRootUrl('http://test.ecommerce.test');
    }

    protected function tearDown(): void
    {
        // End tenancy context first
        tenancy()->end();

        // Drop the tenant MySQL database (RefreshDatabase only rolls back central DB transactions)
        DB::statement('DROP DATABASE IF EXISTS tenant_test');

        parent::tearDown();
    }
}

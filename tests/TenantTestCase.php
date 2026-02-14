<?php

namespace Tests;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

abstract class TenantTestCase extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test tenant
        $this->tenant = Tenant::create([
            'id' => 'test',
            'name' => 'Test Store',
            'email' => 'admin@test.test',
            'plan' => 'basic',
        ]);

        $this->tenant->domains()->create(['domain' => 'test']);

        // Initialize tenancy
        tenancy()->initialize($this->tenant);

        // Set the URL to tenant subdomain
        URL::forceRootUrl('http://test.ecommerce.test');
    }

    protected function tearDown(): void
    {
        tenancy()->end();
        parent::tearDown();
    }
}

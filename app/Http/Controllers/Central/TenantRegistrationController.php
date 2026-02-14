<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class TenantRegistrationController extends Controller
{
    public function create()
    {
        return Inertia::render('Central/Register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'subdomain' => [
                'required',
                'string',
                'max:63',
                'regex:/^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/',
                'unique:domains,domain',
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'subdomain.regex' => 'Subdomain can only contain lowercase letters, numbers, and hyphens.',
            'subdomain.unique' => 'This subdomain is already taken.',
        ]);

        $tenantId = Str::slug($validated['subdomain']);

        // Create tenant (this triggers automatic DB creation, migration, and seeding)
        $tenant = Tenant::create([
            'id' => $tenantId,
            'name' => $validated['store_name'],
            'email' => $validated['email'],
            'plan' => 'free',
            'admin_name' => $validated['name'],
            'admin_email' => $validated['email'],
            'admin_password' => bcrypt($validated['password']),
        ]);

        // Create the subdomain
        $tenant->domains()->create([
            'domain' => $validated['subdomain'],
        ]);

        $tenantUrl = 'http://' . $validated['subdomain'] . '.' . config('tenancy.central_domains')[0];

        return redirect()->away($tenantUrl)
            ->with('success', 'Store created successfully! Please log in.');
    }
}

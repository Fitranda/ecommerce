<?php

namespace Tests\Feature;

use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TenantTestCase;

class ProductTest extends TenantTestCase
{
    private User $admin;
    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@test.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }

    public function test_admin_can_view_products_list(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/products')
            ->assertStatus(200);
    }

    public function test_customer_cannot_access_admin_products(): void
    {
        $this->actingAs($this->customer)
            ->get('/admin/products')
            ->assertStatus(403);
    }

    public function test_admin_can_create_product(): void
    {
        $category = Category::create([
            'name' => 'Test Category',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)
            ->post('/admin/products', [
                'name' => 'Test Product',
                'category_id' => $category->id,
                'description' => 'A test product description.',
                'price' => 29.99,
                'stock' => 10,
                'is_active' => true,
            ])
            ->assertRedirect('/admin/products');

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 29.99,
        ]);
    }

    public function test_admin_can_update_product(): void
    {
        $category = Category::create(['name' => 'Cat', 'is_active' => true]);
        $product = Product::create([
            'name' => 'Original',
            'category_id' => $category->id,
            'price' => 10.00,
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)
            ->put("/admin/products/{$product->id}", [
                'name' => 'Updated Product',
                'category_id' => $category->id,
                'price' => 15.00,
                'stock' => 20,
                'is_active' => true,
            ])
            ->assertRedirect('/admin/products');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'price' => 15.00,
        ]);
    }

    public function test_admin_can_delete_product(): void
    {
        $category = Category::create(['name' => 'Cat', 'is_active' => true]);
        $product = Product::create([
            'name' => 'To Delete',
            'category_id' => $category->id,
            'price' => 10.00,
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->actingAs($this->admin)
            ->delete("/admin/products/{$product->id}")
            ->assertRedirect('/admin/products');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_product_requires_valid_data(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/products', [
                'name' => '',
                'price' => -1,
            ])
            ->assertSessionHasErrors(['name', 'price', 'stock']);
    }

    public function test_storefront_shows_active_products(): void
    {
        $category = Category::create(['name' => 'Cat', 'is_active' => true]);

        Product::create([
            'name' => 'Active Product',
            'category_id' => $category->id,
            'price' => 10,
            'stock' => 5,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Inactive Product',
            'category_id' => $category->id,
            'price' => 10,
            'stock' => 5,
            'is_active' => false,
        ]);

        $this->get('/products')
            ->assertStatus(200);
    }
}

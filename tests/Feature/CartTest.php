<?php

namespace Tests\Feature;

use App\Models\Tenant\Cart;
use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TenantTestCase;

class CartTest extends TenantTestCase
{
    private User $customer;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@test.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $category = Category::create(['name' => 'Test', 'is_active' => true]);

        $this->product = Product::create([
            'name' => 'Cart Test Product',
            'category_id' => $category->id,
            'price' => 25.00,
            'stock' => 10,
            'is_active' => true,
        ]);
    }

    public function test_guest_can_view_cart(): void
    {
        $this->get('/cart')
            ->assertStatus(200);
    }

    public function test_guest_can_add_to_cart(): void
    {
        $this->post('/cart', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ])->assertRedirect();
    }

    public function test_cannot_add_more_than_stock(): void
    {
        $this->post('/cart', [
            'product_id' => $this->product->id,
            'quantity' => 999,
        ])->assertSessionHas('error');
    }

    public function test_authenticated_user_can_checkout(): void
    {
        $this->actingAs($this->customer);

        // Add to cart
        $this->post('/cart', [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        // Checkout
        $this->post('/checkout', [
            'shipping_name' => 'John Doe',
            'shipping_address' => '123 Test St',
            'shipping_phone' => '555-0123',
        ])->assertRedirect();

        // Verify order was created
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
        ]);

        // Verify stock decreased
        $this->product->refresh();
        $this->assertEquals(9, $this->product->stock);
    }

    public function test_guest_cannot_checkout(): void
    {
        $this->post('/checkout', [
            'shipping_name' => 'Guest',
            'shipping_address' => '123 St',
            'shipping_phone' => '555-0000',
        ])->assertRedirect('/login');
    }
}

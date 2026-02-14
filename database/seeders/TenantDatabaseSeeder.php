<?php

namespace Database\Seeders;

use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user from tenant registration data
        $tenant = tenant();
        User::create([
            'name' => $tenant->admin_name ?? 'Store Admin',
            'email' => $tenant->admin_email ?? 'admin@store.test',
            'password' => $tenant->admin_password ?? Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create sample customer
        User::create([
            'name' => 'John Customer',
            'email' => 'customer@store.test',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // Create categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'description' => 'Gadgets and electronic devices',
            'is_active' => true,
        ]);

        $clothing = Category::create([
            'name' => 'Clothing',
            'description' => 'Fashion and apparel',
            'is_active' => true,
        ]);

        $books = Category::create([
            'name' => 'Books',
            'description' => 'Books and literature',
            'is_active' => true,
        ]);

        // Create products
        $products = [
            ['name' => 'Wireless Headphones', 'category_id' => $electronics->id, 'price' => 79.99, 'stock' => 50, 'description' => 'High-quality wireless Bluetooth headphones with noise cancellation.'],
            ['name' => 'Smartphone Case', 'category_id' => $electronics->id, 'price' => 19.99, 'stock' => 100, 'description' => 'Durable protective case for modern smartphones.'],
            ['name' => 'USB-C Charger', 'category_id' => $electronics->id, 'price' => 24.99, 'stock' => 75, 'description' => 'Fast-charging USB-C wall adapter with 30W output.'],
            ['name' => 'Cotton T-Shirt', 'category_id' => $clothing->id, 'price' => 29.99, 'stock' => 200, 'description' => 'Comfortable 100% cotton t-shirt available in multiple colors.'],
            ['name' => 'Denim Jeans', 'category_id' => $clothing->id, 'price' => 59.99, 'stock' => 80, 'description' => 'Classic fit denim jeans with premium quality fabric.'],
            ['name' => 'Running Shoes', 'category_id' => $clothing->id, 'price' => 89.99, 'stock' => 60, 'description' => 'Lightweight running shoes with excellent cushioning.'],
            ['name' => 'Laravel Up & Running', 'category_id' => $books->id, 'price' => 39.99, 'stock' => 30, 'description' => 'A comprehensive guide to the Laravel PHP framework.'],
            ['name' => 'Vue.js in Action', 'category_id' => $books->id, 'price' => 34.99, 'stock' => 25, 'description' => 'Hands-on guide to building web applications with Vue.js.'],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, ['is_active' => true]));
        }
    }
}

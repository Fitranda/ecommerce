<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Cart;
use App\Models\Tenant\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::getCart(userId: $request->user()->id);
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('tenant.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        return Inertia::render('Tenant/Storefront/Checkout', [
            'cart' => [
                'items' => $cart->items->map(fn ($item) => [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                    'product' => [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'price' => $item->product->price,
                        'image' => $item->product->image,
                    ],
                ]),
                'total' => $cart->total,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_name' => ['required', 'string', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'shipping_phone' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $cart = Cart::getCart(userId: $request->user()->id);
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('tenant.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // Verify stock availability
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return back()->with('error', "Not enough stock for {$item->product->name}.");
            }
        }

        $order = DB::transaction(function () use ($cart, $validated, $request) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_amount' => $cart->total,
                'status' => 'pending',
                'shipping_name' => $validated['shipping_name'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_phone' => $validated['shipping_phone'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Decrease stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear cart
            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('tenant.orders.show', $order)
            ->with('success', 'Order placed successfully!');
    }
}

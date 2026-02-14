<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Cart;
use App\Models\Tenant\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::getCart(
            userId: $request->user()?->id,
            sessionId: $request->session()->getId()
        );

        $cart->load('items.product');

        return Inertia::render('Tenant/Storefront/Cart', [
            'cart' => [
                'items' => $cart->items->map(fn ($item) => [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                    'product' => [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'slug' => $item->product->slug,
                        'price' => $item->product->price,
                        'image' => $item->product->image,
                        'stock' => $item->product->stock,
                    ],
                ]),
                'total' => $cart->total,
                'item_count' => $cart->item_count,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cart = Cart::getCart(
            userId: $request->user()?->id,
            sessionId: $request->session()->getId()
        );

        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $newQty = $existingItem->quantity + $request->quantity;
            if ($newQty > $product->stock) {
                return back()->with('error', 'Not enough stock available.');
            }
            $existingItem->update(['quantity' => $newQty]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = Cart::getCart(
            userId: $request->user()?->id,
            sessionId: $request->session()->getId()
        );

        $item = $cart->items()->findOrFail($itemId);

        if ($request->quantity > $item->product->stock) {
            return back()->with('error', 'Not enough stock available.');
        }

        $item->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(Request $request, $itemId)
    {
        $cart = Cart::getCart(
            userId: $request->user()?->id,
            sessionId: $request->session()->getId()
        );

        $cart->items()->findOrFail($itemId)->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}

<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Customer: list own orders
     */
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return Inertia::render('Tenant/Storefront/Orders', [
            'orders' => $orders,
        ]);
    }

    /**
     * Customer: view own order detail
     */
    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            abort(403);
        }

        $order->load('items.product', 'user');

        return Inertia::render('Tenant/Storefront/OrderDetail', [
            'order' => $order,
        ]);
    }
}

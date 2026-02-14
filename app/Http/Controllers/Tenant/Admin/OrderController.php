<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->when($request->status, fn ($q, $status) => $q->byStatus($status))
            ->when($request->search, fn ($q, $search) => $q->where('order_number', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Tenant/Admin/Orders/Index', [
            'orders' => $orders,
            'filters' => $request->only('status', 'search'),
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');

        return Inertia::render('Tenant/Admin/Orders/Show', [
            'order' => $order,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
        ]);

        // If cancelling, restore stock
        if ($validated['status'] === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }

        $order->update(['status' => $validated['status']]);

        Cache::forget('admin_dashboard_stats');

        return back()->with('success', 'Order status updated.');
    }
}

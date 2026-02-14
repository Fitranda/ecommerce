<template>
  <StorefrontLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <nav class="text-sm text-gray-500 mb-6">
        <a href="/orders" class="hover:text-indigo-600">My Orders</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">{{ order.order_number }}</span>
      </nav>

      <div class="bg-white rounded-lg border p-6">
        <div class="flex justify-between items-start mb-6">
          <div>
            <h1 class="text-xl font-bold text-gray-900">{{ order.order_number }}</h1>
            <p class="text-sm text-gray-500">Placed on {{ new Date(order.created_at).toLocaleDateString() }}</p>
          </div>
          <span :class="statusClass(order.status)" class="inline-flex px-3 py-1 text-sm font-medium rounded-full">
            {{ order.status }}
          </span>
        </div>

        <!-- Items -->
        <h2 class="text-sm font-semibold text-gray-900 mb-3">Items</h2>
        <div class="border rounded-lg overflow-hidden mb-6">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="item in order.items" :key="item.id">
                <td class="px-4 py-3 text-sm text-gray-900">{{ item.product_name }}</td>
                <td class="px-4 py-3 text-sm text-gray-600 text-right">${{ Number(item.price).toFixed(2) }}</td>
                <td class="px-4 py-3 text-sm text-gray-600 text-right">{{ item.quantity }}</td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right">${{ (Number(item.price) * item.quantity).toFixed(2) }}</td>
              </tr>
            </tbody>
            <tfoot>
              <tr class="bg-gray-50">
                <td colspan="3" class="px-4 py-3 text-sm font-bold text-gray-900 text-right">Total</td>
                <td class="px-4 py-3 text-sm font-bold text-indigo-600 text-right">${{ Number(order.total_amount).toFixed(2) }}</td>
              </tr>
            </tfoot>
          </table>
        </div>

        <!-- Shipping -->
        <h2 class="text-sm font-semibold text-gray-900 mb-3">Shipping Details</h2>
        <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-600 space-y-1">
          <p><strong>Name:</strong> {{ order.shipping_name }}</p>
          <p><strong>Address:</strong> {{ order.shipping_address }}</p>
          <p><strong>Phone:</strong> {{ order.shipping_phone }}</p>
          <p v-if="order.notes"><strong>Notes:</strong> {{ order.notes }}</p>
        </div>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';

defineProps({
  order: Object,
});

function statusClass(status) {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    completed: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
}
</script>

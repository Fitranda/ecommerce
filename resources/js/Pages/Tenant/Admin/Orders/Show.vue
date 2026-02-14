<template>
  <AdminLayout>
    <template #header>Order {{ order.order_number }}</template>

    <div class="max-w-4xl space-y-6">
      <!-- Status -->
      <div class="bg-white rounded-lg border p-6 flex justify-between items-center">
        <div>
          <p class="text-sm text-gray-500">Order Status</p>
          <span :class="statusClass(order.status)" class="inline-flex px-3 py-1 text-sm font-medium rounded-full mt-1">{{ order.status }}</span>
        </div>
        <div class="flex items-center space-x-3">
          <select :value="order.status" @change="updateStatus($event.target.value)" class="border-gray-300 rounded-md shadow-sm px-3 py-2 border text-sm">
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
      </div>

      <!-- Customer -->
      <div class="bg-white rounded-lg border p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Customer</h3>
        <p class="text-sm text-gray-700">{{ order.user?.name }}</p>
        <p class="text-sm text-gray-500">{{ order.user?.email }}</p>
      </div>

      <!-- Items -->
      <div class="bg-white rounded-lg border overflow-hidden">
        <h3 class="text-sm font-semibold text-gray-900 p-4 border-b">Items</h3>
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
      <div class="bg-white rounded-lg border p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Shipping</h3>
        <div class="text-sm text-gray-600 space-y-1">
          <p><strong>Name:</strong> {{ order.shipping_name }}</p>
          <p><strong>Address:</strong> {{ order.shipping_address }}</p>
          <p><strong>Phone:</strong> {{ order.shipping_phone }}</p>
          <p v-if="order.notes"><strong>Notes:</strong> {{ order.notes }}</p>
        </div>
      </div>

      <a href="/admin/orders" class="inline-block text-sm text-indigo-600 hover:text-indigo-800">&larr; Back to Orders</a>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  order: Object,
});

function updateStatus(newStatus) {
  router.patch(`/admin/orders/${props.order.id}/status`, { status: newStatus });
}

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

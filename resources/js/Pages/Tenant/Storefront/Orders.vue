<template>
  <StorefrontLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-6">My Orders</h1>

      <div v-if="orders.data.length === 0" class="text-center py-12 text-gray-500">
        <p>You haven't placed any orders yet.</p>
        <a href="/products" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium">Start Shopping</a>
      </div>

      <div v-else class="space-y-4">
        <a v-for="order in orders.data" :key="order.id"
           :href="`/orders/${order.id}`"
           class="block bg-white rounded-lg border p-4 hover:shadow-md transition">
          <div class="flex justify-between items-start">
            <div>
              <p class="font-medium text-gray-900">{{ order.order_number }}</p>
              <p class="text-sm text-gray-500 mt-1">{{ new Date(order.created_at).toLocaleDateString() }}</p>
            </div>
            <div class="text-right">
              <span :class="statusClass(order.status)" class="inline-flex px-2 py-1 text-xs font-medium rounded-full">
                {{ order.status }}
              </span>
              <p class="text-lg font-bold text-indigo-600 mt-1">${{ Number(order.total_amount).toFixed(2) }}</p>
            </div>
          </div>
        </a>
      </div>

      <!-- Pagination -->
      <div v-if="orders.links && orders.links.length > 3" class="mt-8 flex justify-center space-x-1">
        <template v-for="link in orders.links" :key="link.label">
          <a v-if="link.url" :href="link.url" v-html="link.label"
             :class="[link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50', 'px-3 py-2 border rounded-md text-sm']" />
          <span v-else v-html="link.label" class="px-3 py-2 border rounded-md text-sm text-gray-400 bg-gray-50" />
        </template>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';

defineProps({
  orders: Object,
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

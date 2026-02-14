<template>
  <AdminLayout>
    <template #header>Dashboard</template>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <div class="bg-white p-6 rounded-lg border">
        <p class="text-sm font-medium text-gray-500">Total Products</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.total_products }}</p>
        <p class="text-sm text-gray-500 mt-1">{{ stats.active_products }} active</p>
      </div>
      <div class="bg-white p-6 rounded-lg border">
        <p class="text-sm font-medium text-gray-500">Total Orders</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.total_orders }}</p>
        <p class="text-sm text-orange-600 mt-1">{{ stats.pending_orders }} pending</p>
      </div>
      <div class="bg-white p-6 rounded-lg border">
        <p class="text-sm font-medium text-gray-500">Total Revenue</p>
        <p class="text-2xl font-bold text-indigo-600 mt-1">${{ Number(stats.total_revenue).toFixed(2) }}</p>
      </div>
      <div class="bg-white p-6 rounded-lg border">
        <p class="text-sm font-medium text-gray-500">Total Customers</p>
        <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.total_customers }}</p>
      </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
      <!-- Recent Orders -->
      <div class="bg-white rounded-lg border">
        <div class="p-4 border-b flex justify-between items-center">
          <h3 class="font-semibold text-gray-900">Recent Orders</h3>
          <a href="/admin/orders" class="text-sm text-indigo-600 hover:text-indigo-800">View all</a>
        </div>
        <div class="divide-y">
          <div v-for="order in recentOrders" :key="order.id" class="p-4 flex justify-between items-center">
            <div>
              <a :href="`/admin/orders/${order.id}`" class="text-sm font-medium text-gray-900 hover:text-indigo-600">{{ order.order_number }}</a>
              <p class="text-xs text-gray-500">{{ order.user?.name }} &middot; {{ new Date(order.created_at).toLocaleDateString() }}</p>
            </div>
            <div class="text-right">
              <span :class="statusClass(order.status)" class="inline-flex px-2 py-1 text-xs font-medium rounded-full">{{ order.status }}</span>
              <p class="text-sm font-medium mt-1">${{ Number(order.total_amount).toFixed(2) }}</p>
            </div>
          </div>
          <div v-if="!recentOrders.length" class="p-4 text-sm text-gray-500 text-center">No orders yet.</div>
        </div>
      </div>

      <!-- Low Stock Products -->
      <div class="bg-white rounded-lg border">
        <div class="p-4 border-b flex justify-between items-center">
          <h3 class="font-semibold text-gray-900">Low Stock Products</h3>
          <a href="/admin/products" class="text-sm text-indigo-600 hover:text-indigo-800">View all</a>
        </div>
        <div class="divide-y">
          <div v-for="product in lowStockProducts" :key="product.id" class="p-4 flex justify-between items-center">
            <div>
              <a :href="`/admin/products/${product.id}/edit`" class="text-sm font-medium text-gray-900 hover:text-indigo-600">{{ product.name }}</a>
            </div>
            <span class="text-sm font-medium text-orange-600">{{ product.stock }} left</span>
          </div>
          <div v-if="!lowStockProducts.length" class="p-4 text-sm text-gray-500 text-center">All products well stocked.</div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
  stats: Object,
  recentOrders: { type: Array, default: () => [] },
  lowStockProducts: { type: Array, default: () => [] },
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

<template>
  <AdminLayout>
    <template #header>Orders</template>

    <div class="mb-6 flex gap-3">
      <input v-model="localFilters.search" @keyup.enter="applyFilters" type="text" placeholder="Search by order number..." class="border-gray-300 rounded-md shadow-sm px-3 py-2 border text-sm" />
      <select v-model="localFilters.status" @change="applyFilters" class="border-gray-300 rounded-md shadow-sm px-3 py-2 border text-sm">
        <option value="">All Statuses</option>
        <option value="pending">Pending</option>
        <option value="processing">Processing</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>

    <div class="bg-white rounded-lg border overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-50">
            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ order.order_number }}</td>
            <td class="px-4 py-3 text-sm text-gray-600">{{ order.user?.name || 'N/A' }}</td>
            <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right">${{ Number(order.total_amount).toFixed(2) }}</td>
            <td class="px-4 py-3 text-center">
              <select :value="order.status" @change="updateStatus(order, $event.target.value)"
                      :class="statusClass(order.status)" class="text-xs font-medium rounded-full px-2 py-1 border-0 cursor-pointer">
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </td>
            <td class="px-4 py-3 text-sm text-gray-500">{{ new Date(order.created_at).toLocaleDateString() }}</td>
            <td class="px-4 py-3 text-right">
              <a :href="`/admin/orders/${order.id}`" class="text-indigo-600 hover:text-indigo-800 text-sm">View</a>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="!orders.data.length" class="p-8 text-center text-gray-500">No orders found.</div>
    </div>

    <!-- Pagination -->
    <div v-if="orders.links && orders.links.length > 3" class="mt-6 flex justify-center space-x-1">
      <template v-for="link in orders.links" :key="link.label">
        <a v-if="link.url" :href="link.url" v-html="link.label"
           :class="[link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50', 'px-3 py-2 border rounded-md text-sm']" />
        <span v-else v-html="link.label" class="px-3 py-2 border rounded-md text-sm text-gray-400 bg-gray-50" />
      </template>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  orders: Object,
  filters: { type: Object, default: () => ({}) },
});

const localFilters = reactive({
  search: props.filters.search || '',
  status: props.filters.status || '',
});

function applyFilters() {
  router.get('/admin/orders', {
    search: localFilters.search || undefined,
    status: localFilters.status || undefined,
  }, { preserveState: true });
}

function updateStatus(order, newStatus) {
  router.patch(`/admin/orders/${order.id}/status`, { status: newStatus }, { preserveScroll: true });
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

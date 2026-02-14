<template>
  <AdminLayout>
    <template #header>Orders</template>

    <v-card variant="outlined" class="pa-4 mb-6" style="border: 2px solid #EDE9FE;">
      <v-row align="center" no-gutters class="ga-3">
        <v-col cols="12" sm="4">
          <v-text-field v-model="localFilters.search" @keyup.enter="applyFilters" placeholder="Search by order number..." prepend-inner-icon="mdi-magnify" hide-details density="compact" />
        </v-col>
        <v-col cols="12" sm="3">
          <v-select v-model="localFilters.status" :items="statusItems" @update:model-value="applyFilters" placeholder="All Statuses" clearable hide-details density="compact" />
        </v-col>
      </v-row>
    </v-card>

    <v-card variant="outlined" style="border: 2px solid #EDE9FE;">
      <v-table hover>
        <thead style="background: #F5F3FF;">
          <tr>
            <th>Order</th>
            <th>Customer</th>
            <th class="text-right">Total</th>
            <th class="text-center">Status</th>
            <th>Date</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in orders.data" :key="order.id">
            <td class="font-weight-bold" style="color: #1E1B4B;">{{ order.order_number }}</td>
            <td class="text-grey-darken-1">{{ order.user?.name || 'N/A' }}</td>
            <td class="text-right font-weight-bold">${{ Number(order.total_amount).toFixed(2) }}</td>
            <td class="text-center">
              <v-select :model-value="order.status" @update:model-value="updateStatus(order, $event)" :items="statusItems" hide-details density="compact" variant="outlined" style="max-width: 150px; margin: 0 auto;" />
            </td>
            <td class="text-grey-darken-1">{{ new Date(order.created_at).toLocaleDateString() }}</td>
            <td class="text-right">
              <v-btn :href="`/admin/orders/${order.id}`" variant="text" color="primary" size="small" icon><v-icon icon="mdi-eye" /></v-btn>
            </td>
          </tr>
        </tbody>
      </v-table>
      <div v-if="!orders.data.length" class="pa-8 text-center text-grey">No orders found.</div>
    </v-card>

    <!-- Pagination -->
    <div v-if="orders.links && orders.links.length > 3" class="d-flex justify-center mt-6 ga-1">
      <template v-for="link in orders.links" :key="link.label">
        <v-btn v-if="link.url" :href="link.url" :color="link.active ? 'primary' : 'grey-lighten-3'" :variant="link.active ? 'flat' : 'outlined'" size="small" rounded="lg" v-html="link.label" min-width="40" />
        <v-btn v-else variant="text" size="small" disabled v-html="link.label" min-width="40" />
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

const statusItems = ['pending', 'processing', 'completed', 'cancelled'];

const localFilters = reactive({
  search: props.filters.search || '',
  status: props.filters.status || null,
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
</script>

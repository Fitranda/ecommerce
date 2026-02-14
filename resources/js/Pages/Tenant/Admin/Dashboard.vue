<template>
  <AdminLayout>
    <template #header>Dashboard</template>

    <!-- Stats Grid -->
    <v-row class="mb-6">
      <v-col cols="6" lg="3">
        <v-card variant="outlined" class="pa-5" style="border: 2px solid #EDE9FE;">
          <div class="d-flex align-center mb-2">
            <v-avatar color="primary" variant="tonal" size="40" class="mr-3"><v-icon icon="mdi-package-variant-closed" /></v-avatar>
            <span class="text-body-2 text-grey-darken-1">Total Products</span>
          </div>
          <p class="text-h4 font-weight-black" style="color: #1E1B4B;">{{ stats.total_products }}</p>
          <p class="text-body-2 text-grey">{{ stats.active_products }} active</p>
        </v-card>
      </v-col>
      <v-col cols="6" lg="3">
        <v-card variant="outlined" class="pa-5" style="border: 2px solid #FEF3C7;">
          <div class="d-flex align-center mb-2">
            <v-avatar color="secondary" variant="tonal" size="40" class="mr-3"><v-icon icon="mdi-clipboard-list" /></v-avatar>
            <span class="text-body-2 text-grey-darken-1">Total Orders</span>
          </div>
          <p class="text-h4 font-weight-black" style="color: #1E1B4B;">{{ stats.total_orders }}</p>
          <p class="text-body-2 text-warning">{{ stats.pending_orders }} pending</p>
        </v-card>
      </v-col>
      <v-col cols="6" lg="3">
        <v-card variant="outlined" class="pa-5" style="border: 2px solid #FCE7F3;">
          <div class="d-flex align-center mb-2">
            <v-avatar color="pink" variant="tonal" size="40" class="mr-3"><v-icon icon="mdi-cash-multiple" /></v-avatar>
            <span class="text-body-2 text-grey-darken-1">Total Revenue</span>
          </div>
          <p class="text-h4 font-weight-black text-primary">${{ Number(stats.total_revenue).toFixed(2) }}</p>
        </v-card>
      </v-col>
      <v-col cols="6" lg="3">
        <v-card variant="outlined" class="pa-5" style="border: 2px solid #D1FAE5;">
          <div class="d-flex align-center mb-2">
            <v-avatar color="success" variant="tonal" size="40" class="mr-3"><v-icon icon="mdi-account-group" /></v-avatar>
            <span class="text-body-2 text-grey-darken-1">Total Customers</span>
          </div>
          <p class="text-h4 font-weight-black" style="color: #1E1B4B;">{{ stats.total_customers }}</p>
        </v-card>
      </v-col>
    </v-row>

    <v-row>
      <!-- Recent Orders -->
      <v-col cols="12" lg="6">
        <v-card variant="outlined" style="border: 2px solid #EDE9FE;">
          <v-card-title class="d-flex justify-space-between align-center">
            <span class="font-weight-bold" style="color: #1E1B4B;">Recent Orders</span>
            <v-btn href="/admin/orders" variant="text" color="primary" size="small">View all</v-btn>
          </v-card-title>
          <v-divider />
          <v-list lines="two">
            <v-list-item v-for="order in recentOrders" :key="order.id" :href="`/admin/orders/${order.id}`">
              <v-list-item-title class="font-weight-bold">{{ order.order_number }}</v-list-item-title>
              <v-list-item-subtitle>{{ order.user?.name }} &middot; {{ new Date(order.created_at).toLocaleDateString() }}</v-list-item-subtitle>
              <template v-slot:append>
                <div class="text-right">
                  <v-chip :color="statusColor(order.status)" size="x-small" variant="flat">{{ order.status }}</v-chip>
                  <p class="text-body-2 font-weight-bold mt-1">${{ Number(order.total_amount).toFixed(2) }}</p>
                </div>
              </template>
            </v-list-item>
          </v-list>
          <div v-if="!recentOrders.length" class="pa-6 text-center text-grey">No orders yet.</div>
        </v-card>
      </v-col>

      <!-- Low Stock -->
      <v-col cols="12" lg="6">
        <v-card variant="outlined" style="border: 2px solid #FEF3C7;">
          <v-card-title class="d-flex justify-space-between align-center">
            <span class="font-weight-bold" style="color: #1E1B4B;">Low Stock Products</span>
            <v-btn href="/admin/products" variant="text" color="primary" size="small">View all</v-btn>
          </v-card-title>
          <v-divider />
          <v-list>
            <v-list-item v-for="product in lowStockProducts" :key="product.id" :href="`/admin/products/${product.id}/edit`">
              <v-list-item-title class="font-weight-bold">{{ product.name }}</v-list-item-title>
              <template v-slot:append>
                <v-chip color="warning" variant="flat" size="small">{{ product.stock }} left</v-chip>
              </template>
            </v-list-item>
          </v-list>
          <div v-if="!lowStockProducts.length" class="pa-6 text-center text-grey">All products well stocked.</div>
        </v-card>
      </v-col>
    </v-row>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
  stats: Object,
  recentOrders: { type: Array, default: () => [] },
  lowStockProducts: { type: Array, default: () => [] },
});

function statusColor(status) {
  const map = { pending: 'warning', processing: 'info', completed: 'success', cancelled: 'error' };
  return map[status] || 'grey';
}
</script>

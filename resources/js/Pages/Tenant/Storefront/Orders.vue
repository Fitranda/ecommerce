<template>
  <StorefrontLayout>
    <v-container style="max-width: 900px;" class="py-8">
      <h1 class="text-h4 font-weight-bold mb-6" style="color: #1E1B4B;">
        <v-icon icon="mdi-package-variant" color="primary" class="mr-2" />My Orders
      </h1>

      <div v-if="orders.data.length === 0" class="text-center py-16">
        <v-icon icon="mdi-package-variant-remove" size="80" color="grey-lighten-2" />
        <p class="text-h6 text-grey mt-4">You haven't placed any orders yet.</p>
        <v-btn href="/products" color="primary" rounded="pill" class="mt-4" prepend-icon="mdi-shopping">Start Shopping</v-btn>
      </div>

      <div v-else class="d-flex flex-column ga-3">
        <v-card v-for="order in orders.data" :key="order.id" :href="`/orders/${order.id}`" hover variant="outlined" class="pa-5 order-card" style="border: 2px solid #EDE9FE;">
          <div class="d-flex justify-space-between align-start">
            <div>
              <p class="text-subtitle-1 font-weight-bold" style="color: #1E1B4B;">{{ order.order_number }}</p>
              <p class="text-body-2 text-grey-darken-1 mt-1">{{ new Date(order.created_at).toLocaleDateString() }}</p>
            </div>
            <div class="text-right">
              <v-chip :color="statusColor(order.status)" variant="flat" size="small" class="mb-2">{{ order.status }}</v-chip>
              <p class="text-h6 font-weight-black text-primary">${{ Number(order.total_amount).toFixed(2) }}</p>
            </div>
          </div>
        </v-card>
      </div>

      <!-- Pagination -->
      <div v-if="orders.links && orders.links.length > 3" class="d-flex justify-center mt-8 ga-1">
        <template v-for="link in orders.links" :key="link.label">
          <v-btn v-if="link.url" :href="link.url" :color="link.active ? 'primary' : 'grey-lighten-3'" :variant="link.active ? 'flat' : 'outlined'" size="small" rounded="lg" v-html="link.label" min-width="40" />
          <v-btn v-else variant="text" size="small" disabled v-html="link.label" min-width="40" />
        </template>
      </div>
    </v-container>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';

defineProps({
  orders: Object,
});

function statusColor(status) {
  const map = { pending: 'warning', processing: 'info', completed: 'success', cancelled: 'error' };
  return map[status] || 'grey';
}
</script>

<style scoped>
.order-card { transition: all 0.3s ease; }
.order-card:hover { border-color: #7C3AED !important; transform: translateY(-2px); }
</style>

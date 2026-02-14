<template>
  <AdminLayout>
    <template #header>Order {{ order.order_number }}</template>

    <div style="max-width: 900px;">
      <!-- Status -->
      <v-card variant="outlined" class="pa-6 mb-4" style="border: 2px solid #EDE9FE;">
        <div class="d-flex justify-space-between align-center">
          <div>
            <p class="text-body-2 text-grey mb-1">Order Status</p>
            <v-chip :color="statusColor(order.status)" variant="flat">{{ order.status }}</v-chip>
          </div>
          <v-select :model-value="order.status" @update:model-value="updateStatus" :items="statusItems" hide-details density="compact" variant="outlined" style="max-width: 180px;" />
        </div>
      </v-card>

      <!-- Customer -->
      <v-card variant="outlined" class="pa-6 mb-4" style="border: 2px solid #EDE9FE;">
        <h3 class="text-subtitle-1 font-weight-bold mb-2" style="color: #1E1B4B;">Customer</h3>
        <p class="text-body-1">{{ order.user?.name }}</p>
        <p class="text-body-2 text-grey">{{ order.user?.email }}</p>
      </v-card>

      <!-- Items -->
      <v-card variant="outlined" class="mb-4" style="border: 2px solid #EDE9FE;">
        <v-card-title class="font-weight-bold" style="color: #1E1B4B;">Items</v-card-title>
        <v-table>
          <thead style="background: #F5F3FF;">
            <tr>
              <th>Product</th>
              <th class="text-right">Price</th>
              <th class="text-right">Qty</th>
              <th class="text-right">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in order.items" :key="item.id">
              <td>{{ item.product_name }}</td>
              <td class="text-right">${{ Number(item.price).toFixed(2) }}</td>
              <td class="text-right">{{ item.quantity }}</td>
              <td class="text-right font-weight-bold">${{ (Number(item.price) * item.quantity).toFixed(2) }}</td>
            </tr>
          </tbody>
          <tfoot>
            <tr style="background: #F5F3FF;">
              <td colspan="3" class="text-right font-weight-bold">Total</td>
              <td class="text-right font-weight-black text-primary text-h6">${{ Number(order.total_amount).toFixed(2) }}</td>
            </tr>
          </tfoot>
        </v-table>
      </v-card>

      <!-- Shipping -->
      <v-card variant="outlined" class="pa-6 mb-4" style="border: 2px solid #EDE9FE;">
        <h3 class="text-subtitle-1 font-weight-bold mb-3" style="color: #1E1B4B;">Shipping</h3>
        <v-card variant="tonal" color="primary" class="pa-4" rounded="lg">
          <div class="text-body-2 d-flex flex-column ga-1">
            <p><strong>Name:</strong> {{ order.shipping_name }}</p>
            <p><strong>Address:</strong> {{ order.shipping_address }}</p>
            <p><strong>Phone:</strong> {{ order.shipping_phone }}</p>
            <p v-if="order.notes"><strong>Notes:</strong> {{ order.notes }}</p>
          </div>
        </v-card>
      </v-card>

      <v-btn href="/admin/orders" variant="text" color="primary" prepend-icon="mdi-arrow-left">Back to Orders</v-btn>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  order: Object,
});

const statusItems = ['pending', 'processing', 'completed', 'cancelled'];

function updateStatus(newStatus) {
  router.patch(`/admin/orders/${props.order.id}/status`, { status: newStatus });
}

function statusColor(status) {
  const map = { pending: 'warning', processing: 'info', completed: 'success', cancelled: 'error' };
  return map[status] || 'grey';
}
</script>

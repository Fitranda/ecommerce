<template>
  <StorefrontLayout>
    <v-container style="max-width: 900px;" class="py-8">
      <v-breadcrumbs :items="[{ title: 'My Orders', href: '/orders' }, { title: order.order_number, disabled: true }]" class="px-0 mb-4" />

      <v-card variant="outlined" class="pa-6" style="border: 2px solid #EDE9FE;">
        <div class="d-flex justify-space-between align-start mb-6">
          <div>
            <h1 class="text-h5 font-weight-bold" style="color: #1E1B4B;">{{ order.order_number }}</h1>
            <p class="text-body-2 text-grey-darken-1">Placed on {{ new Date(order.created_at).toLocaleDateString() }}</p>
          </div>
          <v-chip :color="statusColor(order.status)" variant="flat" size="default">{{ order.status }}</v-chip>
        </div>

        <!-- Items Table -->
        <h2 class="text-subtitle-1 font-weight-bold mb-3" style="color: #1E1B4B;">Items</h2>
        <v-table class="rounded-lg border mb-6">
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

        <!-- Shipping -->
        <h2 class="text-subtitle-1 font-weight-bold mb-3" style="color: #1E1B4B;">Shipping Details</h2>
        <v-card variant="tonal" color="primary" class="pa-4" rounded="lg">
          <div class="text-body-2 d-flex flex-column ga-1">
            <p><strong>Name:</strong> {{ order.shipping_name }}</p>
            <p><strong>Address:</strong> {{ order.shipping_address }}</p>
            <p><strong>Phone:</strong> {{ order.shipping_phone }}</p>
            <p v-if="order.notes"><strong>Notes:</strong> {{ order.notes }}</p>
          </div>
        </v-card>
      </v-card>
    </v-container>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';

defineProps({
  order: Object,
});

function statusColor(status) {
  const map = { pending: 'warning', processing: 'info', completed: 'success', cancelled: 'error' };
  return map[status] || 'grey';
}
</script>

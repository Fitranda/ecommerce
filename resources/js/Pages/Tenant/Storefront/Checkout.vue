<template>
  <StorefrontLayout>
    <v-container style="max-width: 1000px;" class="py-8">
      <h1 class="text-h4 font-weight-bold mb-6" style="color: #1E1B4B;">
        <v-icon icon="mdi-credit-card-check" color="primary" class="mr-2" />Checkout
      </h1>

      <v-row>
        <!-- Shipping Form -->
        <v-col cols="12" md="7">
          <v-card class="pa-6" variant="outlined" style="border: 2px solid #EDE9FE;">
            <h2 class="text-h6 font-weight-bold mb-4" style="color: #1E1B4B;">
              <v-icon icon="mdi-truck-delivery" color="secondary" class="mr-2" />Shipping Information
            </h2>
            <v-form @submit.prevent="submit">
              <v-text-field v-model="form.shipping_name" label="Full Name" prepend-inner-icon="mdi-account" :error-messages="form.errors.shipping_name" class="mb-1" />
              <v-textarea v-model="form.shipping_address" label="Address" prepend-inner-icon="mdi-map-marker" rows="3" :error-messages="form.errors.shipping_address" class="mb-1" />
              <v-text-field v-model="form.shipping_phone" label="Phone" prepend-inner-icon="mdi-phone" :error-messages="form.errors.shipping_phone" class="mb-1" />
              <v-textarea v-model="form.notes" label="Notes (optional)" prepend-inner-icon="mdi-note-text" rows="2" class="mb-4" />
              <v-btn type="submit" color="primary" block size="large" :loading="form.processing" rounded="pill" prepend-icon="mdi-check-circle">
                Place Order
              </v-btn>
            </v-form>
          </v-card>
        </v-col>

        <!-- Order Summary -->
        <v-col cols="12" md="5">
          <v-card class="pa-6 position-sticky" style="top: 88px; border: 2px solid #FEF3C7;" variant="outlined">
            <h2 class="text-h6 font-weight-bold mb-4" style="color: #1E1B4B;">
              <v-icon icon="mdi-receipt-text" color="secondary" class="mr-2" />Order Summary
            </h2>
            <div v-for="item in cart.items" :key="item.id" class="d-flex justify-space-between text-body-2 mb-2">
              <span class="text-grey-darken-1">{{ item.product.name }} x{{ item.quantity }}</span>
              <span class="font-weight-bold">${{ Number(item.subtotal).toFixed(2) }}</span>
            </div>
            <v-divider class="my-3" />
            <div class="d-flex justify-space-between">
              <span class="text-h6 font-weight-bold" style="color: #1E1B4B;">Total</span>
              <span class="text-h5 font-weight-black text-primary">${{ Number(cart.total).toFixed(2) }}</span>
            </div>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
  cart: Object,
});

const form = useForm({
  shipping_name: '',
  shipping_address: '',
  shipping_phone: '',
  notes: '',
});

function submit() {
  form.post('/checkout');
}
</script>

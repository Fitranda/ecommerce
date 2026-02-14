<template>
  <StorefrontLayout>
    <v-container style="max-width: 900px;" class="py-8">
      <h1 class="text-h4 font-weight-bold mb-6" style="color: #1E1B4B;">
        <v-icon icon="mdi-cart" color="primary" class="mr-2" />Shopping Cart
      </h1>

      <div v-if="cart.items.length === 0" class="text-center py-16">
        <v-icon icon="mdi-cart-off" size="80" color="grey-lighten-2" />
        <p class="text-h6 text-grey mt-4">Your cart is empty.</p>
        <v-btn href="/products" color="primary" rounded="pill" class="mt-4" prepend-icon="mdi-shopping">Continue Shopping</v-btn>
      </div>

      <div v-else>
        <v-card variant="outlined" style="border: 2px solid #EDE9FE;" class="mb-6">
          <v-list lines="three">
            <template v-for="(item, idx) in cart.items" :key="item.id">
              <v-list-item>
                <template v-slot:prepend>
                  <v-avatar size="72" rounded="lg" class="mr-4 bg-grey-lighten-4">
                    <v-img v-if="item.product.image" :src="`/storage/${item.product.image}`" cover />
                    <v-icon v-else icon="mdi-image-off" color="grey-lighten-2" />
                  </v-avatar>
                </template>
                <v-list-item-title class="font-weight-bold" style="color: #1E1B4B;">
                  <a :href="`/products/${item.product.slug}`" class="text-decoration-none" style="color: #1E1B4B;">{{ item.product.name }}</a>
                </v-list-item-title>
                <v-list-item-subtitle>${{ Number(item.product.price).toFixed(2) }} each</v-list-item-subtitle>
                <template v-slot:append>
                  <div class="d-flex align-center ga-3">
                    <div class="d-flex align-center">
                      <v-btn icon variant="outlined" size="x-small" @click="updateQuantity(item, item.quantity - 1)" :disabled="item.quantity <= 1"><v-icon icon="mdi-minus" size="16" /></v-btn>
                      <span class="mx-3 font-weight-bold">{{ item.quantity }}</span>
                      <v-btn icon variant="outlined" size="x-small" @click="updateQuantity(item, item.quantity + 1)" :disabled="item.quantity >= item.product.stock"><v-icon icon="mdi-plus" size="16" /></v-btn>
                    </div>
                    <span class="text-subtitle-1 font-weight-black" style="min-width: 80px; text-align: right; color: #1E1B4B;">${{ Number(item.subtotal).toFixed(2) }}</span>
                    <v-btn icon variant="text" color="error" size="small" @click="removeItem(item)"><v-icon icon="mdi-delete" /></v-btn>
                  </div>
                </template>
              </v-list-item>
              <v-divider v-if="idx < cart.items.length - 1" />
            </template>
          </v-list>
        </v-card>

        <!-- Summary -->
        <v-card class="pa-6" style="border: 2px solid #EDE9FE;" variant="outlined">
          <div class="d-flex justify-space-between align-center mb-4">
            <span class="text-h6 font-weight-bold" style="color: #1E1B4B;">Total</span>
            <span class="text-h5 font-weight-black text-primary">${{ Number(cart.total).toFixed(2) }}</span>
          </div>
          <v-btn href="/checkout" color="primary" block size="large" rounded="pill" prepend-icon="mdi-credit-card-check">
            Proceed to Checkout
          </v-btn>
          <v-btn href="/products" variant="text" color="grey-darken-1" block class="mt-2">Continue Shopping</v-btn>
        </v-card>
      </div>
    </v-container>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { router } from '@inertiajs/vue3';

defineProps({
  cart: Object,
});

function updateQuantity(item, newQuantity) {
  if (newQuantity < 1) return;
  router.put(`/cart/${item.id}`, { quantity: newQuantity }, { preserveScroll: true });
}

function removeItem(item) {
  router.delete(`/cart/${item.id}`, { preserveScroll: true });
}
</script>

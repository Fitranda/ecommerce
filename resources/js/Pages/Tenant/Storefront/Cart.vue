<template>
  <StorefrontLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-6">Shopping Cart</h1>

      <div v-if="cart.items.length === 0" class="text-center py-12">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
        </svg>
        <p class="text-gray-500">Your cart is empty.</p>
        <a href="/products" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium">Continue Shopping</a>
      </div>

      <div v-else>
        <div class="bg-white rounded-lg border overflow-hidden">
          <div v-for="item in cart.items" :key="item.id" class="flex items-center p-4 border-b last:border-b-0">
            <div class="w-20 h-20 bg-gray-100 rounded flex-shrink-0 flex items-center justify-center overflow-hidden">
              <img v-if="item.product.image" :src="`/storage/${item.product.image}`" :alt="item.product.name" class="w-full h-full object-cover" />
              <svg v-else class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <div class="ml-4 flex-1">
              <a :href="`/products/${item.product.slug}`" class="text-sm font-medium text-gray-900 hover:text-indigo-600">{{ item.product.name }}</a>
              <p class="text-sm text-gray-500">${{ Number(item.product.price).toFixed(2) }} each</p>
            </div>
            <div class="flex items-center space-x-3">
              <div class="flex items-center border rounded-md">
                <button @click="updateQuantity(item, item.quantity - 1)" :disabled="item.quantity <= 1" class="px-2 py-1 text-gray-600 hover:bg-gray-100 disabled:opacity-50">-</button>
                <span class="px-3 py-1 text-sm">{{ item.quantity }}</span>
                <button @click="updateQuantity(item, item.quantity + 1)" :disabled="item.quantity >= item.product.stock" class="px-2 py-1 text-gray-600 hover:bg-gray-100 disabled:opacity-50">+</button>
              </div>
              <p class="w-20 text-right font-medium">${{ Number(item.subtotal).toFixed(2) }}</p>
              <button @click="removeItem(item)" class="text-red-500 hover:text-red-700 ml-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Summary -->
        <div class="mt-6 bg-white rounded-lg border p-6">
          <div class="flex justify-between text-lg font-bold">
            <span>Total</span>
            <span class="text-indigo-600">${{ Number(cart.total).toFixed(2) }}</span>
          </div>
          <a href="/checkout" class="mt-4 block w-full text-center bg-indigo-600 text-white py-3 rounded-md font-medium hover:bg-indigo-700 transition">
            Proceed to Checkout
          </a>
          <a href="/products" class="mt-2 block text-center text-sm text-gray-600 hover:text-indigo-600">
            Continue Shopping
          </a>
        </div>
      </div>
    </div>
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

<template>
  <StorefrontLayout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

      <div class="grid md:grid-cols-3 gap-8">
        <!-- Shipping Form -->
        <div class="md:col-span-2">
          <form @submit.prevent="submit" class="bg-white p-6 rounded-lg border space-y-5">
            <h2 class="text-lg font-semibold text-gray-900">Shipping Information</h2>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
              <input v-model="form.shipping_name" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border" />
              <p class="text-red-500 text-sm mt-1" v-if="form.errors.shipping_name">{{ form.errors.shipping_name }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
              <textarea v-model="form.shipping_address" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border"></textarea>
              <p class="text-red-500 text-sm mt-1" v-if="form.errors.shipping_address">{{ form.errors.shipping_address }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
              <input v-model="form.shipping_phone" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border" />
              <p class="text-red-500 text-sm mt-1" v-if="form.errors.shipping_phone">{{ form.errors.shipping_phone }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
              <textarea v-model="form.notes" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border"></textarea>
            </div>

            <button type="submit" :disabled="form.processing" class="w-full bg-indigo-600 text-white py-3 rounded-md font-medium hover:bg-indigo-700 transition disabled:opacity-50">
              {{ form.processing ? 'Placing Order...' : 'Place Order' }}
            </button>
          </form>
        </div>

        <!-- Order Summary -->
        <div>
          <div class="bg-white p-6 rounded-lg border sticky top-24">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
            <div class="space-y-3">
              <div v-for="item in cart.items" :key="item.id" class="flex justify-between text-sm">
                <span class="text-gray-600">{{ item.product.name }} x{{ item.quantity }}</span>
                <span class="font-medium">${{ Number(item.subtotal).toFixed(2) }}</span>
              </div>
              <hr>
              <div class="flex justify-between font-bold text-lg">
                <span>Total</span>
                <span class="text-indigo-600">${{ Number(cart.total).toFixed(2) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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

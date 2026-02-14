<template>
  <StorefrontLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <nav class="text-sm text-gray-500 mb-6">
        <a href="/products" class="hover:text-indigo-600">Products</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">{{ product.name }}</span>
      </nav>

      <div class="grid md:grid-cols-2 gap-8">
        <!-- Image -->
        <div class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
          <img v-if="product.image" :src="`/storage/${product.image}`" :alt="product.name" class="w-full h-full object-cover" />
          <svg v-else class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>

        <!-- Details -->
        <div>
          <span class="text-sm text-indigo-600 font-medium">{{ product.category?.name }}</span>
          <h1 class="text-3xl font-bold text-gray-900 mt-1">{{ product.name }}</h1>
          <p class="text-3xl font-bold text-indigo-600 mt-4">${{ Number(product.price).toFixed(2) }}</p>

          <div class="mt-4">
            <span v-if="product.stock > 5" class="text-sm text-green-600 font-medium">In Stock ({{ product.stock }})</span>
            <span v-else-if="product.stock > 0" class="text-sm text-orange-600 font-medium">Low Stock ({{ product.stock }} left)</span>
            <span v-else class="text-sm text-red-600 font-medium">Out of Stock</span>
          </div>

          <div class="mt-6" v-if="product.description">
            <h3 class="text-sm font-medium text-gray-900 mb-2">Description</h3>
            <p class="text-gray-600 whitespace-pre-line">{{ product.description }}</p>
          </div>

          <!-- Add to Cart -->
          <form v-if="product.stock > 0" @submit.prevent="addToCart" class="mt-8 flex items-center space-x-4">
            <div class="flex items-center border rounded-md">
              <button type="button" @click="quantity > 1 && quantity--" class="px-3 py-2 text-gray-600 hover:bg-gray-100">-</button>
              <input v-model.number="quantity" type="number" min="1" :max="product.stock"
                     class="w-16 text-center border-0 focus:ring-0 py-2" />
              <button type="button" @click="quantity < product.stock && quantity++" class="px-3 py-2 text-gray-600 hover:bg-gray-100">+</button>
            </div>
            <button type="submit" :disabled="cartForm.processing" class="flex-1 bg-indigo-600 text-white py-2 px-6 rounded-md hover:bg-indigo-700 transition disabled:opacity-50">
              {{ cartForm.processing ? 'Adding...' : 'Add to Cart' }}
            </button>
          </form>

          <!-- Related Products -->
          <div v-if="relatedProducts.length" class="mt-12">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Related Products</h3>
            <div class="grid grid-cols-2 gap-4">
              <a v-for="related in relatedProducts" :key="related.id"
                 :href="`/products/${related.slug}`"
                 class="bg-white rounded-lg border hover:shadow-md transition overflow-hidden">
                <div class="aspect-square bg-gray-100 flex items-center justify-center">
                  <img v-if="related.image" :src="`/storage/${related.image}`" :alt="related.name" class="w-full h-full object-cover" />
                  <svg v-else class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div class="p-3">
                  <h4 class="text-sm font-medium text-gray-900 truncate">{{ related.name }}</h4>
                  <p class="text-sm font-bold text-indigo-600">${{ Number(related.price).toFixed(2) }}</p>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  product: Object,
  relatedProducts: { type: Array, default: () => [] },
});

const quantity = ref(1);
const cartForm = useForm({});

function addToCart() {
  cartForm.transform(() => ({
    product_id: props.product.id,
    quantity: quantity.value,
  })).post('/cart');
}
</script>

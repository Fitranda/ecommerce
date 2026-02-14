<template>
  <StorefrontLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Hero -->
      <div class="bg-indigo-600 rounded-lg text-white p-8 mb-8">
        <h1 class="text-3xl font-bold">Welcome to {{ tenant?.name || 'Our Store' }}</h1>
        <p class="mt-2 text-indigo-100">Discover our amazing products</p>
        <a href="/products" class="mt-4 inline-block bg-white text-indigo-600 px-6 py-2 rounded-md font-medium hover:bg-gray-100 transition">
          Browse All Products
        </a>
      </div>

      <!-- Categories -->
      <section v-if="categories.length" class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Shop by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <a v-for="cat in categories" :key="cat.id"
             :href="`/products?category=${cat.id}`"
             class="bg-white p-4 rounded-lg border hover:shadow-md transition text-center">
            <h3 class="font-medium text-gray-900">{{ cat.name }}</h3>
            <p class="text-sm text-gray-500">{{ cat.products_count }} products</p>
          </a>
        </div>
      </section>

      <!-- Featured Products -->
      <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Featured Products</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <a v-for="product in featuredProducts" :key="product.id"
             :href="`/products/${product.slug}`"
             class="bg-white rounded-lg border hover:shadow-md transition overflow-hidden">
            <div class="aspect-square bg-gray-100 flex items-center justify-center">
              <img v-if="product.image" :src="`/storage/${product.image}`" :alt="product.name" class="w-full h-full object-cover" />
              <svg v-else class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <div class="p-4">
              <h3 class="text-sm font-medium text-gray-900 truncate">{{ product.name }}</h3>
              <p class="text-sm text-gray-500">{{ product.category?.name }}</p>
              <p class="text-lg font-bold text-indigo-600 mt-1">${{ Number(product.price).toFixed(2) }}</p>
            </div>
          </a>
        </div>
        <div v-if="!featuredProducts.length" class="text-center py-12 text-gray-500">
          No products available yet.
        </div>
      </section>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

defineProps({
  featuredProducts: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
});

const tenant = computed(() => usePage().props.tenant);
</script>

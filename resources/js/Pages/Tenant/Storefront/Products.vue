<template>
  <StorefrontLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-6">Products</h1>

      <!-- Filters -->
      <div class="flex flex-wrap gap-4 mb-6">
        <input v-model="localFilters.search" type="text" placeholder="Search products..."
               class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border text-sm" />
        <select v-model="localFilters.category" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border text-sm">
          <option value="">All Categories</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
        </select>
        <button @click="applyFilters" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">Filter</button>
        <button @click="resetFilters" class="text-gray-600 px-4 py-2 rounded-md text-sm hover:bg-gray-100 border">Reset</button>
      </div>

      <!-- Products Grid -->
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <a v-for="product in products.data" :key="product.id"
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
            <div class="flex justify-between items-center mt-2">
              <p class="text-lg font-bold text-indigo-600">${{ Number(product.price).toFixed(2) }}</p>
              <span v-if="product.stock <= 0" class="text-xs text-red-600 font-medium">Out of stock</span>
              <span v-else-if="product.stock <= 5" class="text-xs text-orange-600 font-medium">Low stock</span>
            </div>
          </div>
        </a>
      </div>

      <div v-if="!products.data.length" class="text-center py-12 text-gray-500">
        No products found.
      </div>

      <!-- Pagination -->
      <div v-if="products.links && products.links.length > 3" class="mt-8 flex justify-center space-x-1">
        <template v-for="link in products.links" :key="link.label">
          <a v-if="link.url"
             :href="link.url"
             v-html="link.label"
             :class="[link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50', 'px-3 py-2 border rounded-md text-sm']" />
          <span v-else v-html="link.label" class="px-3 py-2 border rounded-md text-sm text-gray-400 bg-gray-50" />
        </template>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  products: Object,
  categories: { type: Array, default: () => [] },
  filters: { type: Object, default: () => ({}) },
});

const localFilters = reactive({
  search: props.filters.search || '',
  category: props.filters.category || '',
});

function applyFilters() {
  router.get('/products', {
    search: localFilters.search || undefined,
    category: localFilters.category || undefined,
  }, { preserveState: true });
}

function resetFilters() {
  localFilters.search = '';
  localFilters.category = '';
  router.get('/products');
}
</script>

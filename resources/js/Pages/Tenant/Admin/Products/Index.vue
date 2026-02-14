<template>
  <AdminLayout>
    <template #header>Products</template>

    <div class="mb-6 flex justify-between items-center">
      <div class="flex gap-3">
        <input v-model="localFilters.search" type="text" placeholder="Search products..." class="border-gray-300 rounded-md shadow-sm px-3 py-2 border text-sm" />
        <select v-model="localFilters.category" class="border-gray-300 rounded-md shadow-sm px-3 py-2 border text-sm">
          <option value="">All Categories</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
        </select>
        <button @click="applyFilters" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-700">Filter</button>
      </div>
      <a href="/admin/products/create" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">Add Product</a>
    </div>

    <div class="bg-white rounded-lg border overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stock</th>
            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
            <td class="px-4 py-3">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-100 rounded flex-shrink-0 flex items-center justify-center overflow-hidden mr-3">
                  <img v-if="product.image" :src="`/storage/${product.image}`" class="w-full h-full object-cover" />
                  <span v-else class="text-gray-400 text-xs">IMG</span>
                </div>
                <span class="text-sm font-medium text-gray-900">{{ product.name }}</span>
              </div>
            </td>
            <td class="px-4 py-3 text-sm text-gray-600">{{ product.category?.name || '-' }}</td>
            <td class="px-4 py-3 text-sm text-gray-900 text-right">${{ Number(product.price).toFixed(2) }}</td>
            <td class="px-4 py-3 text-sm text-right" :class="product.stock <= 5 ? 'text-orange-600 font-medium' : 'text-gray-600'">{{ product.stock }}</td>
            <td class="px-4 py-3 text-center">
              <span :class="product.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="inline-flex px-2 py-1 text-xs font-medium rounded-full">
                {{ product.is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-4 py-3 text-right space-x-2">
              <a :href="`/admin/products/${product.id}/edit`" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
              <button @click="deleteProduct(product)" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="!products.data.length" class="p-8 text-center text-gray-500">No products found.</div>
    </div>

    <!-- Pagination -->
    <div v-if="products.links && products.links.length > 3" class="mt-6 flex justify-center space-x-1">
      <template v-for="link in products.links" :key="link.label">
        <a v-if="link.url" :href="link.url" v-html="link.label"
           :class="[link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50', 'px-3 py-2 border rounded-md text-sm']" />
        <span v-else v-html="link.label" class="px-3 py-2 border rounded-md text-sm text-gray-400 bg-gray-50" />
      </template>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
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
  router.get('/admin/products', {
    search: localFilters.search || undefined,
    category: localFilters.category || undefined,
  }, { preserveState: true });
}

function deleteProduct(product) {
  if (confirm(`Delete "${product.name}"?`)) {
    router.delete(`/admin/products/${product.id}`);
  }
}
</script>

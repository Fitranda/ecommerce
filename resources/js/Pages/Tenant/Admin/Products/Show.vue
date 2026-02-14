<template>
  <AdminLayout>
    <template #header>Product Details</template>

    <div class="max-w-2xl bg-white rounded-lg border p-6">
      <div class="flex items-start justify-between mb-6">
        <div>
          <h2 class="text-xl font-bold text-gray-900">{{ product.name }}</h2>
          <p class="text-sm text-gray-500">{{ product.category?.name }} &middot; {{ product.slug }}</p>
        </div>
        <span :class="product.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="inline-flex px-3 py-1 text-sm font-medium rounded-full">
          {{ product.is_active ? 'Active' : 'Inactive' }}
        </span>
      </div>

      <div v-if="product.image" class="mb-6">
        <img :src="`/storage/${product.image}`" class="w-48 h-48 object-cover rounded-lg" />
      </div>

      <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
          <p class="text-sm text-gray-500">Price</p>
          <p class="text-lg font-bold text-indigo-600">${{ Number(product.price).toFixed(2) }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-500">Stock</p>
          <p class="text-lg font-bold" :class="product.stock <= 5 ? 'text-orange-600' : 'text-gray-900'">{{ product.stock }}</p>
        </div>
      </div>

      <div v-if="product.description" class="mb-6">
        <p class="text-sm text-gray-500 mb-1">Description</p>
        <p class="text-gray-700 whitespace-pre-line">{{ product.description }}</p>
      </div>

      <div class="flex space-x-3">
        <a :href="`/admin/products/${product.id}/edit`" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">Edit</a>
        <a href="/admin/products" class="px-4 py-2 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Back</a>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
  product: Object,
});
</script>

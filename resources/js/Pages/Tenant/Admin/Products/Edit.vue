<template>
  <AdminLayout>
    <template #header>Edit Product</template>

    <div class="max-w-2xl">
      <form @submit.prevent="submit" class="bg-white p-6 rounded-lg border space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
          <input v-model="form.name" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border" />
          <p class="text-red-500 text-sm mt-1" v-if="form.errors.name">{{ form.errors.name }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
          <select v-model="form.category_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border">
            <option value="">Select category</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
          <p class="text-red-500 text-sm mt-1" v-if="form.errors.category_id">{{ form.errors.category_id }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
          <textarea v-model="form.description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
            <input v-model="form.price" type="number" step="0.01" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border" />
            <p class="text-red-500 text-sm mt-1" v-if="form.errors.price">{{ form.errors.price }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
            <input v-model="form.stock" type="number" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border" />
            <p class="text-red-500 text-sm mt-1" v-if="form.errors.stock">{{ form.errors.stock }}</p>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
          <div v-if="product.image" class="mb-2">
            <img :src="`/storage/${product.image}`" class="w-24 h-24 object-cover rounded" />
          </div>
          <input type="file" @change="form.image = $event.target.files[0]" accept="image/*" class="text-sm" />
          <p class="text-red-500 text-sm mt-1" v-if="form.errors.image">{{ form.errors.image }}</p>
        </div>

        <div class="flex items-center">
          <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" id="is_active" />
          <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
        </div>

        <div class="flex justify-end space-x-3">
          <a href="/admin/products" class="px-4 py-2 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancel</a>
          <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 disabled:opacity-50">
            {{ form.processing ? 'Saving...' : 'Update Product' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  product: Object,
  categories: { type: Array, default: () => [] },
});

const form = useForm({
  _method: 'PUT',
  name: props.product.name,
  category_id: props.product.category_id || '',
  description: props.product.description || '',
  price: props.product.price,
  stock: props.product.stock,
  image: null,
  is_active: props.product.is_active,
});

function submit() {
  form.post(`/admin/products/${props.product.id}`, {
    forceFormData: true,
  });
}
</script>

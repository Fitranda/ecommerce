<template>
  <AdminLayout>
    <template #header>Edit Category</template>

    <div class="max-w-lg">
      <form @submit.prevent="submit" class="bg-white p-6 rounded-lg border space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
          <input v-model="form.name" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border" />
          <p class="text-red-500 text-sm mt-1" v-if="form.errors.name">{{ form.errors.name }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
          <textarea v-model="form.description" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border"></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
          <div v-if="category.image" class="mb-2">
            <img :src="`/storage/${category.image}`" class="w-20 h-20 object-cover rounded" />
          </div>
          <input type="file" @change="form.image = $event.target.files[0]" accept="image/*" class="text-sm" />
          <p class="text-red-500 text-sm mt-1" v-if="form.errors.image">{{ form.errors.image }}</p>
        </div>

        <div class="flex items-center">
          <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" id="is_active" />
          <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
        </div>

        <div class="flex justify-end space-x-3">
          <a href="/admin/categories" class="px-4 py-2 border rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancel</a>
          <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 disabled:opacity-50">
            {{ form.processing ? 'Saving...' : 'Update Category' }}
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
  category: Object,
});

const form = useForm({
  _method: 'PUT',
  name: props.category.name,
  description: props.category.description || '',
  image: null,
  is_active: props.category.is_active,
});

function submit() {
  form.post(`/admin/categories/${props.category.id}`, {
    forceFormData: true,
  });
}
</script>

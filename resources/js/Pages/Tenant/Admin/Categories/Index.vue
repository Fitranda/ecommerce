<template>
  <AdminLayout>
    <template #header>Categories</template>

    <div class="mb-6 flex justify-between items-center">
      <input v-model="localSearch" @keyup.enter="applyFilter" type="text" placeholder="Search categories..." class="border-gray-300 rounded-md shadow-sm px-3 py-2 border text-sm" />
      <a href="/admin/categories/create" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">Add Category</a>
    </div>

    <div class="bg-white rounded-lg border overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Products</th>
            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="cat in categories.data" :key="cat.id" class="hover:bg-gray-50">
            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ cat.name }}</td>
            <td class="px-4 py-3 text-sm text-gray-600 text-right">{{ cat.products_count }}</td>
            <td class="px-4 py-3 text-center">
              <span :class="cat.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="inline-flex px-2 py-1 text-xs font-medium rounded-full">
                {{ cat.is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-4 py-3 text-right space-x-2">
              <a :href="`/admin/categories/${cat.id}/edit`" class="text-indigo-600 hover:text-indigo-800 text-sm">Edit</a>
              <button @click="deleteCategory(cat)" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="!categories.data.length" class="p-8 text-center text-gray-500">No categories found.</div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  categories: Object,
  filters: { type: Object, default: () => ({}) },
});

const localSearch = ref(props.filters.search || '');

function applyFilter() {
  router.get('/admin/categories', { search: localSearch.value || undefined }, { preserveState: true });
}

function deleteCategory(cat) {
  if (confirm(`Delete "${cat.name}"?`)) {
    router.delete(`/admin/categories/${cat.id}`);
  }
}
</script>

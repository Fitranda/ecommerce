<template>
  <AdminLayout>
    <template #header>Categories</template>

    <v-card variant="outlined" class="pa-4 mb-6" style="border: 2px solid #EDE9FE;">
      <v-row align="center" no-gutters class="ga-3">
        <v-col cols="12" sm="4">
          <v-text-field v-model="localSearch" @keyup.enter="applyFilter" placeholder="Search categories..." prepend-inner-icon="mdi-magnify" hide-details density="compact" />
        </v-col>
        <v-spacer />
        <v-col cols="auto">
          <v-btn href="/admin/categories/create" color="primary" prepend-icon="mdi-plus" rounded="pill">Add Category</v-btn>
        </v-col>
      </v-row>
    </v-card>

    <v-card variant="outlined" style="border: 2px solid #EDE9FE;">
      <v-table hover>
        <thead style="background: #F5F3FF;">
          <tr>
            <th>Name</th>
            <th class="text-right">Products</th>
            <th class="text-center">Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cat in categories.data" :key="cat.id">
            <td class="font-weight-bold" style="color: #1E1B4B;">{{ cat.name }}</td>
            <td class="text-right text-grey-darken-1">{{ cat.products_count }}</td>
            <td class="text-center">
              <v-chip :color="cat.is_active ? 'success' : 'grey'" variant="flat" size="small">{{ cat.is_active ? 'Active' : 'Inactive' }}</v-chip>
            </td>
            <td class="text-right">
              <v-btn :href="`/admin/categories/${cat.id}/edit`" variant="text" color="primary" size="small" icon><v-icon icon="mdi-pencil" /></v-btn>
              <v-btn @click="deleteCategory(cat)" variant="text" color="error" size="small" icon><v-icon icon="mdi-delete" /></v-btn>
            </td>
          </tr>
        </tbody>
      </v-table>
      <div v-if="!categories.data.length" class="pa-8 text-center text-grey">No categories found.</div>
    </v-card>
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

<template>
  <AdminLayout>
    <template #header>Products</template>

    <v-card variant="outlined" class="pa-4 mb-6" style="border: 2px solid #EDE9FE;">
      <v-row align="center" no-gutters class="ga-3">
        <v-col cols="12" sm="4">
          <v-text-field v-model="localFilters.search" placeholder="Search products..." prepend-inner-icon="mdi-magnify" hide-details density="compact" />
        </v-col>
        <v-col cols="12" sm="3">
          <v-select v-model="localFilters.category" :items="categoryOptions" item-title="name" item-value="id" placeholder="All Categories" clearable hide-details density="compact" />
        </v-col>
        <v-col cols="auto">
          <v-btn color="primary" @click="applyFilters" prepend-icon="mdi-filter" rounded="pill">Filter</v-btn>
        </v-col>
        <v-spacer />
        <v-col cols="auto">
          <v-btn href="/admin/products/create" color="primary" prepend-icon="mdi-plus" rounded="pill">Add Product</v-btn>
        </v-col>
      </v-row>
    </v-card>

    <v-card variant="outlined" style="border: 2px solid #EDE9FE;">
      <v-table hover>
        <thead style="background: #F5F3FF;">
          <tr>
            <th>Product</th>
            <th>Category</th>
            <th class="text-right">Price</th>
            <th class="text-right">Stock</th>
            <th class="text-center">Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products.data" :key="product.id">
            <td>
              <div class="d-flex align-center py-2">
                <v-avatar size="40" rounded="lg" class="mr-3 bg-grey-lighten-4">
                  <v-img v-if="product.image" :src="`/storage/${product.image}`" cover />
                  <v-icon v-else icon="mdi-image-off" size="20" color="grey-lighten-2" />
                </v-avatar>
                <span class="font-weight-bold" style="color: #1E1B4B;">{{ product.name }}</span>
              </div>
            </td>
            <td class="text-grey-darken-1">{{ product.category?.name || '-' }}</td>
            <td class="text-right font-weight-bold">${{ Number(product.price).toFixed(2) }}</td>
            <td class="text-right">
              <v-chip :color="product.stock <= 5 ? 'warning' : 'success'" variant="tonal" size="small">{{ product.stock }}</v-chip>
            </td>
            <td class="text-center">
              <v-chip :color="product.is_active ? 'success' : 'grey'" variant="flat" size="small">{{ product.is_active ? 'Active' : 'Inactive' }}</v-chip>
            </td>
            <td class="text-right">
              <v-btn :href="`/admin/products/${product.id}/edit`" variant="text" color="primary" size="small" icon><v-icon icon="mdi-pencil" /></v-btn>
              <v-btn @click="deleteProduct(product)" variant="text" color="error" size="small" icon><v-icon icon="mdi-delete" /></v-btn>
            </td>
          </tr>
        </tbody>
      </v-table>
      <div v-if="!products.data.length" class="pa-8 text-center text-grey">No products found.</div>
    </v-card>

    <!-- Pagination -->
    <div v-if="products.links && products.links.length > 3" class="d-flex justify-center mt-6 ga-1">
      <template v-for="link in products.links" :key="link.label">
        <v-btn v-if="link.url" :href="link.url" :color="link.active ? 'primary' : 'grey-lighten-3'" :variant="link.active ? 'flat' : 'outlined'" size="small" rounded="lg" v-html="link.label" min-width="40" />
        <v-btn v-else variant="text" size="small" disabled v-html="link.label" min-width="40" />
      </template>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { reactive, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  products: Object,
  categories: { type: Array, default: () => [] },
  filters: { type: Object, default: () => ({}) },
});

const categoryOptions = computed(() => props.categories.map(c => ({ id: c.id, name: c.name })));

const localFilters = reactive({
  search: props.filters.search || '',
  category: props.filters.category || null,
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

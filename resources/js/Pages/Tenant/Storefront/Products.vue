<template>
  <StorefrontLayout>
    <v-container style="max-width: 1200px;" class="py-8">
      <h1 class="text-h4 font-weight-bold mb-6" style="color: #1E1B4B;">Products</h1>

      <!-- Filters -->
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
          <v-col cols="auto">
            <v-btn variant="outlined" color="grey" @click="resetFilters" prepend-icon="mdi-refresh" rounded="pill">Reset</v-btn>
          </v-col>
        </v-row>
      </v-card>

      <!-- Products Grid -->
      <v-row>
        <v-col v-for="product in products.data" :key="product.id" cols="6" md="3">
          <v-card :href="`/products/${product.slug}`" hover class="product-card h-100" variant="outlined" style="border: 2px solid #F3F4F6;">
            <v-img :src="product.image ? `/storage/${product.image}` : ''" aspect-ratio="1" cover class="bg-grey-lighten-4">
              <template v-if="!product.image" v-slot:placeholder>
                <div class="d-flex align-center justify-center fill-height">
                  <v-icon icon="mdi-image-off" size="48" color="grey-lighten-2" />
                </div>
              </template>
              <v-chip v-if="product.stock <= 0" color="error" size="small" class="ma-2" variant="flat">Out of stock</v-chip>
              <v-chip v-else-if="product.stock <= 5" color="warning" size="small" class="ma-2" variant="flat">Low stock</v-chip>
            </v-img>
            <v-card-text class="pb-1">
              <p class="text-caption text-grey-darken-1 mb-1">{{ product.category?.name }}</p>
              <h3 class="text-subtitle-2 font-weight-bold text-truncate" style="color: #1E1B4B;">{{ product.name }}</h3>
            </v-card-text>
            <v-card-actions class="pt-0 px-4 pb-4">
              <span class="text-h6 font-weight-black text-primary">${{ Number(product.price).toFixed(2) }}</span>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>

      <div v-if="!products.data.length" class="text-center py-12">
        <v-icon icon="mdi-magnify-close" size="64" color="grey-lighten-2" />
        <p class="text-body-1 text-grey mt-3">No products found.</p>
      </div>

      <!-- Pagination -->
      <div v-if="products.links && products.links.length > 3" class="d-flex justify-center mt-8 ga-1">
        <template v-for="link in products.links" :key="link.label">
          <v-btn v-if="link.url" :href="link.url" :color="link.active ? 'primary' : 'grey-lighten-3'" :variant="link.active ? 'flat' : 'outlined'" size="small" rounded="lg" v-html="link.label" min-width="40" />
          <v-btn v-else variant="text" size="small" disabled v-html="link.label" min-width="40" />
        </template>
      </div>
    </v-container>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
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
  router.get('/products', {
    search: localFilters.search || undefined,
    category: localFilters.category || undefined,
  }, { preserveState: true });
}

function resetFilters() {
  localFilters.search = '';
  localFilters.category = null;
  router.get('/products');
}
</script>

<style scoped>
.product-card { transition: all 0.3s ease; }
.product-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(124, 58, 237, 0.12) !important; }
</style>

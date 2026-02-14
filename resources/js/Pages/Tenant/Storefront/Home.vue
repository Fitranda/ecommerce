<template>
  <StorefrontLayout>
    <v-container style="max-width: 1200px;" class="py-8">
      <!-- Hero Banner -->
      <v-card class="mb-10 overflow-hidden" rounded="xl" elevation="0">
        <div style="background: linear-gradient(135deg, #7C3AED 0%, #EC4899 50%, #F59E0B 100%); padding: 48px 40px;">
          <h1 class="text-h3 font-weight-black text-white mb-2">Welcome to {{ tenant?.name || 'Our Store' }}</h1>
          <p class="text-h6 font-weight-regular mb-6" style="color: rgba(255,255,255,0.85);">Discover our amazing products</p>
          <v-btn href="/products" color="white" size="large" rounded="pill" class="text-primary font-weight-bold" prepend-icon="mdi-shopping">
            Browse All Products
          </v-btn>
        </div>
      </v-card>

      <!-- Categories -->
      <section v-if="categories.length" class="mb-12">
        <div class="d-flex align-center mb-5">
          <v-icon icon="mdi-tag-multiple" color="secondary" size="28" class="mr-2" />
          <h2 class="text-h5 font-weight-bold" style="color: #1E1B4B;">Shop by Category</h2>
        </div>
        <v-row>
          <v-col v-for="cat in categories" :key="cat.id" cols="6" md="3">
            <v-card :href="`/products?category=${cat.id}`" variant="outlined" hover class="text-center pa-5 category-card" style="border: 2px solid #EDE9FE;">
              <v-avatar color="primary" variant="tonal" size="48" class="mb-3">
                <v-icon icon="mdi-tag" />
              </v-avatar>
              <h3 class="text-subtitle-1 font-weight-bold" style="color: #1E1B4B;">{{ cat.name }}</h3>
              <p class="text-body-2 text-grey-darken-1">{{ cat.products_count }} products</p>
            </v-card>
          </v-col>
        </v-row>
      </section>

      <!-- Featured Products -->
      <section>
        <div class="d-flex align-center mb-5">
          <v-icon icon="mdi-star-circle" color="secondary" size="28" class="mr-2" />
          <h2 class="text-h5 font-weight-bold" style="color: #1E1B4B;">Featured Products</h2>
        </div>
        <v-row>
          <v-col v-for="product in featuredProducts" :key="product.id" cols="6" md="3">
            <v-card :href="`/products/${product.slug}`" hover class="product-card h-100" variant="outlined" style="border: 2px solid #F3F4F6;">
              <v-img :src="product.image ? `/storage/${product.image}` : ''" aspect-ratio="1" cover class="bg-grey-lighten-4">
                <template v-if="!product.image" v-slot:placeholder>
                  <div class="d-flex align-center justify-center fill-height">
                    <v-icon icon="mdi-image-off" size="48" color="grey-lighten-2" />
                  </div>
                </template>
              </v-img>
              <v-card-text class="pb-2">
                <p class="text-caption text-grey-darken-1 mb-1">{{ product.category?.name }}</p>
                <h3 class="text-subtitle-2 font-weight-bold text-truncate" style="color: #1E1B4B;">{{ product.name }}</h3>
              </v-card-text>
              <v-card-actions class="pt-0 px-4 pb-4">
                <span class="text-h6 font-weight-black text-primary">${{ Number(product.price).toFixed(2) }}</span>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
        <div v-if="!featuredProducts.length" class="text-center py-12">
          <v-icon icon="mdi-package-variant" size="64" color="grey-lighten-2" />
          <p class="text-body-1 text-grey mt-3">No products available yet.</p>
        </div>
      </section>
    </v-container>
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

<style scoped>
.category-card { transition: all 0.3s ease; }
.category-card:hover { transform: translateY(-2px); border-color: #7C3AED !important; }
.product-card { transition: all 0.3s ease; }
.product-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(124, 58, 237, 0.12) !important; }
</style>

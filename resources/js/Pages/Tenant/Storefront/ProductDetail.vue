<template>
  <StorefrontLayout>
    <v-container style="max-width: 1200px;" class="py-8">
      <v-breadcrumbs :items="[{ title: 'Products', href: '/products' }, { title: product.name, disabled: true }]" class="px-0 mb-4" />

      <v-row>
        <!-- Image -->
        <v-col cols="12" md="6">
          <v-card variant="outlined" rounded="xl" style="border: 2px solid #EDE9FE;">
            <v-img :src="product.image ? `/storage/${product.image}` : ''" aspect-ratio="1" cover class="bg-grey-lighten-4">
              <template v-if="!product.image" v-slot:placeholder>
                <div class="d-flex align-center justify-center fill-height">
                  <v-icon icon="mdi-image-off" size="80" color="grey-lighten-2" />
                </div>
              </template>
            </v-img>
          </v-card>
        </v-col>

        <!-- Details -->
        <v-col cols="12" md="6">
          <v-chip color="primary" variant="tonal" size="small" class="mb-2">{{ product.category?.name }}</v-chip>
          <h1 class="text-h4 font-weight-black mb-2" style="color: #1E1B4B;">{{ product.name }}</h1>
          <p class="text-h4 font-weight-black text-primary mb-4">${{ Number(product.price).toFixed(2) }}</p>

          <v-chip v-if="product.stock > 5" color="success" variant="tonal" prepend-icon="mdi-check-circle" class="mb-4">In Stock ({{ product.stock }})</v-chip>
          <v-chip v-else-if="product.stock > 0" color="warning" variant="tonal" prepend-icon="mdi-alert" class="mb-4">Low Stock ({{ product.stock }} left)</v-chip>
          <v-chip v-else color="error" variant="tonal" prepend-icon="mdi-close-circle" class="mb-4">Out of Stock</v-chip>

          <div v-if="product.description" class="mb-6">
            <h3 class="text-subtitle-1 font-weight-bold mb-2" style="color: #1E1B4B;">Description</h3>
            <p class="text-body-1 text-grey-darken-1" style="white-space: pre-line;">{{ product.description }}</p>
          </div>

          <!-- Add to Cart -->
          <v-card v-if="product.stock > 0" variant="tonal" color="primary" class="pa-4" rounded="xl">
            <v-form @submit.prevent="addToCart">
              <div class="d-flex align-center ga-4">
                <div class="d-flex align-center">
                  <v-btn icon variant="outlined" color="primary" size="small" @click="quantity > 1 && quantity--"><v-icon icon="mdi-minus" /></v-btn>
                  <v-text-field v-model.number="quantity" type="number" min="1" :max="product.stock" hide-details density="compact" style="max-width: 70px;" class="mx-2 text-center" />
                  <v-btn icon variant="outlined" color="primary" size="small" @click="quantity < product.stock && quantity++"><v-icon icon="mdi-plus" /></v-btn>
                </div>
                <v-btn type="submit" color="primary" size="large" :loading="cartForm.processing" rounded="pill" prepend-icon="mdi-cart-plus" class="flex-grow-1">
                  Add to Cart
                </v-btn>
              </div>
            </v-form>
          </v-card>

          <!-- Related Products -->
          <div v-if="relatedProducts.length" class="mt-10">
            <h3 class="text-h6 font-weight-bold mb-4" style="color: #1E1B4B;">Related Products</h3>
            <v-row>
              <v-col v-for="related in relatedProducts" :key="related.id" cols="6">
                <v-card :href="`/products/${related.slug}`" hover variant="outlined" class="product-card" style="border: 2px solid #F3F4F6;">
                  <v-img :src="related.image ? `/storage/${related.image}` : ''" aspect-ratio="1" cover class="bg-grey-lighten-4">
                    <template v-if="!related.image" v-slot:placeholder>
                      <div class="d-flex align-center justify-center fill-height">
                        <v-icon icon="mdi-image-off" size="32" color="grey-lighten-2" />
                      </div>
                    </template>
                  </v-img>
                  <v-card-text class="pa-3">
                    <h4 class="text-subtitle-2 font-weight-bold text-truncate" style="color: #1E1B4B;">{{ related.name }}</h4>
                    <span class="text-subtitle-1 font-weight-black text-primary">${{ Number(related.price).toFixed(2) }}</span>
                  </v-card-text>
                </v-card>
              </v-col>
            </v-row>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  product: Object,
  relatedProducts: { type: Array, default: () => [] },
});

const quantity = ref(1);
const cartForm = useForm({});

function addToCart() {
  cartForm.transform(() => ({
    product_id: props.product.id,
    quantity: quantity.value,
  })).post('/cart');
}
</script>

<style scoped>
.product-card { transition: all 0.3s ease; }
.product-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(124, 58, 237, 0.1) !important; }
</style>

<template>
  <AdminLayout>
    <template #header>Product Details</template>

    <v-card variant="outlined" class="pa-6" style="max-width: 700px; border: 2px solid #EDE9FE;">
      <div class="d-flex justify-space-between align-start mb-6">
        <div>
          <h2 class="text-h5 font-weight-bold" style="color: #1E1B4B;">{{ product.name }}</h2>
          <p class="text-body-2 text-grey-darken-1">{{ product.category?.name }} &middot; {{ product.slug }}</p>
        </div>
        <v-chip :color="product.is_active ? 'success' : 'grey'" variant="flat">{{ product.is_active ? 'Active' : 'Inactive' }}</v-chip>
      </div>

      <v-img v-if="product.image" :src="`/storage/${product.image}`" width="200" height="200" cover rounded="xl" class="mb-6 border" />

      <v-row class="mb-6">
        <v-col cols="6">
          <p class="text-body-2 text-grey mb-1">Price</p>
          <p class="text-h5 font-weight-black text-primary">${{ Number(product.price).toFixed(2) }}</p>
        </v-col>
        <v-col cols="6">
          <p class="text-body-2 text-grey mb-1">Stock</p>
          <p class="text-h5 font-weight-black" :class="product.stock <= 5 ? 'text-warning' : ''">{{ product.stock }}</p>
        </v-col>
      </v-row>

      <div v-if="product.description" class="mb-6">
        <p class="text-body-2 text-grey mb-1">Description</p>
        <p class="text-body-1" style="white-space: pre-line; color: #1E1B4B;">{{ product.description }}</p>
      </div>

      <div class="d-flex ga-3">
        <v-btn :href="`/admin/products/${product.id}/edit`" color="primary" rounded="pill" prepend-icon="mdi-pencil">Edit</v-btn>
        <v-btn href="/admin/products" variant="outlined" color="grey" rounded="pill">Back</v-btn>
      </div>
    </v-card>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
  product: Object,
});
</script>

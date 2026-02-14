<template>
  <AdminLayout>
    <template #header>Edit Product</template>

    <v-card variant="outlined" class="pa-6" style="max-width: 700px; border: 2px solid #EDE9FE;">
      <v-form @submit.prevent="submit">
        <v-text-field v-model="form.name" label="Name" prepend-inner-icon="mdi-package-variant" :error-messages="form.errors.name" class="mb-1" />

        <v-select v-model="form.category_id" :items="categoryOptions" item-title="name" item-value="id" label="Category" prepend-inner-icon="mdi-tag" clearable :error-messages="form.errors.category_id" class="mb-1" />

        <v-textarea v-model="form.description" label="Description" prepend-inner-icon="mdi-text" rows="4" class="mb-1" />

        <v-row class="mb-1">
          <v-col cols="6">
            <v-text-field v-model="form.price" label="Price" type="number" step="0.01" min="0" prepend-inner-icon="mdi-currency-usd" :error-messages="form.errors.price" />
          </v-col>
          <v-col cols="6">
            <v-text-field v-model="form.stock" label="Stock" type="number" min="0" prepend-inner-icon="mdi-archive" :error-messages="form.errors.stock" />
          </v-col>
        </v-row>

        <div v-if="product.image" class="mb-4">
          <v-img :src="`/storage/${product.image}`" width="120" height="120" cover rounded="lg" class="border" />
        </div>

        <v-file-input @update:model-value="form.image = $event" label="Image" prepend-icon="" prepend-inner-icon="mdi-camera" accept="image/*" :error-messages="form.errors.image" class="mb-1" />

        <v-checkbox v-model="form.is_active" label="Active" class="mb-4" />

        <div class="d-flex justify-end ga-3">
          <v-btn href="/admin/products" variant="outlined" color="grey" rounded="pill">Cancel</v-btn>
          <v-btn type="submit" color="primary" :loading="form.processing" rounded="pill" prepend-icon="mdi-content-save">Update Product</v-btn>
        </div>
      </v-form>
    </v-card>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  product: Object,
  categories: { type: Array, default: () => [] },
});

const categoryOptions = computed(() => props.categories.map(c => ({ id: c.id, name: c.name })));

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

<template>
  <AdminLayout>
    <template #header>Add Product</template>

    <v-card variant="outlined" class="pa-6" style="max-width: 700px; border: 2px solid #EDE9FE;">
      <v-form @submit.prevent="submit">
        <v-text-field v-model="form.name" label="Name" prepend-inner-icon="mdi-package-variant" :error-messages="form.errors.name" class="mb-1" />

        <v-select v-model="form.category_id" :items="categoryOptions" item-title="name" item-value="id" label="Category" prepend-inner-icon="mdi-tag" clearable :error-messages="form.errors.category_id" class="mb-1" />

        <v-textarea v-model="form.description" label="Description" prepend-inner-icon="mdi-text" rows="4" :error-messages="form.errors.description" class="mb-1" />

        <v-row class="mb-1">
          <v-col cols="6">
            <v-text-field v-model="form.price" label="Price" type="number" step="0.01" min="0" prepend-inner-icon="mdi-currency-usd" :error-messages="form.errors.price" />
          </v-col>
          <v-col cols="6">
            <v-text-field v-model="form.stock" label="Stock" type="number" min="0" prepend-inner-icon="mdi-archive" :error-messages="form.errors.stock" />
          </v-col>
        </v-row>

        <v-file-input @update:model-value="form.image = $event" label="Image" prepend-icon="" prepend-inner-icon="mdi-camera" accept="image/*" :error-messages="form.errors.image" class="mb-1" />

        <v-checkbox v-model="form.is_active" label="Active" class="mb-4" />

        <div class="d-flex justify-end ga-3">
          <v-btn href="/admin/products" variant="outlined" color="grey" rounded="pill">Cancel</v-btn>
          <v-btn type="submit" color="primary" :loading="form.processing" rounded="pill" prepend-icon="mdi-content-save">Save Product</v-btn>
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
  categories: { type: Array, default: () => [] },
});

const categoryOptions = computed(() => props.categories.map(c => ({ id: c.id, name: c.name })));

const form = useForm({
  name: '',
  category_id: '',
  description: '',
  price: '',
  stock: 0,
  image: null,
  is_active: true,
});

function submit() {
  form.post('/admin/products', {
    forceFormData: true,
  });
}
</script>

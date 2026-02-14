<template>
  <AdminLayout>
    <template #header>Add Category</template>

    <v-card variant="outlined" class="pa-6" style="max-width: 600px; border: 2px solid #EDE9FE;">
      <v-form @submit.prevent="submit">
        <v-text-field v-model="form.name" label="Name" prepend-inner-icon="mdi-tag" :error-messages="form.errors.name" class="mb-1" />
        <v-textarea v-model="form.description" label="Description" prepend-inner-icon="mdi-text" rows="3" class="mb-1" />
        <v-file-input @update:model-value="form.image = $event" label="Image" prepend-icon="" prepend-inner-icon="mdi-camera" accept="image/*" :error-messages="form.errors.image" class="mb-1" />
        <v-checkbox v-model="form.is_active" label="Active" class="mb-4" />

        <div class="d-flex justify-end ga-3">
          <v-btn href="/admin/categories" variant="outlined" color="grey" rounded="pill">Cancel</v-btn>
          <v-btn type="submit" color="primary" :loading="form.processing" rounded="pill" prepend-icon="mdi-content-save">Save Category</v-btn>
        </div>
      </v-form>
    </v-card>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  description: '',
  image: null,
  is_active: true,
});

function submit() {
  form.post('/admin/categories', {
    forceFormData: true,
  });
}
</script>

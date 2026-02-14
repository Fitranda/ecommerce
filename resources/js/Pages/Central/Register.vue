<template>
  <CentralLayout>
    <v-container style="max-width: 560px;" class="py-16">
      <!-- Success Dialog -->
      <v-dialog v-model="showSuccess" persistent max-width="480">
        <v-card class="pa-8 text-center" rounded="xl">
          <v-avatar size="80" color="success" variant="tonal" class="mb-4">
            <v-icon icon="mdi-check-circle" size="48" />
          </v-avatar>
          <h2 class="text-h5 font-weight-bold mb-2" style="color: #1E1B4B;">Store Created!</h2>
          <p class="text-body-1 text-grey-darken-1 mb-2">
            <strong>{{ storeName }}</strong> has been created successfully.
          </p>
          <p class="text-body-2 text-grey mb-6">
            Your store is ready. Click below to visit and log in with your admin credentials.
          </p>
          <v-btn :href="tenantUrl" color="primary" size="large" block rounded="pill" prepend-icon="mdi-open-in-new" class="mb-3">
            Go to My Store
          </v-btn>
          <v-btn href="/" variant="text" color="grey" size="small">Back to Home</v-btn>
        </v-card>
      </v-dialog>

      <v-card v-if="!success" class="pa-8" style="border: 2px solid #EDE9FE;">
        <div class="text-center mb-6">
          <v-avatar size="64" color="primary" variant="tonal" class="mb-3">
            <v-icon icon="mdi-store-plus" size="32" />
          </v-avatar>
          <h2 class="text-h5 font-weight-bold" style="color: #1E1B4B;">Create Your Store</h2>
          <p class="text-body-2 text-grey-darken-1 mt-1">Set up your eCommerce store in seconds</p>
        </div>

        <v-form @submit.prevent="submit">
          <v-text-field
            v-model="form.store_name"
            label="Store Name"
            prepend-inner-icon="mdi-store"
            placeholder="My Awesome Store"
            :error-messages="form.errors.store_name"
            class="mb-1"
          />

          <v-text-field
            v-model="form.subdomain"
            label="Subdomain"
            prepend-inner-icon="mdi-web"
            placeholder="mystore"
            suffix=".ecommerce.test"
            :error-messages="form.errors.subdomain"
            class="mb-1"
          />

          <v-text-field
            v-model="form.name"
            label="Admin Name"
            prepend-inner-icon="mdi-account"
            placeholder="John Doe"
            :error-messages="form.errors.name"
            class="mb-1"
          />

          <v-text-field
            v-model="form.email"
            label="Admin Email"
            type="email"
            prepend-inner-icon="mdi-email"
            placeholder="admin@example.com"
            :error-messages="form.errors.email"
            class="mb-1"
          />

          <v-text-field
            v-model="form.password"
            label="Admin Password"
            type="password"
            prepend-inner-icon="mdi-lock"
            :error-messages="form.errors.password"
            class="mb-1"
          />

          <v-text-field
            v-model="form.password_confirmation"
            label="Confirm Password"
            type="password"
            prepend-inner-icon="mdi-lock-check"
            class="mb-4"
          />

          <v-btn type="submit" color="primary" block size="large" :loading="form.processing" rounded="pill" prepend-icon="mdi-rocket-launch">
            Create Store
          </v-btn>
        </v-form>
      </v-card>
    </v-container>
  </CentralLayout>
</template>

<script setup>
import CentralLayout from '@/Layouts/CentralLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
  success: { type: Boolean, default: false },
  tenantUrl: { type: String, default: '' },
  storeName: { type: String, default: '' },
});

const showSuccess = ref(false);

onMounted(() => {
  if (props.success) {
    showSuccess.value = true;
  }
});

const form = useForm({
  store_name: '',
  subdomain: '',
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

function submit() {
  form.post('/register');
}
</script>

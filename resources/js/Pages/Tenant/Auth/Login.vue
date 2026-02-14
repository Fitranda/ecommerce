<template>
  <StorefrontLayout>
    <v-container style="max-width: 480px;" class="py-16">
      <v-alert v-if="isWelcome" type="success" variant="tonal" rounded="lg" class="mb-6" closable>
        <strong>Store created successfully!</strong> Please sign in with your admin credentials.
      </v-alert>

      <v-card class="pa-8" style="border: 2px solid #EDE9FE;">
        <div class="text-center mb-6">
          <v-avatar size="64" color="primary" variant="tonal" class="mb-3">
            <v-icon icon="mdi-login" size="32" />
          </v-avatar>
          <h2 class="text-h5 font-weight-bold" style="color: #1E1B4B;">Sign In</h2>
          <p class="text-body-2 text-grey-darken-1 mt-1">Welcome back to the store</p>
        </div>

        <v-form @submit.prevent="submit">
          <v-text-field
            v-model="form.email"
            label="Email"
            type="email"
            prepend-inner-icon="mdi-email"
            :error-messages="form.errors.email"
            class="mb-1"
          />

          <v-text-field
            v-model="form.password"
            label="Password"
            type="password"
            prepend-inner-icon="mdi-lock"
            :error-messages="form.errors.password"
            class="mb-2"
          />

          <v-checkbox v-model="form.remember" label="Remember me" density="compact" class="mb-4" />

          <v-btn type="submit" color="primary" block size="large" :loading="form.processing" rounded="pill" prepend-icon="mdi-login">
            Sign In
          </v-btn>

          <p class="text-center text-body-2 text-grey-darken-1 mt-5">
            Don't have an account? <a href="/register" class="text-primary font-weight-bold text-decoration-none">Register</a>
          </p>
        </v-form>
      </v-card>
    </v-container>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const isWelcome = computed(() => {
  const params = new URLSearchParams(window.location.search);
  return params.get('welcome') === '1';
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

function submit() {
  form.post('/login');
}
</script>

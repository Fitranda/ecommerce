<template>
  <StorefrontLayout>
    <v-container style="max-width: 480px;" class="py-16">
      <v-card class="pa-8" style="border: 2px solid #EDE9FE;">
        <div class="text-center mb-6">
          <v-avatar size="64" color="secondary" variant="tonal" class="mb-3">
            <v-icon icon="mdi-account-plus" size="32" />
          </v-avatar>
          <h2 class="text-h5 font-weight-bold" style="color: #1E1B4B;">Create Account</h2>
          <p class="text-body-2 text-grey-darken-1 mt-1">Join and start shopping</p>
        </div>

        <v-form @submit.prevent="submit">
          <v-text-field v-model="form.name" label="Name" prepend-inner-icon="mdi-account" :error-messages="form.errors.name" class="mb-1" />
          <v-text-field v-model="form.email" label="Email" type="email" prepend-inner-icon="mdi-email" :error-messages="form.errors.email" class="mb-1" />
          <v-text-field v-model="form.password" label="Password" type="password" prepend-inner-icon="mdi-lock" :error-messages="form.errors.password" class="mb-1" />
          <v-text-field v-model="form.password_confirmation" label="Confirm Password" type="password" prepend-inner-icon="mdi-lock-check" class="mb-4" />

          <v-btn type="submit" color="primary" block size="large" :loading="form.processing" rounded="pill" prepend-icon="mdi-account-plus">
            Register
          </v-btn>

          <p class="text-center text-body-2 text-grey-darken-1 mt-5">
            Already have an account? <a href="/login" class="text-primary font-weight-bold text-decoration-none">Sign In</a>
          </p>
        </v-form>
      </v-card>
    </v-container>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

function submit() {
  form.post('/register');
}
</script>

<template>
  <StorefrontLayout>
    <div class="max-w-md mx-auto py-16 px-4">
      <div class="bg-white p-8 rounded-lg shadow-sm border">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Sign In</h2>

        <form @submit.prevent="submit" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input v-model="form.email" type="email" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border" />
            <p class="text-red-500 text-sm mt-1" v-if="form.errors.email">{{ form.errors.email }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input v-model="form.password" type="password" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border" />
            <p class="text-red-500 text-sm mt-1" v-if="form.errors.password">{{ form.errors.password }}</p>
          </div>

          <div class="flex items-center justify-between">
            <label class="flex items-center">
              <input v-model="form.remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
              <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
          </div>

          <button type="submit" :disabled="form.processing" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50">
            {{ form.processing ? 'Signing in...' : 'Sign In' }}
          </button>

          <p class="text-center text-sm text-gray-600">
            Don't have an account? <a href="/register" class="text-indigo-600 hover:text-indigo-800">Register</a>
          </p>
        </form>
      </div>
    </div>
  </StorefrontLayout>
</template>

<script setup>
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

function submit() {
  form.post('/login');
}
</script>

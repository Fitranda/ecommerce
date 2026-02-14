<template>
  <CentralLayout>
    <div class="max-w-lg mx-auto py-16 px-4">
      <div class="bg-white p-8 rounded-lg shadow-sm border">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Create Your Store</h2>

        <form @submit.prevent="submit" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Store Name</label>
            <input v-model="form.name" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border" placeholder="My Awesome Store" />
            <p class="text-red-500 text-sm mt-1" v-if="form.errors.name">{{ form.errors.name }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Subdomain</label>
            <div class="flex items-center">
              <input v-model="form.subdomain" type="text" class="flex-1 border-gray-300 rounded-l-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border" placeholder="mystore" />
              <span class="bg-gray-100 border border-l-0 border-gray-300 rounded-r-md px-3 py-2 text-sm text-gray-500">.ecommerce.test</span>
            </div>
            <p class="text-red-500 text-sm mt-1" v-if="form.errors.subdomain">{{ form.errors.subdomain }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Admin Email</label>
            <input v-model="form.email" type="email" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border" placeholder="admin@example.com" />
            <p class="text-red-500 text-sm mt-1" v-if="form.errors.email">{{ form.errors.email }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Admin Password</label>
            <input v-model="form.password" type="password" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border" placeholder="••••••••" />
            <p class="text-red-500 text-sm mt-1" v-if="form.errors.password">{{ form.errors.password }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input v-model="form.password_confirmation" type="password" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 border" placeholder="••••••••" />
          </div>

          <button type="submit" :disabled="form.processing" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
            {{ form.processing ? 'Creating Store...' : 'Create Store' }}
          </button>
        </form>
      </div>
    </div>
  </CentralLayout>
</template>

<script setup>
import CentralLayout from '@/Layouts/CentralLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  subdomain: '',
  email: '',
  password: '',
  password_confirmation: '',
});

function submit() {
  form.post('/register');
}
</script>

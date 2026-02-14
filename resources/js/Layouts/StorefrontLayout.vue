<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center space-x-8">
            <a href="/" class="text-xl font-bold text-indigo-600">
              {{ tenant?.name || 'Store' }}
            </a>
            <div class="hidden md:flex space-x-4">
              <a href="/" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Home</a>
              <a href="/products" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Products</a>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <a href="/cart" class="relative text-gray-700 hover:text-indigo-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
              </svg>
            </a>
            <template v-if="auth?.user">
              <span class="text-sm text-gray-700">{{ auth.user.name }}</span>
              <a v-if="auth.user.role === 'admin'" href="/admin/dashboard" class="text-sm text-indigo-600 hover:text-indigo-800">Admin</a>
              <a href="/orders" class="text-sm text-gray-600 hover:text-indigo-600">My Orders</a>
              <button @click="logout" class="text-sm text-red-600 hover:text-red-800">Logout</button>
            </template>
            <template v-else>
              <a href="/login" class="text-sm text-gray-700 hover:text-indigo-600">Login</a>
              <a href="/register" class="text-sm px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Register</a>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Flash Messages -->
    <div v-if="flash?.success" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
      <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md text-sm">
        {{ flash.success }}
      </div>
    </div>
    <div v-if="flash?.error" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
      <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md text-sm">
        {{ flash.error }}
      </div>
    </div>

    <!-- Content -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-8">
      <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <p class="text-center text-gray-500 text-sm">
          &copy; {{ new Date().getFullYear() }} {{ tenant?.name || 'Store' }}. Powered by Multi-Tenant eCommerce.
        </p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();
const auth = computed(() => page.props.auth);
const flash = computed(() => page.props.flash);
const tenant = computed(() => page.props.tenant);

function logout() {
  router.post('/logout');
}
</script>

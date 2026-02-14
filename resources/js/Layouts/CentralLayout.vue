<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <a :href="route('central.home')" class="text-xl font-bold text-indigo-600">
              Multi-Store Platform
            </a>
          </div>
          <div class="flex items-center space-x-4">
            <a :href="route('central.register')" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
              Create Your Store
            </a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Content -->
    <main>
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-auto">
      <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <p class="text-center text-gray-500 text-sm">
          &copy; {{ new Date().getFullYear() }} Multi-Tenant eCommerce Platform. All rights reserved.
        </p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';

const page = usePage();

function route(name) {
  const routes = {
    'central.home': '/',
    'central.register': '/register',
  };
  return routes[name] || '/';
}
</script>

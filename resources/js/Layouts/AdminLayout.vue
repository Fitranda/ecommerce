<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="flex">
      <aside class="w-64 min-h-screen bg-gray-900 text-white">
        <div class="p-6">
          <h2 class="text-lg font-bold">{{ tenant?.name || 'Store' }}</h2>
          <p class="text-gray-400 text-xs mt-1">Admin Panel</p>
        </div>
        <nav class="mt-2">
          <a href="/admin/dashboard"
             :class="[isActive('/admin/dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white']"
             class="flex items-center px-6 py-3 text-sm font-medium transition">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
          </a>
          <a href="/admin/products"
             :class="[isActive('/admin/products') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white']"
             class="flex items-center px-6 py-3 text-sm font-medium transition">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Products
          </a>
          <a href="/admin/categories"
             :class="[isActive('/admin/categories') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white']"
             class="flex items-center px-6 py-3 text-sm font-medium transition">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            Categories
          </a>
          <a href="/admin/orders"
             :class="[isActive('/admin/orders') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white']"
             class="flex items-center px-6 py-3 text-sm font-medium transition">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Orders
          </a>
          <hr class="border-gray-700 my-4 mx-6">
          <a href="/" class="flex items-center px-6 py-3 text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
            View Store
          </a>
          <button @click="logout" class="w-full flex items-center px-6 py-3 text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Logout
          </button>
        </nav>
      </aside>

      <!-- Main Content -->
      <div class="flex-1">
        <!-- Admin Top Bar -->
        <header class="bg-white shadow-sm border-b">
          <div class="px-6 py-4 flex justify-between items-center">
            <h1 class="text-lg font-semibold text-gray-900">
              <slot name="header">Dashboard</slot>
            </h1>
            <span class="text-sm text-gray-600">{{ auth?.user?.name }}</span>
          </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 mt-4" v-if="flash?.success">
          <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md text-sm">
            {{ flash.success }}
          </div>
        </div>
        <div class="px-6 mt-4" v-if="flash?.error">
          <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md text-sm">
            {{ flash.error }}
          </div>
        </div>

        <!-- Content -->
        <main class="p-6">
          <slot />
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();
const auth = computed(() => page.props.auth);
const flash = computed(() => page.props.flash);
const tenant = computed(() => page.props.tenant);

function isActive(path) {
  return window.location.pathname.startsWith(path);
}

function logout() {
  router.post('/logout');
}
</script>

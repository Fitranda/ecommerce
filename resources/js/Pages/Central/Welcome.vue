<template>
  <CentralLayout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <!-- Hero -->
      <div class="text-center mb-16">
        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl lg:text-6xl">
          Launch Your Online Store
          <span class="text-indigo-600">in Minutes</span>
        </h1>
        <p class="mt-4 text-xl text-gray-500 max-w-2xl mx-auto">
          Create your own fully-featured eCommerce store with separate database, product management, shopping cart, and more.
        </p>
        <a href="/register" class="mt-8 inline-flex items-center px-8 py-3 bg-indigo-600 text-white text-lg font-medium rounded-md hover:bg-indigo-700 transition">
          Get Started Free
        </a>
      </div>

      <!-- Features -->
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border">
          <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Isolated Database</h3>
          <p class="text-gray-500">Each store gets its own dedicated database for complete data isolation and security.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border">
          <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Product Management</h3>
          <p class="text-gray-500">Full CRUD for products and categories with image uploads and stock tracking.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border">
          <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Shopping Cart & Orders</h3>
          <p class="text-gray-500">Complete shopping experience with cart management, checkout, and order tracking.</p>
        </div>
      </div>
    </div>
  </CentralLayout>
</template>

<script setup>
import CentralLayout from '@/Layouts/CentralLayout.vue';
</script>

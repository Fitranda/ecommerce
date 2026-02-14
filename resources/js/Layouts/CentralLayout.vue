<template>
  <v-app>
    <!-- Gradient Top Bar -->
    <v-app-bar flat class="px-2" style="background: linear-gradient(135deg, #7C3AED 0%, #EC4899 100%);">
      <v-container class="d-flex align-center py-0" style="max-width: 1200px;">
        <v-app-bar-title>
          <a :href="route('central.home')" class="text-decoration-none d-flex align-center">
            <v-icon icon="mdi-store" size="28" color="white" class="mr-2" />
            <span class="text-h6 font-weight-bold text-white">Multi-Store Platform</span>
          </a>
        </v-app-bar-title>
        <v-spacer />
        <v-btn
          :href="route('central.register')"
          color="white"
          variant="flat"
          rounded="pill"
          class="text-primary font-weight-bold"
          prepend-icon="mdi-rocket-launch"
        >
          Create Your Store
        </v-btn>
      </v-container>
    </v-app-bar>

    <!-- Content -->
    <v-main>
      <slot />
    </v-main>

    <!-- Footer -->
    <v-footer class="bg-grey-darken-4 text-center" app>
      <v-container>
        <p class="text-body-2 text-grey-lighten-1 ma-0">
          &copy; {{ new Date().getFullYear() }} Multi-Tenant eCommerce Platform. All rights reserved.
        </p>
      </v-container>
    </v-footer>
  </v-app>
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

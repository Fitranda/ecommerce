<template>
  <v-app>
    <!-- Navigation -->
    <v-app-bar flat elevation="1" color="white" class="border-b">
      <v-container class="d-flex align-center py-0" style="max-width: 1200px;">
        <a href="/" class="text-decoration-none d-flex align-center">
          <v-avatar color="primary" size="36" class="mr-3">
            <v-icon icon="mdi-shopping" color="white" size="20" />
          </v-avatar>
          <span class="text-h6 font-weight-bold" style="color: #7C3AED;">{{ tenant?.name || 'Store' }}</span>
        </a>

        <div class="d-none d-md-flex ml-8 ga-1">
          <v-btn href="/" variant="text" color="primary" rounded="pill">Home</v-btn>
          <v-btn href="/products" variant="text" color="primary" rounded="pill">Products</v-btn>
        </div>

        <v-spacer />

        <v-btn href="/cart" icon variant="text" color="primary" class="mr-1">
          <v-icon icon="mdi-cart-outline" />
        </v-btn>

        <template v-if="auth?.user">
          <v-menu>
            <template v-slot:activator="{ props }">
              <v-btn v-bind="props" variant="tonal" color="primary" rounded="pill" class="ml-2">
                <v-icon icon="mdi-account-circle" class="mr-1" />
                {{ auth.user.name }}
                <v-icon icon="mdi-chevron-down" size="18" class="ml-1" />
              </v-btn>
            </template>
            <v-list rounded="lg" elevation="3">
              <v-list-item v-if="auth.user.role === 'admin'" href="/admin/dashboard" prepend-icon="mdi-view-dashboard">
                <v-list-item-title>Admin Panel</v-list-item-title>
              </v-list-item>
              <v-list-item href="/orders" prepend-icon="mdi-package-variant">
                <v-list-item-title>My Orders</v-list-item-title>
              </v-list-item>
              <v-divider class="my-1" />
              <v-list-item @click="logout" prepend-icon="mdi-logout" class="text-error">
                <v-list-item-title>Logout</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </template>
        <template v-else>
          <v-btn href="/login" variant="text" color="primary" rounded="pill" class="ml-1">Login</v-btn>
          <v-btn href="/register" variant="flat" color="primary" rounded="pill" class="ml-1" prepend-icon="mdi-account-plus">Register</v-btn>
        </template>
      </v-container>
    </v-app-bar>

    <!-- Flash Messages -->
    <v-main>
      <v-container v-if="flash?.success" style="max-width: 1200px;" class="pb-0">
        <v-alert type="success" variant="tonal" closable rounded="lg" class="mb-0">{{ flash.success }}</v-alert>
      </v-container>
      <v-container v-if="flash?.error" style="max-width: 1200px;" class="pb-0">
        <v-alert type="error" variant="tonal" closable rounded="lg" class="mb-0">{{ flash.error }}</v-alert>
      </v-container>

      <!-- Content -->
      <slot />
    </v-main>

    <!-- Footer -->
    <v-footer class="bg-grey-darken-4">
      <v-container style="max-width: 1200px;">
        <p class="text-body-2 text-grey-lighten-1 text-center ma-0 w-100">
          &copy; {{ new Date().getFullYear() }} {{ tenant?.name || 'Store' }}. Powered by Multi-Tenant eCommerce.
        </p>
      </v-container>
    </v-footer>
  </v-app>
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

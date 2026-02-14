<template>
  <v-app>
    <!-- Sidebar -->
    <v-navigation-drawer permanent width="260" style="background: linear-gradient(180deg, #1E1B4B 0%, #312E81 100%);">
      <div class="pa-5">
        <div class="d-flex align-center mb-1">
          <v-avatar color="primary" size="38" class="mr-3">
            <v-icon icon="mdi-store" color="white" />
          </v-avatar>
          <div>
            <div class="text-subtitle-1 font-weight-bold text-white">{{ tenant?.name || 'Store' }}</div>
            <div class="text-caption text-purple-lighten-3">Admin Panel</div>
          </div>
        </div>
      </div>

      <v-divider class="border-opacity-10 mx-4" color="white" />

      <v-list nav class="px-3 mt-2" bg-color="transparent">
        <v-list-item
          href="/admin/dashboard"
          :active="isActive('/admin/dashboard')"
          prepend-icon="mdi-view-dashboard"
          title="Dashboard"
          rounded="lg"
          color="white"
          class="mb-1 text-purple-lighten-3"
          active-class="bg-white bg-opacity-15 text-white"
        />
        <v-list-item
          href="/admin/products"
          :active="isActive('/admin/products')"
          prepend-icon="mdi-package-variant-closed"
          title="Products"
          rounded="lg"
          color="white"
          class="mb-1 text-purple-lighten-3"
          active-class="bg-white bg-opacity-15 text-white"
        />
        <v-list-item
          href="/admin/categories"
          :active="isActive('/admin/categories')"
          prepend-icon="mdi-tag-multiple"
          title="Categories"
          rounded="lg"
          color="white"
          class="mb-1 text-purple-lighten-3"
          active-class="bg-white bg-opacity-15 text-white"
        />
        <v-list-item
          href="/admin/orders"
          :active="isActive('/admin/orders')"
          prepend-icon="mdi-clipboard-list"
          title="Orders"
          rounded="lg"
          color="white"
          class="mb-1 text-purple-lighten-3"
          active-class="bg-white bg-opacity-15 text-white"
        />
      </v-list>

      <v-divider class="border-opacity-10 mx-4 my-2" color="white" />

      <v-list nav class="px-3" bg-color="transparent">
        <v-list-item href="/" prepend-icon="mdi-open-in-new" title="View Store" rounded="lg" class="text-purple-lighten-3" />
        <v-list-item @click="logout" prepend-icon="mdi-logout" title="Logout" rounded="lg" class="text-purple-lighten-3" />
      </v-list>
    </v-navigation-drawer>

    <!-- Main Content -->
    <v-main style="background-color: #F5F3FF;">
      <!-- Top Bar -->
      <v-app-bar flat color="white" elevation="1" class="border-b">
        <v-app-bar-title class="text-h6 font-weight-bold" style="color: #1E1B4B;">
          <slot name="header">Dashboard</slot>
        </v-app-bar-title>
        <template v-slot:append>
          <v-chip color="primary" variant="tonal" prepend-icon="mdi-account-circle">
            {{ auth?.user?.name }}
          </v-chip>
        </template>
      </v-app-bar>

      <!-- Flash Messages -->
      <v-container v-if="flash?.success" class="pb-0">
        <v-alert type="success" variant="tonal" closable rounded="lg">{{ flash.success }}</v-alert>
      </v-container>
      <v-container v-if="flash?.error" class="pb-0">
        <v-alert type="error" variant="tonal" closable rounded="lg">{{ flash.error }}</v-alert>
      </v-container>

      <!-- Content -->
      <v-container>
        <slot />
      </v-container>
    </v-main>
  </v-app>
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

<style scoped>
.bg-opacity-15 {
  background-color: rgba(255, 255, 255, 0.12) !important;
}
</style>

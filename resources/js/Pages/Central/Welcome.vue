<template>
  <CentralLayout>
    <v-container style="max-width: 1200px;" class="py-16">
      <!-- Hero Section -->
      <v-row justify="center" class="mb-16">
        <v-col cols="12" md="10" lg="8" class="text-center">
          <v-chip color="secondary" variant="flat" class="mb-6" prepend-icon="mdi-star">
            #1 Multi-Tenant Platform
          </v-chip>
          <h1 class="text-h2 text-md-h1 font-weight-black mb-4" style="color: #1E1B4B; line-height: 1.1;">
            Launch Your Online Store
            <span style="background: linear-gradient(135deg, #7C3AED, #EC4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
              in Minutes
            </span>
          </h1>
          <p class="text-h6 font-weight-regular text-grey-darken-1 mb-8 mx-auto" style="max-width: 600px;">
            Create your own fully-featured eCommerce store with separate database, product management, shopping cart, and more.
          </p>
          <v-btn href="/register" color="primary" size="x-large" rounded="pill" class="px-10 mr-3" prepend-icon="mdi-rocket-launch" elevation="8" style="text-transform: none; font-size: 1.1rem;">
            Get Started Free
          </v-btn>
          <v-btn href="#features" variant="outlined" color="primary" size="x-large" rounded="pill" class="px-8" style="text-transform: none; font-size: 1.1rem;">
            Learn More
          </v-btn>
        </v-col>
      </v-row>

      <!-- Features -->
      <div id="features">
        <v-row class="ga-6">
          <v-col cols="12" md="4">
            <v-card class="pa-6 h-100 feature-card" variant="outlined" style="border: 2px solid #EDE9FE;">
              <v-avatar size="56" color="primary" variant="tonal" class="mb-4">
                <v-icon icon="mdi-database-lock" size="28" />
              </v-avatar>
              <h3 class="text-h6 font-weight-bold mb-2" style="color: #1E1B4B;">Isolated Database</h3>
              <p class="text-body-1 text-grey-darken-1">Each store gets its own dedicated database for complete data isolation and security.</p>
            </v-card>
          </v-col>
          <v-col cols="12" md="4">
            <v-card class="pa-6 h-100 feature-card" variant="outlined" style="border: 2px solid #FEF3C7;">
              <v-avatar size="56" color="secondary" variant="tonal" class="mb-4">
                <v-icon icon="mdi-package-variant-closed" size="28" />
              </v-avatar>
              <h3 class="text-h6 font-weight-bold mb-2" style="color: #1E1B4B;">Product Management</h3>
              <p class="text-body-1 text-grey-darken-1">Full CRUD for products and categories with image uploads and stock tracking.</p>
            </v-card>
          </v-col>
          <v-col cols="12" md="4">
            <v-card class="pa-6 h-100 feature-card" variant="outlined" style="border: 2px solid #FCE7F3;">
              <v-avatar size="56" color="pink" variant="tonal" class="mb-4">
                <v-icon icon="mdi-cart-check" size="28" />
              </v-avatar>
              <h3 class="text-h6 font-weight-bold mb-2" style="color: #1E1B4B;">Shopping Cart & Orders</h3>
              <p class="text-body-1 text-grey-darken-1">Complete shopping experience with cart management, checkout, and order tracking.</p>
            </v-card>
          </v-col>
        </v-row>
      </div>
    </v-container>
  </CentralLayout>
</template>

<script setup>
import CentralLayout from '@/Layouts/CentralLayout.vue';
</script>

<style scoped>
.feature-card {
  transition: all 0.3s ease;
}
.feature-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(124, 58, 237, 0.12) !important;
}
</style>

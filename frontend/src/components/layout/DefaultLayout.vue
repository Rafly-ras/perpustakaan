<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 flex flex-col md:flex-row font-sans antialiased">
    <!-- Sidebar -->
    <AppSidebar :isOpen="isSidebarOpen" @close="isSidebarOpen = false" />

    <!-- Main Content Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      <!-- Header -->
      <AppHeader @toggle-sidebar="isSidebarOpen = !isSidebarOpen" />

      <!-- Page Content Slot -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import AppHeader from './AppHeader.vue';
import AppSidebar from './AppSidebar.vue';

const isSidebarOpen = ref(false);
</script>

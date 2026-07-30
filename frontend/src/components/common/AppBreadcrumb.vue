<template>
  <nav aria-label="Breadcrumb" class="flex">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs text-slate-400">
      <li class="inline-flex items-center">
        <router-link to="/dashboard" class="inline-flex items-center hover:text-primary-400 transition-colors">
          <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          Home
        </router-link>
      </li>
      <li v-for="(item, index) in items" :key="index" class="inline-flex items-center">
        <svg class="w-3.5 h-3.5 text-slate-600 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <router-link
          v-if="item.to && index < items.length - 1"
          :to="item.to"
          class="hover:text-primary-400 transition-colors font-medium"
        >
          {{ item.label }}
        </router-link>
        <span v-else class="text-slate-200 font-semibold">{{ item.label }}</span>
      </li>
    </ol>
  </nav>
</template>

<script setup lang="ts">
export interface BreadcrumbItem {
  label: string;
  to?: string;
}

defineProps<{
  items: BreadcrumbItem[];
}>();
</script>

<template>
  <div class="w-full overflow-hidden rounded-2xl border border-slate-800 bg-slate-900 shadow-xl">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm text-slate-300 border-collapse">
        <thead class="bg-slate-950/80 text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-800">
          <tr>
            <slot name="header" />
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/60">
          <tr v-if="loading">
            <td :colspan="colSpan" class="py-8 text-center text-slate-500">
              <div class="flex items-center justify-center space-x-2">
                <svg class="animate-spin h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memuat data...</span>
              </div>
            </td>
          </tr>
          <tr v-else-if="isEmpty">
            <td :colspan="colSpan" class="py-12">
              <slot name="empty">
                <EmptyState title="Tidak ada data" description="Data yang Anda cari tidak ditemukan." />
              </slot>
            </td>
          </tr>
          <slot v-else />
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import EmptyState from './EmptyState.vue';

withDefaults(
  defineProps<{
    loading?: boolean;
    isEmpty?: boolean;
    colSpan?: number;
  }>(),
  {
    loading: false,
    isEmpty: false,
    colSpan: 5,
  }
);
</script>

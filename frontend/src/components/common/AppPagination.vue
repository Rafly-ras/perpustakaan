<template>
  <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-xs text-slate-400">
    <div>
      Menampilkan
      <span class="font-semibold text-slate-200">{{ from }}</span>
      sampai
      <span class="font-semibold text-slate-200">{{ to }}</span>
      dari
      <span class="font-semibold text-slate-200">{{ total }}</span>
      data
    </div>

    <div class="flex items-center space-x-2">
      <button
        :disabled="currentPage <= 1"
        @click="$emit('page-change', currentPage - 1)"
        class="px-3 py-1.5 rounded-lg border border-slate-800 bg-slate-900 text-slate-300 hover:bg-slate-800 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
      >
        Sebelumnya
      </button>

      <div class="flex items-center space-x-1">
        <button
          v-for="page in totalPages"
          :key="page"
          @click="$emit('page-change', page)"
          :class="[
            'w-8 h-8 rounded-lg font-semibold transition-colors text-xs flex items-center justify-center',
            page === currentPage
              ? 'bg-primary-600 text-white shadow-md shadow-primary-600/30'
              : 'bg-slate-900 border border-slate-800 text-slate-400 hover:bg-slate-800 hover:text-slate-200'
          ]"
        >
          {{ page }}
        </button>
      </div>

      <button
        :disabled="currentPage >= totalPages"
        @click="$emit('page-change', currentPage + 1)"
        class="px-3 py-1.5 rounded-lg border border-slate-800 bg-slate-900 text-slate-300 hover:bg-slate-800 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
      >
        Selanjutnya
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
  defineProps<{
    currentPage: number;
    lastPage: number;
    perPage: number;
    total: number;
  }>(),
  {
    currentPage: 1,
    lastPage: 1,
    perPage: 10,
    total: 0,
  }
);

defineEmits<{
  (e: 'page-change', page: number): void;
}>();

const from = computed(() => (props.currentPage - 1) * props.perPage + 1);
const to = computed(() => Math.min(props.currentPage * props.perPage, props.total));
const totalPages = computed(() => Math.max(1, props.lastPage));
</script>

<template>
  <div class="animate-pulse space-y-4 w-full">
    <div v-for="i in rows" :key="i" class="h-10 bg-slate-800/60 rounded-xl w-full"></div>
  </div>
</template>

<script setup lang="ts">
withDefaults(
  defineProps<{
    rows?: number;
  }>(),
  {
    rows: 4,
  }
);
</script>

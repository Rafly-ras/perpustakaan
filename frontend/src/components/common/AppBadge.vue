<template>
  <span :class="badgeClasses">
    <span v-if="dot" class="w-1.5 h-1.5 rounded-full mr-1.5 shrink-0" :class="dotClasses"></span>
    <slot />
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
  defineProps<{
    variant?: 'primary' | 'success' | 'warning' | 'danger' | 'info' | 'neutral';
    size?: 'sm' | 'md';
    dot?: boolean;
  }>(),
  {
    variant: 'primary',
    size: 'md',
    dot: false,
  }
);

const badgeClasses = computed(() => {
  const base = 'inline-flex items-center font-semibold rounded-full border border-transparent transition-all select-none';

  const variants = {
    primary: 'bg-primary-950/80 text-primary-300 border-primary-800/40',
    success: 'bg-emerald-950/80 text-emerald-300 border-emerald-800/40',
    warning: 'bg-amber-950/80 text-amber-300 border-amber-800/40',
    danger: 'bg-rose-950/80 text-rose-300 border-rose-800/40',
    info: 'bg-sky-950/80 text-sky-300 border-sky-800/40',
    neutral: 'bg-slate-800/80 text-slate-300 border-slate-700/40',
  };

  const sizes = {
    sm: 'px-2 py-0.5 text-[10px]',
    md: 'px-2.5 py-1 text-xs',
  };

  return [base, variants[props.variant], sizes[props.size]].join(' ');
});

const dotClasses = computed(() => {
  const dots = {
    primary: 'bg-primary-400',
    success: 'bg-emerald-400',
    warning: 'bg-amber-400',
    danger: 'bg-rose-400',
    info: 'bg-sky-400',
    neutral: 'bg-slate-400',
  };
  return dots[props.variant];
});
</script>

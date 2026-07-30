<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="translate-x-full"
      enter-to-class="translate-x-0"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="translate-x-0"
      leave-to-class="translate-x-full"
    >
      <div v-if="show" class="fixed inset-0 z-50 overflow-hidden flex justify-end">
        <div @click="$emit('close')" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity"></div>
        <div class="relative w-full max-w-md bg-slate-900 border-l border-slate-800 shadow-2xl flex flex-col h-full z-10">
          <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800">
            <h3 class="text-lg font-bold text-white tracking-tight">{{ title }}</h3>
            <button @click="$emit('close')" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition-colors">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="flex-1 overflow-y-auto px-6 py-4">
            <slot />
          </div>
          <div v-if="$slots.footer" class="px-6 py-4 bg-slate-950/50 border-t border-slate-800">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
defineProps<{
  show: boolean;
  title?: string;
}>();

defineEmits<{
  (e: 'close'): void;
}>();
</script>

<template>
  <Teleport to="body">
    <div class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none">
      <TransitionGroup
        enter-active-class="transition duration-300 ease-out transform"
        enter-from-class="translate-y-2 opacity-0 scale-95"
        enter-to-class="translate-y-0 opacity-100 scale-100"
        leave-active-class="transition duration-200 ease-in transform"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
      >
        <div
          v-for="toast in toasts"
          :key="toast.id"
          class="pointer-events-auto p-4 rounded-xl border shadow-2xl flex items-start gap-3 text-xs font-medium"
          :class="toastClasses(toast.type)"
        >
          <svg v-if="toast.type === 'success'" class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <svg v-else-if="toast.type === 'error'" class="w-4 h-4 text-rose-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          <svg v-else class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          
          <div class="flex-1 text-slate-200">
            {{ toast.message }}
          </div>

          <button @click="removeToast(toast.id)" class="text-slate-400 hover:text-white transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { ref } from 'vue';

export interface ToastItem {
  id: string;
  type: 'success' | 'error' | 'warning';
  message: string;
}

const toasts = ref<ToastItem[]>([]);

function toastClasses(type: string) {
  switch (type) {
    case 'success':
      return 'bg-slate-900/95 border-emerald-500/40 text-emerald-300 backdrop-blur-md';
    case 'error':
      return 'bg-slate-900/95 border-rose-500/40 text-rose-300 backdrop-blur-md';
    default:
      return 'bg-slate-900/95 border-amber-500/40 text-amber-300 backdrop-blur-md';
  }
}

function removeToast(id: string) {
  toasts.value = toasts.value.filter((t) => t.id !== id);
}

function addToast(type: 'success' | 'error' | 'warning', message: string, duration = 4000) {
  const id = Math.random().toString(36).substr(2, 9);
  toasts.value.push({ id, type, message });
  setTimeout(() => {
    removeToast(id);
  }, duration);
}

defineExpose({
  addToast,
});
</script>

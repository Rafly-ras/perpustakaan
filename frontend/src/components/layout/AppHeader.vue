<template>
  <header class="sticky top-0 z-30 bg-slate-900/90 backdrop-blur-md border-b border-slate-800 px-4 py-3 flex items-center justify-between">
    <!-- Left: Hamburger Toggle & Title -->
    <div class="flex items-center space-x-3">
      <button
        @click="$emit('toggle-sidebar')"
        class="text-slate-400 hover:text-white p-2 rounded-xl hover:bg-slate-800 transition-colors md:hidden"
      >
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <div class="flex items-center space-x-2">
        <div class="w-8 h-8 rounded-lg bg-primary-600/20 text-primary-400 flex items-center justify-center ring-1 ring-primary-500/30">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <span class="font-bold text-white tracking-tight hidden sm:inline text-base">Library Digital</span>
      </div>
    </div>

    <!-- Right: User Info & Actions -->
    <div class="flex items-center space-x-3">
      <!-- Coin Balance Badge (FR-COIN-01) -->
      <div v-if="authStore.user" class="px-3 py-1.5 rounded-xl bg-amber-950/60 border border-amber-800/40 text-amber-300 text-xs font-semibold flex items-center space-x-1.5 shadow-sm">
        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.778 11.26 4 10 4s-2.279.778-2.979 1.95a1 1 0 001.715 1.029z" />
        </svg>
        <span>{{ authStore.coinBalance }} Koin</span>
      </div>

      <!-- User Dropdown Profile -->
      <div v-if="authStore.user" class="relative">
        <div class="flex items-center space-x-2 pl-2 border-l border-slate-800">
          <div class="w-8 h-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-xs font-bold text-primary-400 uppercase">
            {{ authStore.user.name.charAt(0) }}
          </div>
          <div class="hidden md:block text-left text-xs">
            <div class="font-semibold text-slate-200 leading-tight">{{ authStore.user.name }}</div>
            <div class="text-[10px] text-slate-400 capitalize">{{ authStore.user.role.display_name }}</div>
          </div>
          <button @click="handleLogout" title="Logout" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-slate-800 transition-colors ml-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/features/auth/stores/useAuthStore';
import { useRouter } from 'vue-router';

defineEmits<{
  (e: 'toggle-sidebar'): void;
}>();

const authStore = useAuthStore();
const router = useRouter();

async function handleLogout() {
  await authStore.logout();
  router.push('/login');
}
</script>

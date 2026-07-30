<template>
  <AuthLayout>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-8 space-y-6">
      
      <!-- Brand & Header -->
      <div class="text-center space-y-2">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-primary-600/20 text-primary-400 ring-1 ring-primary-500/30">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-white tracking-tight">Masuk ke Portal Perpustakaan</h2>
        <p class="text-xs text-slate-400">Masukkan Email atau NIM / NIDN beserta Password Anda</p>
      </div>

      <!-- Error Alert -->
      <div v-if="authStore.error" class="p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs flex items-start space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ authStore.error }}</span>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="block text-xs font-medium text-slate-300 mb-1.5">Email / NIM / NIDN</label>
          <input
            v-model="form.login"
            type="text"
            required
            placeholder="misal: 198501012010 atau dosen@library.local"
            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 text-sm transition-all"
          />
        </div>

        <div>
          <label class="block text-xs font-medium text-slate-300 mb-1.5">Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            placeholder="••••••••"
            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 text-sm transition-all"
          />
        </div>

        <button
          type="submit"
          :disabled="authStore.isLoading"
          class="w-full py-3 px-4 rounded-xl bg-primary-600 hover:bg-primary-500 disabled:opacity-50 text-white font-semibold text-sm shadow-lg shadow-primary-600/25 transition-all flex items-center justify-center space-x-2"
        >
          <svg v-if="authStore.isLoading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>{{ authStore.isLoading ? 'Memproses...' : 'Masuk Sekarang' }}</span>
        </button>
      </form>

      <!-- Footer Link -->
      <div class="text-center pt-2 border-t border-slate-800 text-xs text-slate-400">
        Belum memiliki akun Mahasiswa/Dosen?
        <router-link to="/register" class="text-primary-400 hover:underline font-semibold ml-1">
          Daftar Mandiri (Self-Registration)
        </router-link>
      </div>

    </div>
  </AuthLayout>
</template>

<script setup lang="ts">
import { reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/useAuthStore';
import AuthLayout from '@/components/layout/AuthLayout.vue';

const router = useRouter();
const authStore = useAuthStore();

const form = reactive({
  login: '',
  password: '',
});

onMounted(() => {
  authStore.error = null;
});

async function handleLogin() {
  try {
    await authStore.login(form);
    router.push('/dashboard');
  } catch (e) {
    // Error handled in store
  }
}
</script>

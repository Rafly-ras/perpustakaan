<template>
  <AuthLayout>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-8 space-y-6">
      
      <!-- Header -->
      <div class="text-center space-y-2">
        <h2 class="text-2xl font-bold text-white tracking-tight">Pendaftaran Mandiri Anggota</h2>
        <p class="text-xs text-slate-400">Masukkan data diri & NIM/NIDN Anda untuk membuat akun</p>
      </div>

      <!-- Error Alert -->
      <div v-if="authStore.error" class="p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs flex items-start space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ authStore.error }}</span>
      </div>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <!-- Role Selector (Mahasiswa / Dosen) -->
        <div>
          <label class="block text-xs font-medium text-slate-300 mb-1.5">Status Peran Anggota</label>
          <div class="grid grid-cols-2 gap-3">
            <button
              type="button"
              @click="setRole('mahasiswa')"
              :class="[
                'py-2.5 px-4 rounded-xl text-xs font-semibold border transition-all flex items-center justify-center space-x-2',
                form.role === 'mahasiswa'
                  ? 'bg-primary-600/20 border-primary-500 text-primary-300 ring-1 ring-primary-500/50'
                  : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'
              ]"
            >
              <span>🎓 Mahasiswa (5 Koin)</span>
            </button>
            <button
              type="button"
              @click="setRole('dosen')"
              :class="[
                'py-2.5 px-4 rounded-xl text-xs font-semibold border transition-all flex items-center justify-center space-x-2',
                form.role === 'dosen'
                  ? 'bg-primary-600/20 border-primary-500 text-primary-300 ring-1 ring-primary-500/50'
                  : 'bg-slate-950 border-slate-800 text-slate-400 hover:border-slate-700'
              ]"
            >
              <span>👨‍🏫 Dosen (10 Koin)</span>
            </button>
          </div>
        </div>

        <!-- Identity Number (NIM / NIDN) -->
        <div>
          <label class="block text-xs font-medium text-slate-300 mb-1.5">
            {{ form.role === 'mahasiswa' ? 'Nomor Induk Mahasiswa (NIM)' : 'NIDN / NIP Dosen' }}
          </label>
          <input
            v-if="form.role === 'mahasiswa'"
            v-model="form.nim"
            type="text"
            required
            placeholder="misal: 20261001"
            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 text-sm transition-all"
          />
          <input
            v-else
            v-model="form.nidn"
            type="text"
            required
            placeholder="misal: 198501012010"
            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 text-sm transition-all"
          />
        </div>

        <!-- Full Name -->
        <div>
          <label class="block text-xs font-medium text-slate-300 mb-1.5">Nama Lengkap</label>
          <input
            v-model="form.name"
            type="text"
            required
            placeholder="misal: Budi Santoso"
            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 text-sm transition-all"
          />
        </div>

        <!-- Email & WhatsApp Phone -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-medium text-slate-300 mb-1.5">Email Aktif</label>
            <input
              v-model="form.email"
              type="email"
              required
              placeholder="user@mail.com"
              class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 text-sm transition-all"
            />
          </div>
          <div>
            <label class="block text-xs font-medium text-slate-300 mb-1.5">No. WhatsApp (Notifikasi)</label>
            <input
              v-model="form.phone"
              type="text"
              required
              placeholder="08123456789"
              class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 text-sm transition-all"
            />
          </div>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-xs font-medium text-slate-300 mb-1.5">Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            placeholder="Minimal 8 karakter"
            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 text-sm transition-all"
          />
        </div>

        <button
          type="submit"
          :disabled="authStore.isLoading"
          class="w-full py-3 px-4 rounded-xl bg-primary-600 hover:bg-primary-500 disabled:opacity-50 text-white font-semibold text-sm shadow-lg shadow-primary-600/25 transition-all flex items-center justify-center space-x-2 cursor-pointer"
        >
          <svg v-if="authStore.isLoading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>{{ authStore.isLoading ? 'Memverifikasi...' : 'Daftar Sekarang' }}</span>
        </button>
      </form>

      <div class="text-center pt-2 border-t border-slate-800 text-xs text-slate-400">
        Sudah memiliki akun terdaftar?
        <router-link to="/login" class="text-primary-400 hover:underline font-semibold ml-1">
          Login ke Portal
        </router-link>
      </div>

    </div>
  </AuthLayout>
</template>

<script setup lang="ts">
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/useAuthStore';
import AuthLayout from '@/components/layout/AuthLayout.vue';

const router = useRouter();
const authStore = useAuthStore();

const form = reactive<{
  role: 'mahasiswa' | 'dosen';
  name: string;
  email: string;
  phone: string;
  password: string;
  nim: string;
  nidn: string;
}>({
  role: 'mahasiswa',
  name: '',
  email: '',
  phone: '',
  password: '',
  nim: '',
  nidn: '',
});

function setRole(newRole: 'mahasiswa' | 'dosen') {
  form.role = newRole;
  authStore.error = null;
}

async function handleRegister() {
  try {
    const payload = {
      name: form.name,
      email: form.email,
      phone: form.phone,
      password: form.password,
      password_confirmation: form.password,
      role: form.role,
      nim: form.role === 'mahasiswa' ? form.nim : undefined,
      nidn: form.role === 'dosen' ? form.nidn : undefined,
    };

    await authStore.register(payload);
    const redirectPath = authStore.getRedirectPathByRole(authStore.userRole);
    router.push(redirectPath);
  } catch (e) {
    // Handled in store and displayed in authStore.error
  }
}
</script>

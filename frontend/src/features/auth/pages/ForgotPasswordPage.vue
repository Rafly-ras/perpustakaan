<template>
  <AuthLayout>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-8 space-y-6">
      <div class="text-center space-y-2">
        <h2 class="text-xl font-bold text-white tracking-tight">Lupa Password Akun</h2>
        <p class="text-xs text-slate-400">Masukkan email Anda untuk menerima instruksi reset password</p>
      </div>

      <div class="p-4 rounded-xl bg-amber-950/40 border border-amber-800/40 text-amber-300 text-xs leading-relaxed">
        Fitur reset password via email akan mengonfirmasi WhatsApp Gateway atau link token yang dikirim ke email terdaftar Anda.
      </div>

      <form @submit.prevent="handleReset" class="space-y-4">
        <AppInput
          v-model="email"
          label="Email Terdaftar"
          placeholder="contoh@university.ac.id"
          type="email"
          required
        />

        <AppButton type="submit" variant="primary" block :loading="loading">
          Kirim Instruksi Reset
        </AppButton>

        <div class="text-center pt-2">
          <router-link to="/login" class="text-xs text-primary-400 hover:text-primary-300 transition-colors">
            &larr; Kembali ke Halaman Login
          </router-link>
        </div>
      </form>
    </div>
  </AuthLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import AuthLayout from '@/components/layout/AuthLayout.vue';
import AppInput from '@/components/common/AppInput.vue';
import AppButton from '@/components/common/AppButton.vue';

const email = ref('');
const loading = ref(false);

function handleReset() {
  loading.value = true;
  setTimeout(() => {
    alert('Link reset password (placeholder) telah dikirim ke email Anda.');
    loading.value = false;
  }, 1000);
}
</script>

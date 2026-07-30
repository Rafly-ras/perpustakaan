<template>
  <DefaultLayout>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <AppBreadcrumb :items="[{ label: 'Dashboard Mahasiswa' }]" />
        <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">Portal Peminjaman Mahasiswa</h1>
      </div>
      <AppBadge variant="info" size="md">Role Mahasiswa</AppBadge>
    </div>

    <!-- Student Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl">
        <div class="text-xs text-slate-400">Kuota Koin Bulanan</div>
        <div class="text-2xl font-bold text-amber-400 mt-1">{{ authStore.coinBalance }} / 5 Koin</div>
        <div class="text-[10px] text-slate-500 mt-1">1 Koin = 1 Buku (Batas 7 Hari)</div>
      </div>
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl">
        <div class="text-xs text-slate-400">Buku Sedang Dipinjam</div>
        <div class="text-2xl font-bold text-emerald-400 mt-1">1 Buku</div>
      </div>
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl">
        <div class="text-xs text-slate-400">Antrean Reservasi Saya</div>
        <div class="text-2xl font-bold text-sky-400 mt-1">0 Antrean</div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '@/components/layout/DefaultLayout.vue';
import AppBreadcrumb from '@/components/common/AppBreadcrumb.vue';
import AppBadge from '@/components/common/AppBadge.vue';
import { useAuthStore } from '@/features/auth/stores/useAuthStore';

const authStore = useAuthStore();
</script>

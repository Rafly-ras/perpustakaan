<template>
  <DefaultLayout>
    <!-- Page Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <AppBreadcrumb :items="[{ label: 'Dashboard Utama' }]" />
        <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">Dashboard Perpustakaan Digital</h1>
      </div>
      <AppBadge variant="primary" size="md">
        Perpustakaan Pusat Standalone
      </AppBadge>
    </div>

    <!-- Quick Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- Card 1: Saldo Koin -->
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl flex items-center justify-between">
        <div>
          <div class="text-xs font-semibold text-slate-400">Sisa Kuota Koin</div>
          <div class="text-2xl font-black text-amber-400 mt-1">{{ authStore.coinBalance }} Koin</div>
          <div class="text-[10px] text-slate-500 mt-1">Reset massal tgl 1 tiap bulan</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.778 11.26 4 10 4s-2.279.778-2.979 1.95a1 1 0 001.715 1.029z" />
          </svg>
        </div>
      </div>

      <!-- Card 2: Role Akun -->
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl flex items-center justify-between">
        <div>
          <div class="text-xs font-semibold text-slate-400">Role & Peran Akun</div>
          <div class="text-xl font-bold text-white capitalize mt-1">{{ authStore.userRole || 'Anggota' }}</div>
          <div class="text-[10px] text-slate-500 mt-1">Hak akses tersinkronisasi</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-primary-500/10 text-primary-400 border border-primary-500/20 flex items-center justify-center">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </div>
      </div>

      <!-- Card 3: Status Server -->
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl flex items-center justify-between">
        <div>
          <div class="text-xs font-semibold text-slate-400">Environment REST API</div>
          <div class="text-xl font-bold text-emerald-400 mt-1">Laravel 13 API</div>
          <div class="text-[10px] text-slate-500 mt-1">PostgreSQL 17 & Redis 7</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
      </div>

      <!-- Card 4: Front Engine -->
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl flex items-center justify-between">
        <div>
          <div class="text-xs font-semibold text-slate-400">Frontend Engine</div>
          <div class="text-xl font-bold text-sky-400 mt-1">Vue 3 + TS</div>
          <div class="text-[10px] text-slate-500 mt-1">Vite + Pinia + Tailwind</div>
        </div>
        <div class="w-12 h-12 rounded-xl bg-sky-500/10 text-sky-400 border border-sky-500/20 flex items-center justify-center">
          <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Welcome Overview Banner -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-slate-900 via-slate-900 to-primary-950/40 border border-slate-800 shadow-xl space-y-3">
      <h3 class="text-lg font-bold text-white tracking-tight">Selamat Datang di Sistem Sirkulasi Perpustakaan Digital!</h3>
      <p class="text-xs text-slate-300 max-w-3xl leading-relaxed">
        Sistem ini mengelola sirkulasi buku digital dengan kuota koin bulanan, antrean FIFO reservasi otomatis saat stok habis, pengingat jatuh tempo via WhatsApp Gateway, dan form pencarian fast-typing auto-complete tanpa hardware scanner.
      </p>
      <div class="pt-2 flex flex-wrap gap-2">
        <AppBadge variant="info">Mobile-First Responsive</AppBadge>
        <AppBadge variant="success">Dockerized Environment</AppBadge>
        <AppBadge variant="warning">Monthly Coin Quota System</AppBadge>
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

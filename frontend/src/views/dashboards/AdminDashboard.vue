<template>
  <DefaultLayout>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <AppBreadcrumb :items="[{ label: 'Dashboard Admin Operator' }]" />
        <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">Meja Sirkulasi & Deskside Manual</h1>
      </div>
      <AppBadge variant="primary" size="md">Operator Sirkulasi Desk</AppBadge>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl">
        <div class="text-xs text-slate-400">Peminjaman Hari Ini</div>
        <div class="text-2xl font-bold text-emerald-400 mt-1">28 Transaksi</div>
      </div>
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl">
        <div class="text-xs text-slate-400">Pengembalian Hari Ini</div>
        <div class="text-2xl font-bold text-sky-400 mt-1">32 Transaksi</div>
      </div>
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl">
        <div class="text-xs text-slate-400">Reservasi Siap Ambil</div>
        <div class="text-2xl font-bold text-amber-400 mt-1">5 Buku</div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '@/components/layout/DefaultLayout.vue';
import AppBreadcrumb from '@/components/common/AppBreadcrumb.vue';
import AppBadge from '@/components/common/AppBadge.vue';
</script>

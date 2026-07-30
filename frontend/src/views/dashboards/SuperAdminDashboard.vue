<template>
  <DefaultLayout>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <AppBreadcrumb :items="[{ label: 'Dashboard Super Admin' }]" />
        <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">Super Admin Control Center</h1>
      </div>
      <AppBadge variant="danger" size="md">Hak Akses Super Admin Penuh</AppBadge>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl">
        <div class="text-xs text-slate-400">Total Pengguna Terdaftar</div>
        <div class="text-2xl font-bold text-white mt-1">1,248 Akun</div>
        <div class="text-[10px] text-slate-500 mt-1">Super Admin, Admin, Mahasiswa, Dosen</div>
      </div>
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl">
        <div class="text-xs text-slate-400">Koleksi Judul Buku</div>
        <div class="text-2xl font-bold text-primary-400 mt-1">4,850 Judul</div>
        <div class="text-[10px] text-slate-500 mt-1">Status aktif OPAC</div>
      </div>
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl">
        <div class="text-xs text-slate-400">Sirkulasi Aktif</div>
        <div class="text-2xl font-bold text-emerald-400 mt-1">142 Transaksi</div>
        <div class="text-[10px] text-slate-500 mt-1">Sedang dipinjam</div>
      </div>
      <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl">
        <div class="text-xs text-slate-400">Antrean Reservasi FIFO</div>
        <div class="text-2xl font-bold text-amber-400 mt-1">18 Antrean</div>
        <div class="text-[10px] text-slate-500 mt-1">Menunggu pengembalian</div>
      </div>
    </div>

    <!-- Overview Banner -->
    <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl space-y-2">
      <h3 class="text-base font-bold text-white">Manajemen Sistem Perpustakaan Pusat</h3>
      <p class="text-xs text-slate-400 leading-relaxed">
        Sebagai Super Admin, Anda memiliki hak kelola penuh terhadap master data user (import NIM/NIDN CSV), katalog buku, barcode eksemplar, transaksi peminjaman desk, antrean FIFO reservasi, dan konfigurasi scheduler koin bulanan.
      </p>
    </div>
  </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '@/components/layout/DefaultLayout.vue';
import AppBreadcrumb from '@/components/common/AppBreadcrumb.vue';
import AppBadge from '@/components/common/AppBadge.vue';
</script>

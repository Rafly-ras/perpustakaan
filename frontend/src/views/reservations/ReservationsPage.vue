<template>
  <DefaultLayout>
    <!-- Page Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <AppBreadcrumb :items="[{ label: 'Antrean Reservasi FIFO' }]" />
        <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">Antrean Reservasi FIFO (First-In, First-Out)</h1>
        <p class="text-xs text-slate-400 mt-1">Sistem antrean otomatis saat stok eksemplar buku di katalog OPAC sedang kosong</p>
      </div>
      <AppButton variant="primary" size="sm" disabled>
        <template #icon-left>
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </template>
        Coming Soon (STEP 8: Dynamic FIFO Engine)
      </AppButton>
    </div>

    <!-- Info Banner -->
    <div class="p-4 rounded-2xl bg-amber-950/40 border border-amber-800/40 text-amber-300 text-xs flex items-start gap-3 shadow-lg">
      <svg class="w-5 h-5 shrink-0 mt-0.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <div class="space-y-1">
        <div class="font-bold">Prinsip Kerja Reservasi FIFO (First-In, First-Out):</div>
        <p class="text-slate-300 leading-relaxed">
          Anggota pertama yang mengantre akan mendapatkan prioritas utama saat buku dikembalikan ke meja sirkulasi. Notifikasi WhatsApp Gateway otomatis terikirim saat buku siap diambil di meja sirkulasi.
        </p>
      </div>
    </div>

    <!-- Table Section -->
    <AppTable :loading="false" :isEmpty="mockReservations.length === 0" :colSpan="6">
      <template #header>
        <th class="px-4 py-3">No. Antrean FIFO</th>
        <th class="px-4 py-3">Judul Buku & ISBN</th>
        <th class="px-4 py-3">Pemohon</th>
        <th class="px-4 py-3">Waktu Reservasi</th>
        <th class="px-4 py-3">Status Antrean</th>
        <th class="px-4 py-3 text-right">Aksi</th>
      </template>

      <tr v-for="res in mockReservations" :key="res.id" class="hover:bg-slate-850/50 transition-colors">
        <td class="px-4 py-3 font-mono font-bold text-lg text-primary-400">#{{ res.queue_number }}</td>
        <td class="px-4 py-3">
          <div class="font-bold text-white leading-snug">{{ res.book_title }}</div>
          <div class="text-[11px] text-slate-500 font-mono">ISBN: {{ res.book_isbn }}</div>
        </td>
        <td class="px-4 py-3">
          <div class="font-semibold text-slate-200">{{ res.user_name }}</div>
          <div class="text-xs text-slate-400">{{ res.user_role }} &bull; {{ res.user_nim_nidn }}</div>
        </td>
        <td class="px-4 py-3 text-xs text-slate-400 font-mono">
          {{ res.reserved_at }}
        </td>
        <td class="px-4 py-3">
          <AppBadge :variant="res.status === 'ready_for_pickup' ? 'success' : 'warning'" size="sm">
            {{ res.status === 'ready_for_pickup' ? 'Siap Diambil di Desk' : 'Dalam Antrean FIFO' }}
          </AppBadge>
        </td>
        <td class="px-4 py-3 text-right">
          <AppButton variant="ghost" size="sm" disabled>Detail</AppButton>
        </td>
      </tr>
    </AppTable>
  </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '@/components/layout/DefaultLayout.vue';
import AppBreadcrumb from '@/components/common/AppBreadcrumb.vue';
import AppTable from '@/components/common/AppTable.vue';
import AppButton from '@/components/common/AppButton.vue';
import AppBadge from '@/components/common/AppBadge.vue';
import { mockReservations } from '@/mocks/reservationsMock';
</script>

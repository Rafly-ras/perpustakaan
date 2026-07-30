<template>
  <DefaultLayout>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <AppBreadcrumb :items="[{ label: 'Meja Sirkulasi Desk' }]" />
        <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">Sirkulasi Meja Desk & Manual Auto-Complete</h1>
        <p class="text-xs text-slate-400 mt-1">Pemrosesan transaksi peminjaman & pengembalian oleh Admin dengan pencarian fast-typing tanpa scanner</p>
      </div>
      <AppBadge variant="primary" size="md">Admin Desk Operator</AppBadge>
    </div>

    <!-- Deskside Transaction Form Simulation -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Form Input -->
      <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl space-y-4">
        <h3 class="text-base font-bold text-white flex items-center gap-2">
          <svg class="w-5 h-5 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
          </svg>
          Form Transaksi Peminjaman / Pengembalian Desk
        </h3>

        <div class="space-y-3">
          <AppInput
            v-model="nimQuery"
            label="1. Cari Anggota (NIM / NIDN / Nama)"
            placeholder="Ketik NIM / NIDN..."
          />
          
          <AppInput
            v-model="bookQuery"
            label="2. Cari Buku (ISBN / Kode Eksemplar / Judul)"
            placeholder="Ketik Kode Eksemplar (misal: LIB-2026-X891)..."
          />

          <div class="pt-2">
            <AppButton variant="primary" block disabled>
              Coming Soon (STEP 7: Transaksi Peminjaman & LockForUpdate)
            </AppButton>
          </div>
        </div>
      </div>

      <!-- Information & Rules Card -->
      <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl space-y-3">
        <h3 class="text-base font-bold text-white">Aturan Sirkulasi & Kuota Koin</h3>
        <ul class="text-xs text-slate-300 space-y-2 list-disc list-inside leading-relaxed">
          <li><strong>Batas Peminjaman:</strong> 1 Koin = 1 Eksemplar Buku.</li>
          <li><strong>Kuota Bulanan:</strong> Mahasiswa (5 Koin), Dosen (10 Koin).</li>
          <li><strong>Durasi Peminjaman:</strong> Maksimal 7 Hari Kalender.</li>
          <li><strong>Denda Financial:</strong> <span class="text-emerald-400 font-bold">DIHAPUS (Rp 0)</span> sesuai aturan FRD.</li>
          <li><strong>Pengingat WhatsApp:</strong> Notifikasi otomatis dikirim pada H-3, H-1, dan Hari-H jatuh tempo.</li>
        </ul>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import DefaultLayout from '@/components/layout/DefaultLayout.vue';
import AppBreadcrumb from '@/components/common/AppBreadcrumb.vue';
import AppInput from '@/components/common/AppInput.vue';
import AppButton from '@/components/common/AppButton.vue';
import AppBadge from '@/components/common/AppBadge.vue';

const nimQuery = ref('');
const bookQuery = ref('');
</script>

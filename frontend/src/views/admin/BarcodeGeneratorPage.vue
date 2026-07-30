<template>
  <DefaultLayout>
    <ToastNotification ref="toastRef" />

    <!-- Printable Header & Actions (Hidden during browser print) -->
    <div class="print:hidden flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <AppBreadcrumb :items="[{ label: 'Master Data' }, { label: 'Cetak Barcode' }]" />
        <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">Generator & Cetak Stiker Label Barcode</h1>
        <p class="text-xs text-slate-400 mt-1">Layout grid stiker label (Format LIB-2026-XXXX) siap dicetak langsung ke printer stiker / kertas A4</p>
      </div>
      <AppButton variant="primary" size="sm" @click="handlePrint">
        <template #icon-left>
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
          </svg>
        </template>
        Cetak Stiker Barcode (PDF / Print)
      </AppButton>
    </div>

    <!-- Sticker Grid Preview Area -->
    <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl space-y-4 print:bg-white print:p-0 print:border-0 print:shadow-none">
      <div class="print:hidden flex items-center justify-between">
        <h3 class="text-sm font-bold text-white">Preview Layout Grid Stiker Label Barcode Eksemplar Pustaka</h3>
        <AppBadge variant="info">Format Kode: LIB-2026-XXXX</AppBadge>
      </div>

      <!-- Sticker Cards Grid (Print CSS Optimized) -->
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 print:grid-cols-4 print:gap-2">
        <div
          v-for="item in allCopies"
          :key="item.barcode"
          class="p-4 rounded-xl bg-slate-950 border border-slate-800 flex flex-col items-center justify-center text-center space-y-2 font-mono print:bg-white print:border-slate-300 print:text-black print:p-2"
        >
          <div class="text-[9px] text-slate-500 font-sans font-bold uppercase tracking-wider print:text-slate-700">
            PERPUSTAKAAN PUSAT
          </div>

          <!-- Barcode SVG Simulation -->
          <div class="w-full h-9 bg-slate-900 rounded flex items-center justify-center space-x-1 px-2 print:bg-white">
            <div class="w-1 h-7 bg-slate-200 print:bg-black"></div>
            <div class="w-2 h-7 bg-slate-200 print:bg-black"></div>
            <div class="w-0.5 h-7 bg-slate-200 print:bg-black"></div>
            <div class="w-1.5 h-7 bg-slate-200 print:bg-black"></div>
            <div class="w-1 h-7 bg-slate-200 print:bg-black"></div>
            <div class="w-2 h-7 bg-slate-200 print:bg-black"></div>
            <div class="w-0.5 h-7 bg-slate-200 print:bg-black"></div>
            <div class="w-1.5 h-7 bg-slate-200 print:bg-black"></div>
            <div class="w-1 h-7 bg-slate-200 print:bg-black"></div>
          </div>

          <div class="text-xs font-bold text-primary-400 print:text-black">{{ item.barcode }}</div>
          <div class="text-[9px] text-slate-400 font-sans truncate w-full print:text-slate-600">
            {{ item.book_title }}
          </div>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/services/api';
import DefaultLayout from '@/components/layout/DefaultLayout.vue';
import AppBreadcrumb from '@/components/common/AppBreadcrumb.vue';
import AppButton from '@/components/common/AppButton.vue';
import AppBadge from '@/components/common/AppBadge.vue';
import ToastNotification from '@/components/common/ToastNotification.vue';

const toastRef = ref();
const allCopies = ref<any[]>([]);

async function fetchCopies() {
  try {
    const res = await api.get('/books');
    const books = res.data.data;
    const copies: any[] = [];
    books.forEach((b: any) => {
      if (b.copies && Array.isArray(b.copies)) {
        b.copies.forEach((c: any) => {
          copies.push({
            barcode: c.barcode,
            book_title: b.title,
          });
        });
      }
    });
    allCopies.value = copies;
  } catch (err) {
    toastRef.value?.addToast('error', 'Gagal memuat daftar barcode');
  }
}

function handlePrint() {
  window.print();
}

onMounted(() => {
  fetchCopies();
});
</script>

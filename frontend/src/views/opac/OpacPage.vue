<template>
  <DefaultLayout>
    <ToastNotification ref="toastRef" />

    <!-- Page Header & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <AppBreadcrumb :items="[{ label: 'Katalog OPAC' }]" />
        <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">Katalog Publik OPAC (Online Public Access Catalog)</h1>
        <p class="text-xs text-slate-400 mt-1">Cari dan jelajahi koleksi pustaka fisik perpustakaan pusat secara real-time</p>
      </div>
      <AppBadge variant="info" size="md">
        Total {{ pagination.total }} Judul Terdaftar
      </AppBadge>
    </div>

    <!-- Search & Filter Controls -->
    <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="w-full md:w-96">
        <AppInput
          v-model="searchQuery"
          placeholder="Cari Judul Buku, Pengarang, ISBN..."
          @input="debounceFetch"
        >
          <template #prefix-icon>
            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </template>
        </AppInput>
      </div>

      <div class="flex items-center gap-3 w-full md:w-auto">
        <select
          v-model="selectedCategory"
          @change="fetchData(1)"
          class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-slate-300 focus:outline-none focus:border-primary-500 w-full sm:w-auto"
        >
          <option value="">Semua Kategori</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">
            {{ cat.name }}
          </option>
        </select>
      </div>
    </div>

    <!-- Loading Skeleton -->
    <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <LoadingSkeleton v-for="n in 8" :key="n" type="card" height="300px" />
    </div>

    <!-- Book Grid Cards -->
    <div v-else-if="books.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <div
        v-for="book in books"
        :key="book.id"
        class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl hover:border-slate-700 transition-all flex flex-col group"
      >
        <!-- Cover Image Container -->
        <div class="relative h-48 bg-slate-950 overflow-hidden flex items-center justify-center">
          <img
            :src="book.cover_image_url || 'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&w=400&q=80'"
            :alt="book.title"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
          />
          <div class="absolute top-3 right-3">
            <AppBadge :variant="book.available_copies > 0 ? 'success' : 'warning'" size="sm">
              {{ book.available_copies > 0 ? `Stok: ${book.available_copies}` : 'Habis (Antrean FIFO)' }}
            </AppBadge>
          </div>
        </div>

        <!-- Book Details -->
        <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
          <div class="space-y-1">
            <div class="text-[10px] font-bold text-primary-400 uppercase tracking-wider">{{ book.category_name }}</div>
            <h3 class="text-sm font-bold text-white line-clamp-2 leading-snug group-hover:text-primary-300 transition-colors">
              {{ book.title }}
            </h3>
            <p class="text-xs text-slate-400 line-clamp-1">
              {{ book.authors.length > 0 ? book.authors.join(', ') : 'Pengarang Tidak Diketahui' }}
            </p>
          </div>

          <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-[11px] text-slate-500">
            <span>ISBN: {{ book.isbn }}</span>
            <span>{{ book.publication_year }}</span>
          </div>

          <div class="pt-1">
            <AppButton
              variant="outline"
              size="sm"
              block
              @click="openBookModal(book)"
            >
              Lihat Detail & Eksemplar
            </AppButton>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="py-12 bg-slate-900 border border-slate-800 rounded-2xl shadow-xl">
      <EmptyState
        title="Buku Tidak Ditemukan"
        description="Tidak ada koleksi buku yang cocok dengan kata kunci pencarian Anda."
      >
        <template #action>
          <AppButton variant="secondary" size="sm" @click="searchQuery = ''; selectedCategory = ''; fetchData(1)">
            Reset Pencarian
          </AppButton>
        </template>
      </EmptyState>
    </div>

    <!-- Pagination -->
    <AppPagination
      v-if="pagination.total > 0"
      :currentPage="pagination.current_page"
      :lastPage="pagination.last_page"
      :perPage="pagination.per_page"
      :total="pagination.total"
      @page-change="fetchData"
    />

    <!-- Book Detail Modal -->
    <AppModal :show="showModal" :title="selectedBook?.title || 'Detail Buku'" @close="showModal = false">
      <div v-if="selectedBook" class="space-y-4">
        <div class="flex flex-col sm:flex-row gap-4">
          <img
            :src="selectedBook.cover_image_url || 'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&w=400&q=80'"
            class="w-32 h-44 object-cover rounded-xl border border-slate-800 shadow-md shrink-0 mx-auto sm:mx-0"
          />
          <div class="space-y-2 text-xs text-slate-300 flex-1">
            <div><span class="text-slate-500">Pengarang:</span> <strong class="text-white">{{ selectedBook.authors.join(', ') || '-' }}</strong></div>
            <div><span class="text-slate-500">Penerbit:</span> {{ selectedBook.publisher_name }} ({{ selectedBook.publication_year }})</div>
            <div><span class="text-slate-500">ISBN:</span> {{ selectedBook.isbn }}</div>
            <div><span class="text-slate-500">Kategori:</span> {{ selectedBook.category_name }}</div>
            <div class="pt-2 flex items-center gap-2">
              <AppBadge :variant="selectedBook.available_copies > 0 ? 'success' : 'warning'" size="md">
                Stok Tersedia: {{ selectedBook.available_copies }} dari {{ selectedBook.total_copies }} Eksemplar
              </AppBadge>
            </div>
          </div>
        </div>

        <div v-if="selectedBook.description" class="p-3 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-300">
          <div class="font-bold text-white mb-1">Sinopsis / Ringkasan:</div>
          <p class="leading-relaxed">{{ selectedBook.description }}</p>
        </div>

        <!-- Eksemplar Barcodes -->
        <div class="p-3 rounded-xl bg-slate-950 border border-slate-800 text-xs space-y-2">
          <div class="font-bold text-white">Stiker Kode Barcode Eksemplar Fisik:</div>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="copy in selectedBook.copies"
              :key="copy.id"
              class="px-2 py-1 rounded bg-slate-900 border border-slate-800 font-mono text-[11px]"
              :class="copy.status === 'available' ? 'text-emerald-400 border-emerald-800/40' : 'text-amber-400 border-amber-800/40'"
            >
              {{ copy.barcode }} ({{ copy.status === 'available' ? 'Tersedia' : 'Dipinjam' }})
            </span>
          </div>
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t border-slate-800">
          <AppButton variant="ghost" size="sm" @click="showModal = false">Tutup</AppButton>
        </div>
      </div>
    </AppModal>
  </DefaultLayout>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import api from '@/services/api';
import DefaultLayout from '@/components/layout/DefaultLayout.vue';
import AppBreadcrumb from '@/components/common/AppBreadcrumb.vue';
import AppInput from '@/components/common/AppInput.vue';
import AppButton from '@/components/common/AppButton.vue';
import AppBadge from '@/components/common/AppBadge.vue';
import AppModal from '@/components/common/AppModal.vue';
import AppPagination from '@/components/common/AppPagination.vue';
import LoadingSkeleton from '@/components/common/LoadingSkeleton.vue';
import EmptyState from '@/components/common/EmptyState.vue';
import ToastNotification from '@/components/common/ToastNotification.vue';

const toastRef = ref();
const loading = ref(false);

const books = ref<any[]>([]);
const categories = ref<any[]>([]);
const searchQuery = ref('');
const selectedCategory = ref('');

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  per_page: 12,
  total: 0,
});

const showModal = ref(false);
const selectedBook = ref<any>(null);

let debounceTimer: any = null;
function debounceFetch() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => fetchData(1), 300);
}

async function fetchCategories() {
  try {
    const response = await api.get('/categories');
    categories.value = response.data.data;
  } catch (err) {
    //
  }
}

async function fetchData(page = 1) {
  loading.value = true;
  try {
    const response = await api.get('/books', {
      params: {
        page,
        search: searchQuery.value,
        category_id: selectedCategory.value,
      },
    });
    books.value = response.data.data;
    Object.assign(pagination, response.data.meta);
  } catch (err) {
    toastRef.value?.addToast('error', 'Gagal memuat katalog pustaka');
  } finally {
    loading.value = false;
  }
}

function openBookModal(book: any) {
  selectedBook.value = book;
  showModal.value = true;
}

onMounted(() => {
  fetchCategories();
  fetchData(1);
});
</script>

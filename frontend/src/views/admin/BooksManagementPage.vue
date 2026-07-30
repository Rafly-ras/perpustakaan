<template>
  <DefaultLayout>
    <ToastNotification ref="toastRef" />

    <!-- Page Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <AppBreadcrumb :items="[{ label: 'Master Data' }, { label: 'Kelola Buku' }]" />
        <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">Kelola Katalog Buku & Eksemplar</h1>
        <p class="text-xs text-slate-400 mt-1">Manajemen judul buku, upload gambar cover, kategori, pengarang, dan penerbit</p>
      </div>
      <AppButton variant="primary" size="sm" @click="openCreateModal">
        <template #icon-left>
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </template>
        Tambah Buku Baru
      </AppButton>
    </div>

    <!-- Filter & Search Bar -->
    <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="w-full md:w-72">
        <AppInput
          v-model="filters.search"
          placeholder="Cari Judul, ISBN, Pengarang..."
          @input="debounceFetch"
        >
          <template #prefix-icon>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </template>
        </AppInput>
      </div>

      <div class="flex items-center gap-3 w-full md:w-auto">
        <select
          v-model="filters.category_id"
          @change="fetchData(1)"
          class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-300 focus:outline-none focus:border-primary-500"
        >
          <option value="">Semua Kategori</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
        </select>
      </div>
    </div>

    <!-- Books Data Table -->
    <AppTable :loading="loading" :isEmpty="books.length === 0" :colSpan="6">
      <template #header>
        <th class="px-4 py-3">Cover & Judul</th>
        <th class="px-4 py-3">Pengarang & Penerbit</th>
        <th class="px-4 py-3">Kategori</th>
        <th class="px-4 py-3">ISBN</th>
        <th class="px-4 py-3">Stok Eksemplar</th>
        <th class="px-4 py-3 text-right">Aksi</th>
      </template>

      <tr v-for="book in books" :key="book.id" class="hover:bg-slate-850/50 transition-colors">
        <td class="px-4 py-3 flex items-center space-x-3">
          <img
            :src="book.cover_image_url || 'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&w=400&q=80'"
            class="w-10 h-14 object-cover rounded-lg border border-slate-800 shrink-0"
          />
          <div>
            <div class="font-bold text-white leading-snug">{{ book.title }}</div>
            <div class="text-[11px] text-slate-500">Tahun: {{ book.publication_year }}</div>
          </div>
        </td>
        <td class="px-4 py-3 text-xs text-slate-300">
          <div>{{ book.authors.join(', ') || '-' }}</div>
          <div class="text-[11px] text-slate-500">{{ book.publisher_name }}</div>
        </td>
        <td class="px-4 py-3">
          <AppBadge variant="info" size="sm">{{ book.category_name }}</AppBadge>
        </td>
        <td class="px-4 py-3 font-mono text-xs text-slate-400">{{ book.isbn }}</td>
        <td class="px-4 py-3 font-bold text-emerald-400">
          {{ book.available_copies }} / {{ book.total_copies }} Eksemplar
        </td>
        <td class="px-4 py-3 text-right space-x-2">
          <button @click="openCopiesDrawer(book)" class="text-xs text-amber-400 hover:underline">Stiker Barcode</button>
          <button @click="openEditModal(book)" class="text-xs text-primary-400 hover:underline">Edit</button>
          <button @click="confirmDelete(book)" class="text-xs text-rose-400 hover:underline">Hapus</button>
        </td>
      </tr>
    </AppTable>

    <!-- Pagination -->
    <AppPagination
      v-if="pagination.total > 0"
      :currentPage="pagination.current_page"
      :lastPage="pagination.last_page"
      :perPage="pagination.per_page"
      :total="pagination.total"
      @page-change="fetchData"
    />

    <!-- Create / Edit Book Modal -->
    <AppModal :show="showModal" :title="isEdit ? 'Edit Data Buku' : 'Tambah Katalog Buku Baru'" @close="showModal = false">
      <form @submit.prevent="handleSubmit" class="space-y-4">
        <AppInput v-model="form.title" label="Judul Buku" required placeholder="misal: Clean Architecture" />
        
        <div class="grid grid-cols-2 gap-3">
          <AppInput v-model="form.isbn" label="Kode ISBN" required placeholder="978-602-..." />
          <AppInput v-model.number="form.publication_year" label="Tahun Terbit" type="number" required placeholder="2026" />
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-medium text-slate-300 mb-1.5">Kategori Pustaka</label>
            <select v-model="form.category_id" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm">
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>
          <AppInput v-model="form.author_input" label="Nama Pengarang" placeholder="Robert C. Martin" />
        </div>

        <div v-if="!isEdit" class="grid grid-cols-2 gap-3">
          <AppInput v-model.number="form.copy_count" label="Jumlah Eksemplar Awal" type="number" min="1" max="50" placeholder="1" />
        </div>

        <div>
          <label class="block text-xs font-medium text-slate-300 mb-1.5">Deskripsi / Sinopsis</label>
          <textarea v-model="form.description" rows="3" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm focus:outline-none focus:border-primary-500" placeholder="Ringkasan isi buku..."></textarea>
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t border-slate-800">
          <AppButton variant="ghost" size="sm" @click="showModal = false">Batal</AppButton>
          <AppButton type="submit" variant="primary" size="sm" :loading="submitLoading">Simpan Buku</AppButton>
        </div>
      </form>
    </AppModal>

    <!-- Copies Drawer Modal -->
    <AppModal :show="showCopiesModal" title="Stiker Barcode Eksemplar Buku" @close="showCopiesModal = false">
      <div v-if="selectedBook" class="space-y-4">
        <div class="flex items-center justify-between">
          <div class="text-xs font-bold text-white">{{ selectedBook.title }}</div>
          <AppButton variant="outline" size="sm" @click="addCopies(selectedBook)">+ Tambah 1 Eksemplar</AppButton>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-60 overflow-y-auto">
          <div v-for="copy in selectedBook.copies" :key="copy.id" class="p-3 rounded-xl bg-slate-950 border border-slate-800 text-center font-mono space-y-1">
            <div class="text-[10px] text-slate-500 font-sans">Barcode ID</div>
            <div class="text-xs font-bold text-primary-400">{{ copy.barcode }}</div>
            <AppBadge :variant="copy.status === 'available' ? 'success' : 'warning'" size="sm">
              {{ copy.status }}
            </AppBadge>
          </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-800">
          <AppButton variant="ghost" size="sm" @click="showCopiesModal = false">Tutup</AppButton>
        </div>
      </div>
    </AppModal>

    <!-- Delete Confirm -->
    <ConfirmDialog
      :show="showDeleteModal"
      title="Hapus Buku"
      :message="`Apakah Anda yakin ingin menghapus buku '${selectedBook?.title}' beserta seluruh eksemplar barcodenya?`"
      :loading="deleteLoading"
      @confirm="executeDelete"
      @cancel="showDeleteModal = false"
    />
  </DefaultLayout>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import api from '@/services/api';
import DefaultLayout from '@/components/layout/DefaultLayout.vue';
import AppBreadcrumb from '@/components/common/AppBreadcrumb.vue';
import AppTable from '@/components/common/AppTable.vue';
import AppPagination from '@/components/common/AppPagination.vue';
import AppButton from '@/components/common/AppButton.vue';
import AppInput from '@/components/common/AppInput.vue';
import AppBadge from '@/components/common/AppBadge.vue';
import AppModal from '@/components/common/AppModal.vue';
import ConfirmDialog from '@/components/common/ConfirmDialog.vue';
import ToastNotification from '@/components/common/ToastNotification.vue';

const toastRef = ref();
const loading = ref(false);
const submitLoading = ref(false);
const deleteLoading = ref(false);

const books = ref<any[]>([]);
const categories = ref<any[]>([]);

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0,
});

const filters = reactive({
  search: '',
  category_id: '',
});

const showModal = ref(false);
const isEdit = ref(false);
const editId = ref<number | null>(null);

const form = reactive({
  title: '',
  isbn: '',
  publication_year: 2026,
  category_id: 1,
  author_input: '',
  description: '',
  copy_count: 1,
});

const showCopiesModal = ref(false);
const showDeleteModal = ref(false);
const selectedBook = ref<any>(null);

let debounceTimer: any = null;
function debounceFetch() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => fetchData(1), 300);
}

async function fetchCategories() {
  try {
    const res = await api.get('/categories');
    categories.value = res.data.data;
    if (categories.value.length > 0) {
      form.category_id = categories.value[0].id;
    }
  } catch (err) {}
}

async function fetchData(page = 1) {
  loading.value = true;
  try {
    const response = await api.get('/books', {
      params: {
        page,
        search: filters.search,
        category_id: filters.category_id,
        per_page: 10,
      },
    });
    books.value = response.data.data;
    Object.assign(pagination, response.data.meta);
  } catch (err) {
    toastRef.value?.addToast('error', 'Gagal memuat data buku');
  } finally {
    loading.value = false;
  }
}

function openCreateModal() {
  isEdit.value = false;
  editId.value = null;
  Object.assign(form, {
    title: '',
    isbn: '',
    publication_year: 2026,
    category_id: categories.value[0]?.id || 1,
    author_input: '',
    description: '',
    copy_count: 1,
  });
  showModal.value = true;
}

function openEditModal(book: any) {
  isEdit.value = true;
  editId.value = book.id;
  Object.assign(form, {
    title: book.title,
    isbn: book.isbn,
    publication_year: book.publication_year,
    category_id: book.category_id,
    author_input: book.authors.join(', '),
    description: book.description || '',
    copy_count: 1,
  });
  showModal.value = true;
}

async function handleSubmit() {
  submitLoading.value = true;
  try {
    const payload = {
      title: form.title,
      isbn: form.isbn,
      publication_year: form.publication_year,
      category_id: form.category_id,
      description: form.description,
      author_names: form.author_input ? [form.author_input] : [],
      copy_count: form.copy_count,
    };

    if (isEdit.value && editId.value) {
      await api.put(`/books/${editId.value}`, payload);
      toastRef.value?.addToast('success', 'Data buku berhasil diperbarui');
    } else {
      await api.post('/books', payload);
      toastRef.value?.addToast('success', 'Buku baru & barcode eksemplar berhasil ditambahkan');
    }
    showModal.value = false;
    fetchData(pagination.current_page);
  } catch (err: any) {
    const msg = err.response?.data?.message || 'Gagal menyimpan buku';
    toastRef.value?.addToast('error', msg);
  } finally {
    submitLoading.value = false;
  }
}

function openCopiesDrawer(book: any) {
  selectedBook.value = book;
  showCopiesModal.value = true;
}

async function addCopies(book: any) {
  try {
    const response = await api.post(`/books/${book.id}/copies`, { count: 1 });
    selectedBook.value = response.data.data;
    toastRef.value?.addToast('success', 'Berhasil menambah 1 stiker barcode eksemplar');
    fetchData(pagination.current_page);
  } catch (err) {
    toastRef.value?.addToast('error', 'Gagal menambah eksemplar');
  }
}

function confirmDelete(book: any) {
  selectedBook.value = book;
  showDeleteModal.value = true;
}

async function executeDelete() {
  if (!selectedBook.value) return;
  deleteLoading.value = true;
  try {
    await api.delete(`/books/${selectedBook.value.id}`);
    toastRef.value?.addToast('success', 'Buku berhasil dihapus');
    showDeleteModal.value = false;
    fetchData(pagination.current_page);
  } catch (err) {
    toastRef.value?.addToast('error', 'Gagal menghapus buku');
  } finally {
    deleteLoading.value = false;
  }
}

onMounted(() => {
  fetchCategories();
  fetchData(1);
});
</script>

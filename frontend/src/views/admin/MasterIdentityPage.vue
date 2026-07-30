<template>
  <DefaultLayout>
    <!-- Toast Notifications -->
    <ToastNotification ref="toastRef" />

    <!-- Page Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <AppBreadcrumb :items="[{ label: 'Master Data' }, { label: 'Master Identity' }]" />
        <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">Master Data NIM & NIDN</h1>
      </div>
      <div class="flex flex-wrap items-center gap-2">
        <AppButton variant="outline" size="sm" @click="showImportModal = true">
          <template #icon-left>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
          </template>
          Import Excel / CSV
        </AppButton>
        <AppButton variant="outline" size="sm" @click="handleExport">
          <template #icon-left>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
          </template>
          Export CSV
        </AppButton>
        <AppButton variant="primary" size="sm" @click="openCreateModal">
          <template #icon-left>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </template>
          Tambah Master
        </AppButton>
      </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="w-full md:w-72">
        <AppInput
          v-model="filters.search"
          placeholder="Cari NIM/NIDN, Nama, Email..."
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
        <!-- Role Filter -->
        <select
          v-model="filters.role_type"
          @change="fetchData(1)"
          class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-300 focus:outline-none focus:border-primary-500"
        >
          <option value="">Semua Peran</option>
          <option value="mahasiswa">Mahasiswa</option>
          <option value="dosen">Dosen</option>
        </select>

        <!-- Status Filter -->
        <select
          v-model="filters.status"
          @change="fetchData(1)"
          class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-300 focus:outline-none focus:border-primary-500"
        >
          <option value="">Semua Status</option>
          <option value="active">Aktif</option>
          <option value="inactive">Tidak Aktif</option>
        </select>
      </div>
    </div>

    <!-- Table Section -->
    <AppTable :loading="loading" :isEmpty="items.length === 0" :colSpan="7">
      <template #header>
        <th class="px-4 py-3">NIM / NIDN</th>
        <th class="px-4 py-3">Nama Lengkap</th>
        <th class="px-4 py-3">Email & Telp</th>
        <th class="px-4 py-3">Peran</th>
        <th class="px-4 py-3">Status Terdaftar</th>
        <th class="px-4 py-3">Status Master</th>
        <th class="px-4 py-3 text-right">Aksi</th>
      </template>

      <tr v-for="item in items" :key="item.id" class="hover:bg-slate-850/50 transition-colors">
        <td class="px-4 py-3 font-mono font-bold text-primary-400">{{ item.identity_number }}</td>
        <td class="px-4 py-3 font-semibold text-white">{{ item.full_name }}</td>
        <td class="px-4 py-3 text-xs text-slate-400">
          <div>{{ item.email || '-' }}</div>
          <div class="text-[11px] text-slate-500">{{ item.phone || '-' }}</div>
        </td>
        <td class="px-4 py-3">
          <AppBadge :variant="item.role_type === 'mahasiswa' ? 'info' : 'warning'" size="sm">
            {{ item.role_type === 'mahasiswa' ? 'Mahasiswa' : 'Dosen' }}
          </AppBadge>
        </td>
        <td class="px-4 py-3">
          <AppBadge :variant="item.is_registered ? 'success' : 'neutral'" size="sm">
            {{ item.is_registered ? 'Registered' : 'Unregistered' }}
          </AppBadge>
        </td>
        <td class="px-4 py-3">
          <AppBadge :variant="item.status === 'active' ? 'success' : 'danger'" size="sm">
            {{ item.status === 'active' ? 'Aktif' : 'Non-Aktif' }}
          </AppBadge>
        </td>
        <td class="px-4 py-3 text-right space-x-2">
          <button @click="openEditModal(item)" class="text-slate-400 hover:text-primary-400 p-1 text-xs">Edit</button>
          <button @click="confirmDelete(item)" class="text-slate-400 hover:text-rose-400 p-1 text-xs">Hapus</button>
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

    <!-- Create / Edit Modal -->
    <AppModal :show="showModal" :title="isEdit ? 'Edit Master Data' : 'Tambah Master Data Baru'" @close="showModal = false">
      <form @submit.prevent="handleSubmit" class="space-y-4">
        <AppInput v-model="form.identity_number" label="Nomor Induk (NIM/NIDN)" required placeholder="misal: 20261001" />
        <AppInput v-model="form.full_name" label="Nama Lengkap" required placeholder="misal: Budi Santoso" />
        
        <div class="grid grid-cols-2 gap-3">
          <AppInput v-model="form.email" label="Email (Opsional)" type="email" placeholder="user@mail.com" />
          <AppInput v-model="form.phone" label="No. WhatsApp (Opsional)" placeholder="08123456789" />
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-medium text-slate-300 mb-1.5">Tipe Peran</label>
            <select v-model="form.role_type" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm">
              <option value="mahasiswa">Mahasiswa</option>
              <option value="dosen">Dosen</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-medium text-slate-300 mb-1.5">Status Master</label>
            <select v-model="form.status" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm">
              <option value="active">Aktif</option>
              <option value="inactive">Tidak Aktif</option>
            </select>
          </div>
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t border-slate-800">
          <AppButton variant="ghost" size="sm" @click="showModal = false">Batal</AppButton>
          <AppButton type="submit" variant="primary" size="sm" :loading="submitLoading">Simpan</AppButton>
        </div>
      </form>
    </AppModal>

    <!-- Import Modal -->
    <AppModal :show="showImportModal" title="Import Master NIM / NIDN (CSV / Excel)" @close="showImportModal = false">
      <div class="space-y-4">
        <p class="text-xs text-slate-300 leading-relaxed">
          Unggah file CSV/Excel berisi data master. Jika <strong>identity_number</strong> sudah ada di database, data tersebut akan di-<strong>SKIP</strong>.
        </p>

        <div>
          <label class="block text-xs font-medium text-slate-300 mb-1.5">Kategori Peran</label>
          <select v-model="importRoleType" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm">
            <option value="mahasiswa">Mahasiswa (NIM, Nama, Email, WhatsApp)</option>
            <option value="dosen">Dosen (NIDN/NIP, Nama, Email, WhatsApp)</option>
          </select>
        </div>

        <div class="border-2 border-dashed border-slate-800 rounded-xl p-6 text-center hover:border-primary-500 transition-colors">
          <input type="file" accept=".csv, .xlsx, .xls" @change="handleFileUpload" class="hidden" id="import-file-input" />
          <label for="import-file-input" class="cursor-pointer text-xs text-slate-400 hover:text-white">
            <svg class="w-8 h-8 mx-auto mb-2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            <span class="font-semibold text-primary-400">Pilih File CSV</span> atau drag & drop di sini
          </label>
          <div v-if="selectedFile" class="mt-2 text-xs font-bold text-emerald-400">
            Selected: {{ selectedFile.name }}
          </div>
        </div>

        <div v-if="importSummary" class="p-4 rounded-xl bg-slate-950 border border-slate-800 text-xs space-y-1">
          <div class="font-bold text-white mb-1">Hasil Laporan Import:</div>
          <div class="text-emerald-400">&bull; Inserted: {{ importSummary.inserted }}</div>
          <div class="text-amber-400">&bull; Skipped (Sudah ada): {{ importSummary.skipped }}</div>
          <div class="text-rose-400">&bull; Failed: {{ importSummary.failed }}</div>
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t border-slate-800">
          <AppButton variant="ghost" size="sm" @click="showImportModal = false">Tutup</AppButton>
          <AppButton variant="primary" size="sm" :loading="importLoading" :disabled="!selectedFile" @click="submitImport">
            Proses Import
          </AppButton>
        </div>
      </div>
    </AppModal>

    <!-- Delete Confirmation Modal -->
    <ConfirmDialog
      :show="showDeleteModal"
      title="Hapus Master Identity"
      :message="`Apakah Anda yakin ingin menghapus data master '${selectedItem?.full_name}' (${selectedItem?.identity_number})?`"
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
const importLoading = ref(false);
const deleteLoading = ref(false);

const items = ref<any[]>([]);
const pagination = reactive({
  current_page: 1,
  last_page: 1,
  per_page: 15,
  total: 0,
});

const filters = reactive({
  search: '',
  role_type: '',
  status: '',
});

const showModal = ref(false);
const isEdit = ref(false);
const editId = ref<number | null>(null);

const form = reactive({
  identity_number: '',
  full_name: '',
  email: '',
  phone: '',
  role_type: 'mahasiswa',
  status: 'active',
});

const showImportModal = ref(false);
const importRoleType = ref('mahasiswa');
const selectedFile = ref<File | null>(null);
const importSummary = ref<any>(null);

const showDeleteModal = ref(false);
const selectedItem = ref<any>(null);

let debounceTimer: any = null;
function debounceFetch() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => fetchData(1), 300);
}

async function fetchData(page = 1) {
  loading.value = true;
  try {
    const response = await api.get('/master-identities', {
      params: {
        page,
        search: filters.search,
        role_type: filters.role_type,
        status: filters.status,
      },
    });
    items.value = response.data.data;
    Object.assign(pagination, response.data.meta);
  } catch (err: any) {
    toastRef.value?.addToast('error', 'Gagal memuat data master identity');
  } finally {
    loading.value = false;
  }
}

function openCreateModal() {
  isEdit.value = false;
  editId.value = null;
  Object.assign(form, {
    identity_number: '',
    full_name: '',
    email: '',
    phone: '',
    role_type: 'mahasiswa',
    status: 'active',
  });
  showModal.value = true;
}

function openEditModal(item: any) {
  isEdit.value = true;
  editId.value = item.id;
  Object.assign(form, {
    identity_number: item.identity_number,
    full_name: item.full_name,
    email: item.email || '',
    phone: item.phone || '',
    role_type: item.role_type,
    status: item.status,
  });
  showModal.value = true;
}

async function handleSubmit() {
  submitLoading.value = true;
  try {
    if (isEdit.value && editId.value) {
      await api.put(`/master-identities/${editId.value}`, form);
      toastRef.value?.addToast('success', 'Data master berhasil diperbarui');
    } else {
      await api.post('/master-identities', form);
      toastRef.value?.addToast('success', 'Data master berhasil ditambahkan');
    }
    showModal.value = false;
    fetchData(pagination.current_page);
  } catch (err: any) {
    const msg = err.response?.data?.message || 'Gagal menyimpan data master';
    toastRef.value?.addToast('error', msg);
  } finally {
    submitLoading.value = false;
  }
}

function confirmDelete(item: any) {
  selectedItem.value = item;
  showDeleteModal.value = true;
}

async function executeDelete() {
  if (!selectedItem.value) return;
  deleteLoading.value = true;
  try {
    await api.delete(`/master-identities/${selectedItem.value.id}`);
    toastRef.value?.addToast('success', 'Data master berhasil dihapus');
    showDeleteModal.value = false;
    fetchData(pagination.current_page);
  } catch (err: any) {
    toastRef.value?.addToast('error', 'Gagal menghapus data master');
  } finally {
    deleteLoading.value = false;
  }
}

function handleFileUpload(e: any) {
  if (e.target.files && e.target.files[0]) {
    selectedFile.value = e.target.files[0];
  }
}

async function submitImport() {
  if (!selectedFile.value) return;
  importLoading.value = true;
  importSummary.value = null;

  const formData = new FormData();
  formData.append('file', selectedFile.value);
  formData.append('role_type', importRoleType.value);

  try {
    const response = await api.post('/master-identities/import', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    importSummary.value = response.data.data;
    toastRef.value?.addToast('success', 'Import data master selesai');
    fetchData(1);
  } catch (err: any) {
    toastRef.value?.addToast('error', 'Gagal memproses file import');
  } finally {
    importLoading.value = false;
  }
}

async function handleExport() {
  try {
    const response = await api.get('/master-identities/export', {
      params: { role_type: filters.role_type, status: filters.status },
    });
    const records = response.data.data;
    if (!records || records.length === 0) {
      toastRef.value?.addToast('warning', 'Tidak ada data untuk diexport');
      return;
    }
    const headers = Object.keys(records[0]).join(',');
    const rows = records.map((row: any) => Object.values(row).map(v => `"${v}"`).join(','));
    const csvContent = "data:text/csv;charset=utf-8," + [headers, ...rows].join("\n");
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `master_identities_${new Date().toISOString().slice(0, 10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    toastRef.value?.addToast('success', 'Export CSV berhasil didownload');
  } catch (err) {
    toastRef.value?.addToast('error', 'Gagal export data');
  }
}

onMounted(() => {
  fetchData(1);
});
</script>

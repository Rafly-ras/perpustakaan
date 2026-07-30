<template>
  <DefaultLayout>
    <!-- Toast Notifications -->
    <ToastNotification ref="toastRef" />

    <!-- Page Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <AppBreadcrumb :items="[{ label: 'Master Data' }, { label: 'User Management' }]" />
        <h1 class="text-2xl font-extrabold text-white tracking-tight mt-1">Kelola Akun Pengguna System</h1>
      </div>
      <AppButton variant="primary" size="sm" @click="openCreateModal">
        <template #icon-left>
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
        </template>
        Tambah Akun User
      </AppButton>
    </div>

    <!-- Filter & Search Bar -->
    <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 shadow-xl flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="w-full md:w-72">
        <AppInput
          v-model="filters.search"
          placeholder="Cari Nama, Email, NIM/NIDN..."
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
          v-model="filters.role"
          @change="fetchData(1)"
          class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-300 focus:outline-none focus:border-primary-500"
        >
          <option value="">Semua Role</option>
          <option value="super_admin">Super Admin</option>
          <option value="admin">Admin Operator</option>
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

    <!-- User Data Table -->
    <AppTable :loading="loading" :isEmpty="users.length === 0" :colSpan="7">
      <template #header>
        <th class="px-4 py-3">Pengguna & Contact</th>
        <th class="px-4 py-3">Role</th>
        <th class="px-4 py-3">NIM / NIDN</th>
        <th class="px-4 py-3">Saldo Koin</th>
        <th class="px-4 py-3">Status</th>
        <th class="px-4 py-3">Login Terakhir</th>
        <th class="px-4 py-3 text-right">Aksi</th>
      </template>

      <tr v-for="user in users" :key="user.id" class="hover:bg-slate-850/50 transition-colors">
        <td class="px-4 py-3">
          <div class="font-bold text-white">{{ user.name }}</div>
          <div class="text-xs text-slate-400">{{ user.email }}</div>
          <div class="text-[11px] text-slate-500">{{ user.phone || '-' }}</div>
        </td>
        <td class="px-4 py-3">
          <AppBadge :variant="roleBadgeVariant(user.role)" size="sm">
            {{ user.role }}
          </AppBadge>
        </td>
        <td class="px-4 py-3 font-mono text-xs text-slate-300">
          {{ user.nim || user.nidn || '-' }}
        </td>
        <td class="px-4 py-3 font-bold text-amber-400">
          {{ user.coin_balance }} Koin
        </td>
        <td class="px-4 py-3">
          <AppBadge :variant="user.status === 'active' ? 'success' : 'danger'" size="sm">
            {{ user.status === 'active' ? 'Aktif' : 'Non-Aktif' }}
          </AppBadge>
        </td>
        <td class="px-4 py-3 text-xs text-slate-400">
          {{ formatDate(user.created_at) }}
        </td>
        <td class="px-4 py-3 text-right space-x-2">
          <!-- Reset Password -->
          <button @click="openResetPasswordModal(user)" title="Reset Password" class="text-amber-400 hover:underline text-xs">
            Reset Pass
          </button>
          
          <!-- Toggle Status -->
          <button
            v-if="user.role !== 'super_admin'"
            @click="toggleUserStatus(user)"
            :class="user.status === 'active' ? 'text-rose-400 hover:underline' : 'text-emerald-400 hover:underline'"
            class="text-xs"
          >
            {{ user.status === 'active' ? 'Non-aktifkan' : 'Aktifkan' }}
          </button>

          <!-- Edit -->
          <button @click="openEditModal(user)" class="text-primary-400 hover:underline text-xs">Edit</button>

          <!-- Delete -->
          <button
            v-if="user.role !== 'super_admin'"
            @click="confirmDeleteUser(user)"
            class="text-rose-400 hover:underline text-xs ml-1"
          >
            Hapus
          </button>
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

    <!-- User Create / Edit Modal -->
    <AppModal :show="showModal" :title="isEdit ? 'Edit Data Pengguna' : 'Tambah Akun Pengguna Baru'" @close="showModal = false">
      <form @submit.prevent="handleSubmitUser" class="space-y-4">
        <AppInput v-model="form.name" label="Nama Lengkap" required placeholder="misal: Budi Santoso" />
        <AppInput v-model="form.email" label="Email" type="email" required placeholder="user@mail.com" />
        
        <div class="grid grid-cols-2 gap-3">
          <AppInput v-model="form.phone" label="No. WhatsApp" placeholder="08123456789" />
          <AppInput v-if="!isEdit" v-model="form.password" label="Password" type="password" required placeholder="Minimal 8 karakter" />
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-medium text-slate-300 mb-1.5">Role Pengguna</label>
            <select v-model="form.role" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm">
              <option value="super_admin">Super Admin</option>
              <option value="admin">Admin Operator Desk</option>
              <option value="mahasiswa">Mahasiswa</option>
              <option value="dosen">Dosen</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-medium text-slate-300 mb-1.5">Status Akun</label>
            <select v-model="form.status" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm">
              <option value="active">Aktif</option>
              <option value="inactive">Tidak Aktif</option>
            </select>
          </div>
        </div>

        <div v-if="form.role === 'mahasiswa' || form.role === 'dosen'" class="grid grid-cols-2 gap-3">
          <AppInput v-if="form.role === 'mahasiswa'" v-model="form.nim" label="NIM Mahasiswa" placeholder="20261001" />
          <AppInput v-if="form.role === 'dosen'" v-model="form.nidn" label="NIDN Dosen" placeholder="198501012010" />
          <AppInput v-model.number="form.coin_balance" label="Saldo Koin" type="number" placeholder="5" />
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t border-slate-800">
          <AppButton variant="ghost" size="sm" @click="showModal = false">Batal</AppButton>
          <AppButton type="submit" variant="primary" size="sm" :loading="submitLoading">Simpan Akun</AppButton>
        </div>
      </form>
    </AppModal>

    <!-- Reset Password Modal -->
    <AppModal :show="showResetModal" title="Reset Password Pengguna" @close="showResetModal = false">
      <form @submit.prevent="executeResetPassword" class="space-y-4">
        <p class="text-xs text-slate-300">
          Reset password untuk pengguna <strong>{{ selectedUser?.name }}</strong> ({{ selectedUser?.email }}).
        </p>

        <AppInput v-model="newPassword" label="Password Baru" type="password" required placeholder="Minimal 8 karakter" />

        <div class="flex justify-end gap-2 pt-4 border-t border-slate-800">
          <AppButton variant="ghost" size="sm" @click="showResetModal = false">Batal</AppButton>
          <AppButton type="submit" variant="primary" size="sm" :loading="resetLoading">Reset Password</AppButton>
        </div>
      </form>
    </AppModal>

    <!-- Delete Confirmation Modal -->
    <ConfirmDialog
      :show="showDeleteModal"
      title="Hapus Akun Pengguna"
      :message="`Apakah Anda yakin ingin menghapus akun '${selectedUser?.name}' (${selectedUser?.email})?`"
      :loading="deleteLoading"
      @confirm="executeDeleteUser"
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
const resetLoading = ref(false);
const deleteLoading = ref(false);

const users = ref<any[]>([]);
const pagination = reactive({
  current_page: 1,
  last_page: 1,
  per_page: 15,
  total: 0,
});

const filters = reactive({
  search: '',
  role: '',
  status: '',
});

const showModal = ref(false);
const isEdit = ref(false);
const editId = ref<number | null>(null);

const form = reactive({
  name: '',
  email: '',
  phone: '',
  password: '',
  role: 'mahasiswa',
  status: 'active',
  nim: '',
  nidn: '',
  coin_balance: 5,
});

const showResetModal = ref(false);
const newPassword = ref('');
const selectedUser = ref<any>(null);

const showDeleteModal = ref(false);

let debounceTimer: any = null;
function debounceFetch() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => fetchData(1), 300);
}

async function fetchData(page = 1) {
  loading.value = true;
  try {
    const response = await api.get('/users', {
      params: {
        page,
        search: filters.search,
        role: filters.role,
        status: filters.status,
      },
    });
    users.value = response.data.data;
    Object.assign(pagination, response.data.meta);
  } catch (err) {
    toastRef.value?.addToast('error', 'Gagal memuat data pengguna');
  } finally {
    loading.value = false;
  }
}

function roleBadgeVariant(role: string) {
  switch (role) {
    case 'super_admin': return 'danger';
    case 'admin': return 'primary';
    case 'mahasiswa': return 'info';
    case 'dosen': return 'warning';
    default: return 'neutral';
  }
}

function formatDate(dateStr?: string) {
  if (!dateStr) return '-';
  return new Date(dateStr).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
}

function openCreateModal() {
  isEdit.value = false;
  editId.value = null;
  Object.assign(form, {
    name: '',
    email: '',
    phone: '',
    password: '',
    role: 'mahasiswa',
    status: 'active',
    nim: '',
    nidn: '',
    coin_balance: 5,
  });
  showModal.value = true;
}

function openEditModal(user: any) {
  isEdit.value = true;
  editId.value = user.id;
  Object.assign(form, {
    name: user.name,
    email: user.email,
    phone: user.phone || '',
    password: '',
    role: user.role,
    status: user.status || 'active',
    nim: user.nim || '',
    nidn: user.nidn || '',
    coin_balance: user.coin_balance,
  });
  showModal.value = true;
}

async function handleSubmitUser() {
  submitLoading.value = true;
  try {
    if (isEdit.value && editId.value) {
      await api.put(`/users/${editId.value}`, form);
      toastRef.value?.addToast('success', 'Data akun pengguna berhasil diperbarui');
    } else {
      await api.post('/users', form);
      toastRef.value?.addToast('success', 'Akun pengguna baru berhasil ditambahkan');
    }
    showModal.value = false;
    fetchData(pagination.current_page);
  } catch (err: any) {
    const msg = err.response?.data?.message || 'Gagal menyimpan akun pengguna';
    toastRef.value?.addToast('error', msg);
  } finally {
    submitLoading.value = false;
  }
}

function openResetPasswordModal(user: any) {
  selectedUser.value = user;
  newPassword.value = '';
  showResetModal.value = true;
}

async function executeResetPassword() {
  if (!selectedUser.value) return;
  resetLoading.value = true;
  try {
    await api.post(`/users/${selectedUser.value.id}/reset-password`, { password: newPassword.value });
    toastRef.value?.addToast('success', `Password ${selectedUser.value.name} berhasil direset`);
    showResetModal.value = false;
  } catch (err: any) {
    const msg = err.response?.data?.message || 'Gagal reset password';
    toastRef.value?.addToast('error', msg);
  } finally {
    resetLoading.value = false;
  }
}

async function toggleUserStatus(user: any) {
  try {
    const endpoint = user.status === 'active' ? `/users/${user.id}/deactivate` : `/users/${user.id}/activate`;
    await api.patch(endpoint);
    toastRef.value?.addToast('success', `Status akun ${user.name} berhasil diubah`);
    fetchData(pagination.current_page);
  } catch (err: any) {
    const msg = err.response?.data?.message || 'Gagal merubah status pengguna';
    toastRef.value?.addToast('error', msg);
  }
}

function confirmDeleteUser(user: any) {
  selectedUser.value = user;
  showDeleteModal.value = true;
}

async function executeDeleteUser() {
  if (!selectedUser.value) return;
  deleteLoading.value = true;
  try {
    await api.delete(`/users/${selectedUser.value.id}`);
    toastRef.value?.addToast('success', 'Akun pengguna berhasil dihapus');
    showDeleteModal.value = false;
    fetchData(pagination.current_page);
  } catch (err: any) {
    const msg = err.response?.data?.message || 'Gagal menghapus akun pengguna';
    toastRef.value?.addToast('error', msg);
  } finally {
    deleteLoading.value = false;
  }
}

onMounted(() => {
  fetchData(1);
});
</script>

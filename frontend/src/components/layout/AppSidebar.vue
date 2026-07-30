<template>
  <aside
    :class="[
      'fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 border-r border-slate-800 flex flex-col transition-transform duration-300 md:static md:translate-x-0',
      isOpen ? 'translate-x-0' : '-translate-x-full'
    ]"
  >
    <!-- Brand -->
    <div class="h-16 px-6 flex items-center justify-between border-b border-slate-800">
      <router-link to="/dashboard" class="flex items-center space-x-2.5">
        <div class="w-9 h-9 rounded-xl bg-primary-600 flex items-center justify-center text-white font-black shadow-lg shadow-primary-600/30">
          L
        </div>
        <div class="font-extrabold text-white text-lg tracking-tight">Perpustakaan</div>
      </router-link>
      <button @click="$emit('close')" class="text-slate-400 hover:text-white md:hidden">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Nav List -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
      <!-- Main Nav Items -->
      <div class="pb-1 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
        Layanan Anggota
      </div>

      <router-link
        to="/dashboard"
        @click="$emit('close')"
        :class="[
          'flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200',
          $route.path.startsWith('/dashboard')
            ? 'bg-primary-600/20 text-primary-300 border border-primary-500/40 shadow-sm'
            : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200'
        ]"
      >
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span>Dashboard</span>
      </router-link>

      <router-link
        to="/opac"
        @click="$emit('close')"
        :class="[
          'flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200',
          $route.path === '/opac'
            ? 'bg-primary-600/20 text-primary-300 border border-primary-500/40 shadow-sm'
            : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200'
        ]"
      >
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        <span>Katalog OPAC</span>
      </router-link>

      <router-link
        to="/reservations"
        @click="$emit('close')"
        :class="[
          'flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200',
          $route.path === '/reservations'
            ? 'bg-primary-600/20 text-primary-300 border border-primary-500/40 shadow-sm'
            : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200'
        ]"
      >
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Antrean Reservasi</span>
      </router-link>

      <!-- Admin Desk & Circulation Menu -->
      <template v-if="authStore.userRole === 'admin' || authStore.userRole === 'super_admin'">
        <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
          Sirkulasi & Katalog
        </div>

        <router-link
          to="/admin/circulation"
          @click="$emit('close')"
          :class="[
            'flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200',
            $route.path === '/admin/circulation'
              ? 'bg-primary-600/20 text-primary-300 border border-primary-500/40 shadow-sm'
              : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200'
          ]"
        >
          <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
          </svg>
          <span>Sirkulasi Meja Desk</span>
        </router-link>

        <router-link
          to="/admin/books"
          @click="$emit('close')"
          :class="[
            'flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200',
            $route.path === '/admin/books'
              ? 'bg-primary-600/20 text-primary-300 border border-primary-500/40 shadow-sm'
              : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200'
          ]"
        >
          <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
          <span>Kelola Buku</span>
        </router-link>

        <router-link
          to="/admin/barcodes"
          @click="$emit('close')"
          :class="[
            'flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200',
            $route.path === '/admin/barcodes'
              ? 'bg-primary-600/20 text-primary-300 border border-primary-500/40 shadow-sm'
              : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200'
          ]"
        >
          <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m4-16v16m-8-16v16M4 4v16m16-16v16" />
          </svg>
          <span>Cetak Barcode</span>
        </router-link>
      </template>

      <!-- Super Admin Master Data Menu -->
      <template v-if="authStore.userRole === 'super_admin'">
        <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
          Master Data
        </div>

        <router-link
          to="/admin/master-identities"
          @click="$emit('close')"
          :class="[
            'flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200',
            $route.path === '/admin/master-identities'
              ? 'bg-primary-600/20 text-primary-300 border border-primary-500/40 shadow-sm'
              : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200'
          ]"
        >
          <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4" />
          </svg>
          <span>Master Identity</span>
        </router-link>

        <router-link
          to="/admin/users"
          @click="$emit('close')"
          :class="[
            'flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200',
            $route.path === '/admin/users'
              ? 'bg-primary-600/20 text-primary-300 border border-primary-500/40 shadow-sm'
              : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200'
          ]"
        >
          <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <span>User Management</span>
        </router-link>
      </template>
    </div>

    <!-- Role Indicator Footer -->
    <div class="p-4 border-t border-slate-800 bg-slate-950/40">
      <div class="flex items-center justify-between text-xs">
        <span class="text-slate-500">Role Hak Akses</span>
        <span class="font-bold text-primary-400 uppercase text-[11px]">{{ authStore.userRole || 'Guest' }}</span>
      </div>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/features/auth/stores/useAuthStore';

defineProps<{
  isOpen: boolean;
}>();

defineEmits<{
  (e: 'close'): void;
}>();

const authStore = useAuthStore();
</script>

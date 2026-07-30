<template>
  <AppModal :show="show" maxWidth="md" @close="$emit('cancel')">
    <template #title>
      <span class="text-red-400 font-bold flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        {{ title }}
      </span>
    </template>
    
    <p class="text-sm text-slate-300 py-2 leading-relaxed">
      {{ message }}
    </p>

    <template #footer>
      <AppButton variant="ghost" size="sm" @click="$emit('cancel')">Batal</AppButton>
      <AppButton variant="danger" size="sm" :loading="loading" @click="$emit('confirm')">
        {{ confirmText }}
      </AppButton>
    </template>
  </AppModal>
</template>

<script setup lang="ts">
import AppModal from './AppModal.vue';
import AppButton from './AppButton.vue';

withDefaults(
  defineProps<{
    show: boolean;
    title?: string;
    message?: string;
    confirmText?: string;
    loading?: boolean;
  }>(),
  {
    show: false,
    title: 'Konfirmasi Tindakan',
    message: 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
    confirmText: 'Ya, Lanjutkan',
    loading: false,
  }
);

defineEmits<{
  (e: 'confirm'): void;
  (e: 'cancel'): void;
}>();
</script>

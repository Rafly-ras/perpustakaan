<template>
  <div class="w-full">
    <label v-if="label" :for="id" class="block text-xs font-medium text-slate-300 mb-1.5">
      {{ label }}
      <span v-if="required" class="text-red-400">*</span>
    </label>
    <div class="relative rounded-xl shadow-sm">
      <div v-if="$slots['prefix-icon']" class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
        <slot name="prefix-icon" />
      </div>
      <input
        :id="id"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :required="required"
        :autocomplete="autocomplete"
        :class="inputClasses"
        @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
        @blur="$emit('blur', $event)"
        @focus="$emit('focus', $event)"
      />
      <div v-if="$slots['suffix-icon']" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500">
        <slot name="suffix-icon" />
      </div>
    </div>
    <p v-if="error" class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span>{{ error }}</span>
    </p>
    <p v-else-if="hint" class="mt-1.5 text-xs text-slate-500">{{ hint }}</p>
  </div>
</template>

<script setup lang="ts">
import { computed, useSlots } from 'vue';

const slots = useSlots();

const props = withDefaults(
  defineProps<{
    modelValue: string | number;
    id?: string;
    label?: string;
    type?: string;
    placeholder?: string;
    error?: string;
    hint?: string;
    disabled?: boolean;
    required?: boolean;
    autocomplete?: string;
  }>(),
  {
    id: () => `input-${Math.random().toString(36).substr(2, 9)}`,
    type: 'text',
    disabled: false,
    required: false,
    autocomplete: 'off',
  }
);

defineEmits<{
  (e: 'update:modelValue', value: string): void;
  (e: 'blur', event: FocusEvent): void;
  (e: 'focus', event: FocusEvent): void;
}>();

const inputClasses = computed(() => {
  const base = 'w-full py-2.5 rounded-xl bg-slate-950 border text-white placeholder-slate-500 focus:outline-none focus:ring-2 text-sm transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed';
  
  const padding = [
    slots['prefix-icon'] ? 'pl-10' : 'pl-4',
    slots['suffix-icon'] ? 'pr-10' : 'pr-4',
  ].join(' ');

  const borderState = props.error
    ? 'border-red-500/60 focus:border-red-500 focus:ring-red-500/30'
    : 'border-slate-800 focus:border-primary-500 focus:ring-primary-500/30 hover:border-slate-700';

  return [base, padding, borderState].join(' ');
});
</script>

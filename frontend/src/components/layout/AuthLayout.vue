<template>
  <div class="min-h-screen bg-slate-950 flex flex-col items-center justify-center p-4 relative overflow-hidden font-sans">
    <!-- Ambient Background Gradients -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-primary-600/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
      <slot />
    </div>

    <!-- Minimal Footer -->
    <div class="mt-8 text-center text-xs text-slate-600 relative z-10">
      &copy; 2026 Library Management System &bull; Single-Location Digital Circulation
    </div>
  </div>
</template>

<script setup lang="ts">
</script>

<template>
  <Transition name="fade-overlay">
    <div
      v-if="loading"
      class="fixed inset-0 z-[99999] flex flex-col items-center justify-center bg-brand-dark/95 backdrop-blur-md"
    >
      <!-- Premium Spinning Element -->
      <div class="relative flex items-center justify-center mb-6">
        <!-- Outer Glowing Ring -->
        <div class="absolute w-24 h-24 rounded-full border-4 border-brand-accent/20 animate-pulse"></div>
        
        <!-- Rotating Brand Primary & Accent Gradient Ring -->
        <div class="w-20 h-20 rounded-full border-4 border-t-brand-accent border-r-transparent border-b-brand-light border-l-transparent animate-spin"></div>
        
        <!-- Inner Pulsing Icon/Circle -->
        <div class="absolute w-10 h-10 rounded-full bg-brand-accent/15 flex items-center justify-center animate-bounce">
          <Icon name="ri:roadster-line" class="w-6 h-6 text-brand-accent" />
        </div>
      </div>

      <!-- Glowing Brand Title -->
      <h3 class="text-2xl font-black tracking-[0.2em] text-white mb-2 uppercase drop-shadow-[0_0_10px_rgba(167,126,82,0.5)]">
        Drivio
      </h3>

      <!-- Pulsing Subtitle -->
      <div class="flex items-center gap-2">
        <span class="text-sm font-semibold tracking-wider text-slate-300">{{ text }}</span>
        <!-- Animated dots -->
        <span class="flex gap-1">
          <span class="w-1.5 h-1.5 rounded-full bg-brand-accent animate-bounce" style="animation-delay: 0ms"></span>
          <span class="w-1.5 h-1.5 rounded-full bg-brand-accent animate-bounce" style="animation-delay: 150ms"></span>
          <span class="w-1.5 h-1.5 rounded-full bg-brand-accent animate-bounce" style="animation-delay: 300ms"></span>
        </span>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { watch, onBeforeUnmount } from 'vue'

const props = defineProps({
  loading: {
    type: Boolean,
    default: false,
  },
  text: {
    type: String,
    default: 'Đang tải dữ liệu',
  },
})

// Scroll lock body when loading overlay is active
if (process.client) {
  watch(
    () => props.loading,
    (newVal) => {
      if (newVal) {
        document.body.classList.add('overflow-hidden')
      } else {
        document.body.classList.remove('overflow-hidden')
      }
    },
    { immediate: true }
  )
}

onBeforeUnmount(() => {
  if (process.client) {
    document.body.classList.remove('overflow-hidden')
  }
})
</script>

<style scoped>
.fade-overlay-enter-active,
.fade-overlay-leave-active {
  transition: opacity 0.4s ease, backdrop-filter 0.4s ease;
}

.fade-overlay-enter-from,
.fade-overlay-leave-to {
  opacity: 0;
  backdrop-filter: blur(0px);
}
</style>

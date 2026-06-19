<template>
  <Transition name="fade-overlay">
    <div
      v-if="loading"
      class="fixed inset-0 z-[99999] flex flex-col items-center justify-center bg-black/50"
    >
      <!-- Basic Spinner -->
      <div class="w-10 h-10 rounded-full border-4 border-slate-200/60 border-t-teal-500 animate-spin mb-3"></div>
      
      <!-- Text -->
      <span v-if="text" class="text-sm font-medium text-white drop-shadow">
        {{ text }}
      </span>
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
  transition: opacity 0.2s ease;
}

.fade-overlay-enter-from,
.fade-overlay-leave-to {
  opacity: 0;
}
</style>

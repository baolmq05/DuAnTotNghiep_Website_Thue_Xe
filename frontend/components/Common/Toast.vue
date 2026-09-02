<template>
  <Teleport to="body">
    <div class="fixed top-6 right-6 z-[99999999] flex flex-col gap-2.5 max-w-sm pointer-events-none">
      <TransitionGroup name="toast-list">
        <div v-for="t in toasts" :key="t.id"
          class="pointer-events-auto flex items-center gap-3 bg-slate-900/95 backdrop-blur-md text-white px-4 py-3.5 rounded-2xl shadow-[0_12px_40px_rgba(0,0,0,0.35)] border border-white/20 transition-all duration-300 w-full">
          <div class="flex items-center justify-center w-7 h-7 rounded-full shrink-0">
            <Icon v-if="t.type == 'success'" name="heroicons:check-circle" class="w-6 h-6 text-emerald-400" />
            <Icon v-else-if="t.type == 'warning'" name="heroicons:exclamation-triangle"
              class="w-6 h-6 text-amber-400" />
            <Icon v-else-if="t.type == 'danger' || t.type == 'error'" name="heroicons:x-circle"
              class="w-6 h-6 text-rose-400" />
            <Icon v-else name="heroicons:information-circle" class="w-6 h-6 text-cyan-300" />
          </div>
          <div class="flex-1 min-w-0 pr-1">
            <p class="text-xs sm:text-sm font-medium leading-snug tracking-wide text-slate-50 break-words">{{ t.message
              }}</p>
          </div>
          <button type="button" @click="removeToast(t.id)"
            class="shrink-0 text-slate-400 hover:text-white transition-colors p-0.5 rounded-lg hover:bg-white/10 cursor-pointer">
            <Icon name="heroicons:x-mark" class="w-4 h-4" />
          </button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup>
import { useToast } from '~/composables/useToast'
const { toasts, removeToast } = useToast()
</script>

<style scoped>
.toast-list-enter-active,
.toast-list-leave-active {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.toast-list-enter-from {
  opacity: 0;
  transform: translateX(40px) scale(0.95);
}

.toast-list-leave-to {
  opacity: 0;
  transform: translateY(-15px) scale(0.9);
}

.toast-list-move {
  transition: transform 0.3s ease;
}
</style>
<template>
  <Transition name="fade-toast">
    <div v-if="isShow" class="fixed top-6 right-6 z-[9999] flex items-center gap-3 bg-brand-dark border border-brand-accent/30 text-white px-5 py-4 rounded-2xl shadow-xl max-w-sm">
      <div class="flex items-center justify-center w-8 h-8 rounded-full bg-brand-light text-brand-primary shrink-0">
        <Icon v-if="type === 'success'" name="heroicons:check-circle" class="w-6 h-6 text-brand-primary" />
        <Icon v-else-if="type === 'error'" name="heroicons:x-circle" class="w-6 h-6 text-rose-500" />
        <Icon v-else name="heroicons:information-circle" class="w-6 h-6 text-brand-accent" />
      </div>
      <div>
        <p class="text-sm font-semibold tracking-wide">{{ message }}</p>
      </div>
    </div>
  </Transition>
</template>

<script setup>
const { isShow, message, type } = useToast()
</script>

<style scoped>
.fade-toast-enter-active,
.fade-toast-leave-active {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.fade-toast-enter-from {
  opacity: 0;
  transform: translateY(-20px) scale(0.95);
}
.fade-toast-leave-to {
  opacity: 0;
  transform: translateY(-10px) scale(0.95);
}
</style>

<template>
  <div>
    <div
      class="fixed bottom-6 left-1/2 -translate-x-1/2 z-45"
    >
      <button
        type="button"
        class="inline-flex items-center gap-2 bg-slate-900 text-white text-sm font-bold px-8 py-5 rounded-full shadow-2xl"
        @click="openMap"
      >
        <Icon
          name="lucide:map"
          size="18"
          class="text-[#53cf84]"
        />
        Mở bản đồ
      </button>
    </div>

    <Transition name="modal-fade">
      <div
        v-if="isOpen"
        class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-3 md:p-6"
        @click.self="closeMap"
      >
        <div
          class="bg-white w-full h-full max-w-7xl max-h-[calc(100vh-3rem)] rounded-3xl overflow-hidden"
        >
          <div class="h-full">
            <ClientOnly>
              <VehicleMap
                :active="isOpen"
                :vehicles="vehicles"
              />
            </ClientOnly>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import VehicleMap from './VehicleMap.vue'

const props = defineProps<{
  vehicles?: any[]
}>()

const isOpen = ref(false)

const openMap = () => {
  isOpen.value = true
}

const closeMap = () => {
  isOpen.value = false
}
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: all 0.3s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>
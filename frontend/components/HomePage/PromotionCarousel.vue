<template>
  <section class="max-w-7xl mx-auto px-4 py-12">

    <div class="text-center max-w-xl mx-auto mb-12">
      <h2 class="text-3xl font-extrabold text-black tracking-tight">Chương trình khuyến mãi</h2>
      <p class="text-slate-800 mt-3 text-sm md:text-base">Khám phá các chương trình ưu đãi hấp dẫn dành cho khách
        hàng khi đặt xe tại Drivio</p>
    </div>


    <div class="relative">

      <!-- LEFT -->
      <button @click="scrollLeft"
        class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-2 rounded-full">
        ◀
      </button>

      <!-- VIEWPORT -->
      <div ref="slider" class="overflow-hidden">
        <!-- TRACK -->
        <div class="flex gap-4 transition-transform duration-500"
          :style="{ transform: `translateX(-${currentIndex * 100}%)` }">

          <!-- PAGE -->
          <div v-for="(page, i) in pages" :key="i"
            class="w-full flex-shrink-0 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- ITEM -->
            <div v-for="promo in page" :key="promo.id" class="cursor-pointer group" @click="openModal(promo)">
              <div class="relative h-[180px] md:h-[220px] rounded-2xl overflow-hidden">

                <!-- IMAGE -->
                <img :src="promo.banner"
                  class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />

                <!-- OVERLAY -->
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition"></div>

              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- RIGHT -->
      <button @click="scrollRight"
        class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-2 rounded-full">
        ▶
      </button>

    </div>

    <!-- MODAL -->
    <div v-if="showModal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">
      <div class="bg-white rounded-2xl w-[90%] max-w-md p-6 relative">

        <button @click="closeModal" class="absolute top-3 right-3">✕</button>

        <img :src="selectedPromotion?.banner" class="w-full h-[160px] object-cover rounded-lg mb-4" />

        <h3 class="text-xl font-bold mb-2">
          {{ selectedPromotion?.title }}
        </h3>

        <p class="text-sm text-slate-600 mb-3">
          {{ selectedPromotion?.description }}
        </p>

        <div class="text-center text-brand-primary font-bold">
          {{ selectedPromotion?.code }}
        </div>

      </div>
    </div>

  </section>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const promotions = [
  { id: 1, banner: 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2', title: 'Promo 1', description: 'Giảm 120K', code: 'A1' },
  { id: 2, banner: 'https://images.unsplash.com/photo-1503376780353-7e6692767b70', title: 'Promo 2', description: 'Giảm 120K', code: 'A2' },
  { id: 3, banner: 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341', title: 'Promo 3', description: 'Giảm 120K', code: 'A3' },
  { id: 4, banner: 'https://images.unsplash.com/photo-1502877338535-766e1452684a', title: 'Promo 4', description: 'Giảm 120K', code: 'A4' },
  { id: 5, banner: 'https://images.unsplash.com/photo-1493238792000-8113da705763', title: 'Promo 5', description: 'Giảm 120K', code: 'A5' },
  { id: 6, banner: 'https://images.unsplash.com/photo-1504215680853-026ed2a45def', title: 'Promo 6', description: 'Giảm 120K', code: 'A6' }
]

/* CHIA TRANG: 3 ITEM / PAGE */
const pages = computed(() => {
  const chunk = 3
  const result = []

  for (let i = 0; i < promotions.length; i += chunk) {
    result.push(promotions.slice(i, i + chunk))
  }

  return result
})

const currentIndex = ref(0)

const scrollRight = () => {
  if (currentIndex.value < pages.value.length - 1) {
    currentIndex.value++
  }
}

const scrollLeft = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--
  }
}

/* MODAL */
const showModal = ref(false)
const selectedPromotion = ref<any>(null)

const openModal = (promo: any) => {
  selectedPromotion.value = promo
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}
</script>

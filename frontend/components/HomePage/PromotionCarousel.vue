<template>
  <section class="max-w-7xl mx-auto px-4 py-12">

    <div class="text-center max-w-xl mx-auto mb-12">
      <h2 class="text-3xl font-extrabold text-black tracking-tight">Chương trình khuyến mãi</h2>
      <p class="text-slate-800 mt-3 text-sm md:text-base">Khám phá các chương trình ưu đãi hấp dẫn dành cho khách
        hàng khi đặt xe tại Drivio</p>
    </div>


    <div class="relative">

      <!-- LEFT -->
      <button 
        @click="scrollLeft"
        :disabled="currentIndex === 0"
        class="hidden md:flex absolute left-2 xl:-left-6 top-1/2 -translate-y-1/2 z-10 w-11 h-11 items-center justify-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-md hover:shadow-lg rounded-full text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-30 disabled:cursor-not-allowed disabled:pointer-events-none disabled:scale-100"
        aria-label="Previous page"
      >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
      </button>

      <!-- VIEWPORT -->
      <div 
        ref="slider" 
        class="overflow-hidden"
        @touchstart="handleTouchStart"
        @touchend="handleTouchEnd"
      >
        <!-- TRACK -->
        <div 
          class="flex -mx-2 transition-transform duration-500 ease-out"
          :style="{ transform: `translateX(-${currentIndex * (100 / itemsPerView)}%)` }"
        >
          <!-- ITEM -->
          <div 
            v-for="promo in promotions" 
            :key="promo.id" 
            class="w-full sm:w-1/2 lg:w-1/3 flex-shrink-0 px-2 cursor-pointer group" 
            @click="openModal(promo)"
          >
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

      <!-- RIGHT -->
      <button 
        @click="scrollRight"
        :disabled="currentIndex >= promotions.length - itemsPerView"
        class="hidden md:flex absolute right-2 xl:-right-6 top-1/2 -translate-y-1/2 z-10 w-11 h-11 items-center justify-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-md hover:shadow-lg rounded-full text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-300 hover:scale-110 active:scale-95 disabled:opacity-30 disabled:cursor-not-allowed disabled:pointer-events-none disabled:scale-100"
        aria-label="Next page"
      >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
      </button>

    </div>

    <!-- INDICATORS -->
    <div class="flex justify-center gap-2 mt-6">
      <button 
        v-for="index in promotions.length - itemsPerView + 1" 
        :key="index"
        @click="currentIndex = index - 1"
        class="w-2 h-2 rounded-full transition-all duration-300"
        :class="currentIndex === index - 1 ? 'bg-blue-600 w-5' : 'bg-slate-300 dark:bg-slate-700 hover:bg-slate-400'"
        :aria-label="`Go to item ${index}`"
      ></button>
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
import { ref, onMounted, onUnmounted } from 'vue'

const promotions = [
  { id: 1, banner: 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2', title: 'Promo 1', description: 'Giảm 120K', code: 'A1' },
  { id: 2, banner: 'https://images.unsplash.com/photo-1503376780353-7e6692767b70', title: 'Promo 2', description: 'Giảm 120K', code: 'A2' },
  { id: 3, banner: 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341', title: 'Promo 3', description: 'Giảm 120K', code: 'A3' },
  { id: 4, banner: 'https://images.unsplash.com/photo-1502877338535-766e1452684a', title: 'Promo 4', description: 'Giảm 120K', code: 'A4' },
  { id: 5, banner: 'https://images.unsplash.com/photo-1493238792000-8113da705763', title: 'Promo 5', description: 'Giảm 120K', code: 'A5' },
  { id: 6, banner: 'https://images.unsplash.com/photo-1504215680853-026ed2a45def', title: 'Promo 6', description: 'Giảm 120K', code: 'A6' }
]

const currentIndex = ref(0)
const itemsPerView = ref(3)

const updateItemsPerView = () => {
  if (typeof window === 'undefined') return
  if (window.innerWidth >= 1024) {
    itemsPerView.value = 3
  } else if (window.innerWidth >= 640) {
    itemsPerView.value = 2
  } else {
    itemsPerView.value = 1
  }

  // Keep currentIndex in bounds
  const maxIndex = promotions.length - itemsPerView.value
  if (currentIndex.value > maxIndex) {
    currentIndex.value = Math.max(0, maxIndex)
  }
}

onMounted(() => {
  updateItemsPerView()
  window.addEventListener('resize', updateItemsPerView)
})

onUnmounted(() => {
  if (typeof window !== 'undefined') {
    window.removeEventListener('resize', updateItemsPerView)
  }
})

const scrollRight = () => {
  const maxIndex = promotions.length - itemsPerView.value
  if (currentIndex.value < maxIndex) {
    currentIndex.value++
  }
}

const scrollLeft = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--
  }
}

/* TOUCH SWIPE EVENT HANDLERS FOR MOBILE */
const touchStartX = ref(0)
const touchEndX = ref(0)

const handleTouchStart = (e: TouchEvent) => {
  touchStartX.value = e.touches[0].clientX
}

const handleTouchEnd = (e: TouchEvent) => {
  touchEndX.value = e.changedTouches[0].clientX
  handleSwipe()
}

const handleSwipe = () => {
  const swipeThreshold = 50
  const diffX = touchStartX.value - touchEndX.value
  if (Math.abs(diffX) > swipeThreshold) {
    if (diffX > 0) {
      scrollRight()
    } else {
      scrollLeft()
    }
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

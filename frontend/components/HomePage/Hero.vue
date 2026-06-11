<template>
  <div
    class="relative w-full overflow-hidden bg-gradient-to-b from-brand-dark via-brand-primary to-brand-light/35 pb-12 pt-28 md:pt-36">
    <!-- Premium background glowing spots -->
    <div class="absolute top-20 left-1/4 w-80 h-80 bg-brand-accent/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-40 right-1/4 w-80 h-80 bg-brand-light/20 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Main Content Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Cards Row -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch relative">

        <!-- Left Column: Glassmorphic Text Card -->
        <div class="lg:col-span-5 flex flex-col justify-center">
          <div
            class="glass-effect rounded-3xl p-8 md:p-10 lg:p-12 shadow-xl flex flex-col justify-between h-full min-h-[320px] lg:min-h-[380px] relative overflow-hidden group hover:border-white/50 transition-all duration-300">
            <!-- Subtle light flare -->
            <div
              class="absolute -top-12 -left-12 w-44 h-44 bg-white/20 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-500">
            </div>

            <div class="relative z-10 my-auto">
              <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-brand-dark leading-tight tracking-tight">
                <span class="text-slate-800 bg-clip-text">Tự Do Cầm Lái</span>
              </h1>
              <p class="text-sm sm:text-base mt-4 sm:mt-6 leading-relaxed max-w-md">
                Trải nghiệm sự khác biệt với hơn <span class="text-brand-dark font-extrabold">15.000</span> xe tự lái
                chất
                lượng, đa dạng mẫu mã và dịch vụ chuyên nghiệp trên khắp cả nước.
              </p>
            </div>

            <!-- Extra info -->
            <div class="relative z-10 mt-6 pt-6 border-t border-white/20 flex items-center gap-3">
              <div class="flex items-center text-brand-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-accent" viewBox="0 0 20 20"
                  fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
                </svg>
                <span class="text-xs font-bold text-slate-700 ml-1">Đảm bảo 100% xe sạch sẽ & an toàn</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: Image Carousel (takes up 7 columns on large screens) -->
        <div class="lg:col-span-7 flex">
          <div class="w-full rounded-3xl shadow-xl overflow-hidden aspect-[4/3] lg:aspect-auto relative group flex"
            @mouseenter="stopAutoplay" @mouseleave="startAutoplay">
            <!-- Slides Container with Crossfade and Scale Effects -->
            <div class="relative w-full h-full min-h-[300px] lg:min-h-[380px] flex-grow">
              <div v-for="(slide, idx) in slides" :key="idx" :class="[
                'absolute inset-0 transition-all duration-1000 ease-in-out',
                currentSlide === idx ? 'opacity-100 scale-100 z-10' : 'opacity-0 scale-95 z-0'
              ]">
                <img :src="slide.url" :alt="slide.alt"
                  class="w-full h-full object-cover object-center transform transition-transform duration-10000 ease-linear"
                  :class="currentSlide === idx ? 'scale-105' : 'scale-100'" />
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent"></div>
              </div>
            </div>

            <!-- Left Navigation Arrow -->
            <button @click="prevSlide" type="button"
              class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full glass-effect flex items-center justify-center text-white hover:bg-white/30 hover:scale-105 active:scale-95 transition-all duration-200 opacity-0 group-hover:opacity-100 focus:outline-none"
              aria-label="Previous slide">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
              </svg>
            </button>

            <!-- Right Navigation Arrow -->
            <button @click="nextSlide" type="button"
              class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full glass-effect flex items-center justify-center text-white hover:bg-white/30 hover:scale-105 active:scale-95 transition-all duration-200 opacity-0 group-hover:opacity-100 focus:outline-none"
              aria-label="Next slide">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
              </svg>
            </button>

            <!-- Slide Indicators (Dots) -->
            <div
              class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex space-x-2 bg-slate-950/20 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-white/10">
              <button v-for="(_, idx) in slides" :key="idx" @click="setSlide(idx)" type="button" :class="[
                'h-2 rounded-full transition-all duration-300 focus:outline-none',
                currentSlide === idx ? 'bg-white w-5' : 'bg-white/50 w-2 hover:bg-white/80'
              ]" :aria-label="'Go to slide ' + (idx + 1)"></button>
            </div>
          </div>
        </div>

      </div>

      <!-- Overlapping Search Bar -->
      <div class="relative z-20 mt-8 lg:-mt-7 mx-auto max-w-5xl">
        <HomePageSearchBar />
      </div>

      <!-- Vehicle Categories Bottom Section -->
      <div class="mt-8 md:mt-12">
        <HomePageCategoryList />
      </div>

    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, onUnmounted } from 'vue'

const currentSlide = ref(0)
let autoplayTimer: any = null

const slides = [
  {
    url: '/images/index/banner/image1.psd.webp',
    alt: 'Volvo SUV driving on scenic winding coastal road at sunset'
  },
  {
    url: '/images/index/banner/image2.psd.webp',
    alt: 'Premium sports car driving fast on a sunset highway'
  },
  {
    url: '/images/index/banner/image3.psd.webp',
    alt: 'Electric SUV charging by a scenic view'
  },
  {
    url: '/images/index/banner/image4.psd.webp',
    alt: 'Premium car parked next to a beautiful seaside sunset cliffs'
  }
]

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % slides.length
}

const prevSlide = () => {
  currentSlide.value = (currentSlide.value - 1 + slides.length) % slides.length
}

const setSlide = (idx: number) => {
  currentSlide.value = idx
}

const startAutoplay = () => {
  stopAutoplay()
  autoplayTimer = setInterval(nextSlide, 5000)
}

const stopAutoplay = () => {
  if (autoplayTimer) {
    clearInterval(autoplayTimer)
    autoplayTimer = null
  }
}

onMounted(() => {
  startAutoplay()
})

onUnmounted(() => {
  stopAutoplay()
})
</script>

<style scoped>
/* Smooth slow panning zoom transition class */
.duration-10000 {
  transition-duration: 10000ms;
}
</style>

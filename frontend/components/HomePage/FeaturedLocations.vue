<template>
  <section class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold text-center mb-10">
      Địa Điểm Nổi Bật
    </h2>

    <div class="relative">

      <!-- BUTTON LEFT -->
      <button @click="scrollLeft"
        class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-2 rounded-full">
        ◀
      </button>

      <!-- SLIDER -->
      <div ref="slider" class="flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory scrollbar-hide">
        <div v-for="loc in locations" :key="loc.name" class="snap-start shrink-0 w-[80%] sm:w-[45%] lg:w-[23%]">
          <div class="relative h-[400px] rounded-2xl overflow-hidden">
            <img :src="loc.image" class="w-full h-full object-cover" />

            <div class="absolute inset-0 bg-black/40"></div>

            <div class="absolute bottom-4 left-4 text-white">
              <h3 class="font-bold text-lg">{{ loc.name }}</h3>
              <p class="text-sm">{{ loc.vehicles }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- BUTTON RIGHT -->
      <button @click="scrollRight"
        class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-2 rounded-full">
        ▶
      </button>

    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const slider = ref<HTMLElement | null>(null)

const locations = [
  {
    name: 'TP. Hồ Chí Minh',
    vehicles: '5000+ xe',
    image: 'https://images.unsplash.com/photo-1583417319070-4a69db38a482?auto=format&fit=crop&w=500&q=80'
  },
  {
    name: 'Hà Nội',
    vehicles: '3000+ xe',
    image: 'https://images.unsplash.com/photo-1621569898825-ef12e7592f94?auto=format&fit=crop&w=500&q=80'
  },
  {
    name: 'Đà Nẵng',
    vehicles: '500+ xe',
    image: 'https://images.unsplash.com/photo-1571401888741-ac4d307a61d1?auto=format&fit=crop&w=500&q=80'
  },
  {
    name: 'Đà Lạt',
    vehicles: '300+ xe',
    image: 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?auto=format&fit=crop&w=500&q=80'
  },
  {
    name: 'Nha Trang',
    vehicles: '250+ xe',
    image: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=500&q=80'
  }
]

const scrollLeft = () => {
  slider.value?.scrollBy({
    left: -300,
    behavior: 'smooth'
  })
}

const scrollRight = () => {
  slider.value?.scrollBy({
    left: 300,
    behavior: 'smooth'
  })
}
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
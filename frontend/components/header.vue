<template>
  <header 
    :class="[
      'absolute top-0 left-0 right-0 z-50 transition-all duration-300',
      isScrolled ? 'bg-slate-950/80 backdrop-blur-md border-b border-white/5 py-4' : 'bg-transparent py-6'
    ]"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between">
        
        <!-- Left: Logo -->
        <div class="flex-shrink-0">
          <NuxtLink to="/" class="flex items-center gap-2 group focus:outline-none">
            <!-- Stylized modern D-shaped logo -->
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              viewBox="0 0 100 100" 
              class="w-8 h-8 text-brand-light fill-current drop-shadow-[0_2px_8px_rgba(254,227,206,0.2)] transition-transform duration-300 group-hover:scale-105"
            >
              <path d="M20 15h30c20 0 35 15 35 35s-15 35-35 35H20V15zm16 16v38h14c10 0 18-8 18-19s-8-19-18-19H36z" />
              <circle cx="50" cy="50" r="10" class="text-brand-accent" />
            </svg>
            <span class="text-2xl font-black tracking-wider text-white group-hover:text-brand-light transition-colors duration-200">
              DRIVIO
            </span>
          </NuxtLink>
        </div>

        <!-- Middle: Navigation (Desktop) -->
        <nav class="hidden md:flex items-center space-x-8">
          <NuxtLink 
            v-for="item in navItems" 
            :key="item.text" 
            :to="item.to"
            class="text-sm font-medium text-white/90 hover:text-brand-accent transition-colors duration-200 relative group py-2"
          >
            {{ item.text }}
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-accent transition-all duration-300 group-hover:w-full"></span>
          </NuxtLink>
        </nav>

        <!-- Right: Actions (Desktop) -->
        <div class="hidden md:flex items-center space-x-6">
          <NuxtLink 
            to="/register" 
            class="text-sm font-medium text-white/90 hover:text-brand-accent transition-colors duration-200"
          >
            Đăng ký
          </NuxtLink>
          <NuxtLink 
            to="/login" 
            class="text-sm font-medium text-white border border-white/30 rounded-xl px-5 py-2 hover:bg-white/10 hover:border-white transition-all duration-200 shadow-sm"
          >
            Đăng nhập
          </NuxtLink>
        </div>

        <!-- Hamburger Button (Mobile) -->
        <div class="md:hidden flex items-center">
          <button 
            @click="toggleMenu"
            type="button" 
            class="inline-flex items-center justify-center p-2 rounded-xl text-white hover:text-brand-accent hover:bg-white/5 focus:outline-none transition-colors duration-200"
            aria-controls="mobile-menu" 
            :aria-expanded="isMenuOpen"
          >
            <span class="sr-only">Open main menu</span>
            <svg 
              v-if="!isMenuOpen" 
              class="block h-6 w-6" 
              xmlns="http://www.w3.org/2000/svg" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor" 
              aria-hidden="true"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg 
              v-else 
              class="block h-6 w-6" 
              xmlns="http://www.w3.org/2000/svg" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor" 
              aria-hidden="true"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

      </div>
    </div>

    <!-- Mobile Menu Overlay (Smooth Dropdown) -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 -translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-4"
    >
      <div 
        v-if="isMenuOpen" 
        class="md:hidden absolute top-full left-0 right-0 mt-1 mx-4 rounded-2xl glass-effect-dark shadow-2xl overflow-hidden border border-white/10"
        id="mobile-menu"
      >
        <div class="px-4 pt-4 pb-3 space-y-2">
          <NuxtLink 
            v-for="item in navItems" 
            :key="item.text" 
            :to="item.to"
            class="block px-4 py-3 rounded-xl text-base font-medium text-white hover:text-brand-accent hover:bg-white/5 transition-all duration-200"
            @click="isMenuOpen = false"
          >
            {{ item.text }}
          </NuxtLink>
        </div>
        <div class="pt-4 pb-6 px-8 border-t border-white/10 flex flex-col gap-3">
          <NuxtLink 
            to="/register" 
            class="w-full text-center py-3 rounded-xl text-base font-medium text-white hover:text-brand-accent hover:bg-white/5 transition-all duration-200"
            @click="isMenuOpen = false"
          >
            Đăng ký
          </NuxtLink>
          <NuxtLink 
            to="/login" 
            class="w-full text-center py-3 rounded-xl text-base font-medium text-white bg-brand-primary hover:bg-brand-dark transition-all duration-200 shadow-md shadow-brand-primary/20"
            @click="isMenuOpen = false"
          >
            Đăng nhập
          </NuxtLink>
        </div>
      </div>
    </transition>
  </header>
</template>

<script lang="ts" setup>
import { ref, onMounted, onUnmounted } from 'vue'

const isMenuOpen = ref(false)
const isScrolled = ref(false)

const navItems = [
  { text: 'Trang chủ', to: '/' },
  { text: 'Về Drivio', to: '/about' },
  { text: 'Bài viết', to: '/blog' },
  { text: 'Trở thành chủ xe', to: '/become-host' }
]

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const handleScroll = () => {
  isScrolled.value = window.scrollY > 20
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
/* Glassmorphism custom blur is imported from tailwind.css, but local classes work too */
</style>
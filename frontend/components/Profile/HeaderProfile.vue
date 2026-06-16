<template>
  <header :class="[
    'absolute top-0 left-0 right-0 z-50 transition-all duration-300',
    isScrolled ? 'bg-slate-950/80 backdrop-blur-md border-b border-white/5 py-4' : 'bg-transparent py-6'
  ]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between">
        <!-- Left: Logo -->
        <div class="flex-shrink-0">
          <NuxtLink to="/" class="flex items-center gap-2 group focus:outline-none">
            <!-- Stylized modern D-shaped logo -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
              class="w-8 h-8 text-brand-light fill-current drop-shadow-[0_2px_8px_rgba(254,227,206,0.2)] transition-transform duration-300 group-hover:scale-105">
              <path d="M20 15h30c20 0 35 15 35 35s-15 35-35 35H20V15zm16 16v38h14c10 0 18-8 18-19s-8-19-18-19H36z" />
              <circle cx="50" cy="50" r="10" class="text-brand-accent" />
            </svg>
            <span
              class="text-2xl font-black tracking-wider text-white group-hover:text-brand-light transition-colors duration-200">
              DRIVIO
            </span>
          </NuxtLink>
        </div>

        <!-- Middle: Navigation (Desktop) -->
        <nav class="hidden md:flex items-center space-x-8">
          <NuxtLink v-for="item in navItems" :key="item.text" :to="item.to"
            class="text-sm font-medium text-white/90 hover:text-brand-light transition-colors duration-200 relative group py-2">
            {{ item.text }}
            <span
              class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-light transition-all duration-300 group-hover:w-full"></span>
          </NuxtLink>
        </nav>

        <!-- Right: Actions (Desktop) -->
        <div class="hidden md:flex items-center space-x-6">
          <template v-if="isLoggedIn">
            <!-- Icons: Bell & Message -->
            <div class="flex items-center space-x-4 border-r border-white/20 pr-4">
              <!-- Bell Icon -->
              <button class="text-white/80 hover:text-white transition-colors relative focus:outline-none animate-fade-in" aria-label="Thông báo">
                <Icon name="heroicons:bell" class="w-6 h-6" />
                <span class="absolute top-0 right-0 w-2 h-2 bg-rose-500 rounded-full"></span>
              </button>
              <!-- Message Icon -->
              <button class="text-white/80 hover:text-white transition-colors relative focus:outline-none animate-fade-in" aria-label="Tin nhắn">
                <Icon name="heroicons:chat-bubble-left-ellipsis" class="w-6 h-6" />
              </button>
            </div>
            
            <!-- User Profile Link -->
            <NuxtLink to="/profile" class="flex items-center space-x-3 text-white focus:outline-none group animate-fade-in">
              <img :src="user.avatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100'" 
                alt="User Avatar" 
                class="w-8 h-8 rounded-full border border-white/20 object-cover shadow-sm group-hover:scale-105 transition-transform duration-200" />
              <span class="text-sm font-medium hover:text-brand-light transition-colors">{{ user.name }}</span>
            </NuxtLink>
          </template>
          
          <template v-else>
            <button @click="openRegister"
              class="text-sm font-medium text-white/90 hover:text-brand-light transition-colors duration-200 focus:outline-none">
              Đăng ký
            </button>
            <button @click="openLogin"
              class="text-sm font-medium text-white border border-white/30 rounded-xl px-5 py-2 hover:bg-white/10 hover:border-white transition-all duration-200 shadow-sm focus:outline-none">
              Đăng nhập
            </button>
          </template>
        </div>

      </div>
    </div>
  </header>

  <!-- Bottom Navigation (Mobile Only, Google Material 3 style) -->
  <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-[#f8f0e8]/95 backdrop-blur-md border-t border-slate-200/40 h-[80px] flex items-center justify-around pb-safe px-2 shadow-[0_-4px_12px_rgba(0,0,0,0.03)]">
    <div 
      v-for="(item, idx) in bottomNavItems" 
      :key="item.text" 
      @click="handleBottomNavClick(item, idx)"
      class="flex flex-col items-center justify-center flex-grow py-1 select-none cursor-pointer focus:outline-none"
    >
      <!-- Icon Wrapper (Material 3 Pill active indicator) -->
      <div 
        :class="[
          'px-5 py-1.5 rounded-full transition-all duration-300 flex items-center justify-center',
          activeTab === idx 
            ? 'bg-brand-primary/15 text-brand-primary scale-105 shadow-sm' 
            : 'text-slate-600 hover:text-brand-primary'
        ]"
      >
        <!-- Home Icon -->
        <svg v-if="item.icon === 'home'" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24">
          <path fill="currentColor" d="M10 20v-6h4v6h5v-8h3L12 3L2 12h3v8z"/>
        </svg>

        <!-- About Icon -->
        <svg v-else-if="item.icon === 'info'" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24">
          <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2m0 15c-.55 0-1-.45-1-1v-4c0-.55.45-1 1-1s1 .45 1 1v4c0 .55-.45 1-1 1m1-8h-2V7h2z"/>
        </svg>

        <!-- Blog Icon -->
        <svg v-else-if="item.icon === 'blog'" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24">
          <path fill="currentColor" d="M21 5c-1.11-.35-2.33-.5-3.5-.5c-1.95 0-4.05.4-5.5 1.5c-1.45-1.1-3.55-1.5-5.5-1.5S2.45 4.9 1 6v14.65c0 .25.25.5.5.5c.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5c1.35-.85 3.8-1.5 5.5-1.5c1.65 0 3.35.3 4.75 1.05c.1.05.15.05.25.05c.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1m0 13.5c-1.1-.35-2.3-.5-3.5-.5c-1.7 0-4.15.65-5.5 1.5V8c1.35-.85 3.8-1.5 5.5-1.5c1.2 0 2.4.15 3.5.5z"/>
          <path fill="currentColor" d="M17.5 10.5c.88 0 1.73.09 2.5.26V9.24c-.79-.15-1.64-.24-2.5-.24c-1.7 0-3.24.29-4.5.83v1.66c1.13-.64 2.7-.99 4.5-.99M13 12.49v1.66c1.13-.64 2.7-.99 4.5-.99c.88 0 1.73.09 2.5.26V11.9c-.79-.15-1.64-.24-2.5-.24c-1.7 0-3.24.3-4.5.83m4.5 1.84c-1.7 0-3.24.29-4.5.83v1.66c1.13-.64 2.7-.99 4.5-.99c.88 0 1.73.09 2.5.26v-1.52c-.79-.16-1.64-.24-2.5-.24"/>
        </svg>

        <!-- Đăng ký chủ xe -->
        <svg v-else-if="item.icon === 'host'" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24">
          <path fill="currentColor" d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5H15V3H9v2H6.5c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16m11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5s1.5.67 1.5 1.5s-.67 1.5-1.5 1.5M5 11l1.5-4.5h11L19 11z"/>
        </svg>

        <!-- Tài khoản -->
        <svg v-else-if="item.icon === 'account'" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24">
          <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2m0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6m0 14c-2.03 0-4.43-.82-6.14-2.88a9.95 9.95 0 0 1 12.28 0C16.43 19.18 14.03 20 12 20"/>
        </svg>
      </div>

      <!-- Label -->
      <span 
        :class="[
          'text-[10px] tracking-wide mt-1 transition-all duration-300',
          activeTab === idx 
            ? 'text-brand-primary font-bold scale-105' 
            : 'text-slate-500 font-medium'
        ]"
      >
        {{ item.text }}
      </span>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted, onUnmounted } from 'vue'

const { openLogin, openRegister } = useAuthModal()
const { user, isLoggedIn } = useAuth()

const isScrolled = ref(false)
const activeTab = ref(0)

const navItems = [
  { text: 'Trang chủ', to: '/' },
  { text: 'Về Drivio', to: '/about' },
  { text: 'Bài viết', to: '/blogs' },
  { text: 'Trở thành chủ xe', to: '/become-host' }
]

const bottomNavItems = [
  { text: 'Trang chủ', icon: 'home', to: '/' },
  { text: 'Về Drivio', icon: 'info', to: '/about' },
  { text: 'Bài viết', icon: 'blog', to: '/blogs' },
  { text: 'Chủ xe', icon: 'host', to: '/become-host' },
  { text: 'Tài khoản', icon: 'account' }
]

const handleBottomNavClick = (item: any, idx: number) => {
  activeTab.value = idx
  if (item.icon === 'account') {
    if (isLoggedIn.value) {
      navigateTo('/profile')
    } else {
      openLogin()
    }
  } else if (item.to) {
    navigateTo(item.to)
  }
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
/* Google Material 3 Safe Area support styling if any */
header{
    background-color: #286874;
}
.pb-safe {
  padding-bottom: env(safe-area-inset-bottom, 0px);
}
</style>
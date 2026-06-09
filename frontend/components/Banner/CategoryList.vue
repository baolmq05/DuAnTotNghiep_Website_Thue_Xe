<template>
  <div class="w-full flex items-center justify-center overflow-x-auto no-scrollbar py-6 px-4">
    <div class="flex items-center space-x-8 md:space-x-16 min-w-max">
      <button
        v-for="(cat, idx) in categories"
        :key="cat.name"
        @click="selectCategory(idx)"
        type="button"
        class="flex flex-col items-center gap-3 group focus:outline-none transition-all duration-300 relative pb-2"
      >
        <!-- Icon Wrapper with active animations -->
        <div 
          :class="[
            'w-14 h-14 rounded-full flex items-center justify-center transition-all duration-300',
            activeIdx === idx 
              ? 'bg-brand-light/40 text-brand-primary scale-110 shadow-sm border border-brand-light/70' 
              : 'bg-white text-slate-400 group-hover:text-slate-600 group-hover:scale-105 border border-slate-100'
          ]"
        >
          <!-- Dynamic SVG Icons -->
          <svg 
            xmlns="http://www.w3.org/2000/svg" 
            viewBox="0 0 24 24" 
            fill="none" 
            stroke="currentColor" 
            stroke-width="1.8" 
            stroke-linecap="round" 
            stroke-linejoin="round"
            class="w-7 h-7"
          >
            <!-- Xe 4 chỗ (Sedan) -->
            <path v-if="cat.icon === 'sedan'" d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 001 13v3c0 .6.4 1 1 1h2m10 0h4m-12 0a2.5 2.5 0 100-5 2.5 2.5 0 000 5zm12 0a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
            
            <!-- Xe 7 chỗ (SUV) -->
            <path v-else-if="cat.icon === 'suv'" d="M19 17h2c.6 0 1-.4 1-1v-4c0-.9-.7-1.7-1.5-1.9C18.7 9.6 15 9 15 9s-1.3-2-2.5-3c-.5-.4-1.1-.7-1.8-.7H4c-.6 0-1.1.4-1.4.9l-1.4 3.9A3.7 3.7 0 001 12v4c0 .6.4 1 1 1h2m10 0h4m-12 0a2.5 2.5 0 100-5 2.5 2.5 0 000 5zm12 0a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
            
            <!-- Xe điện (EV Plug) -->
            <g v-else-if="cat.icon === 'ev'">
              <path d="M5 14h12M12 4v16M18 9l3 3-3 3M6 9l-3 3 3 3" />
              <circle cx="12" cy="12" r="3" />
            </g>
            
            <!-- Xe bán tải (Pickup Truck) -->
            <path v-else-if="cat.icon === 'pickup'" d="M14 17h7c.6 0 1-.4 1-1v-4c0-.9-.7-1.7-1.5-1.9C18.7 9.6 15 9 15 9V5c0-.6-.4-1-1-1H9c-.6 0-1.1.4-1.4.9L6 8H2c-.6 0-1 .4-1 1v7c0 .6.4 1 1 1h2m10 0h2m-10 0a2.5 2.5 0 100-5 2.5 2.5 0 000 5zm12 0a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
            
            <!-- Xe hạng sang (Luxury Sports Car) -->
            <path v-else-if="cat.icon === 'luxury'" d="M21 16V9c0-1.1-.9-2-2-2h-3L13.5 4h-4L7 7H5c-1.1 0-2 .9-2 2v7c0 .6.4 1 1 1h1m12 0h1c.6 0 1-.4 1-1m-14 0a2 2 0 100-4 2 2 0 000 4zm12 0a2 2 0 100-4 2 2 0 000 4z" />
          </svg>
        </div>

        <!-- Name Label -->
        <span 
          :class="[
            'text-xs font-semibold tracking-wide transition-colors duration-300',
            activeIdx === idx ? 'text-brand-primary font-bold' : 'text-slate-600 group-hover:text-slate-800'
          ]"
        >
          {{ cat.name }}
        </span>

        <!-- Active Dot Line Underneath -->
        <span 
          :class="[
            'absolute bottom-0 w-8 h-1 rounded-full bg-brand-accent transition-all duration-300',
            activeIdx === idx ? 'opacity-100 scale-100' : 'opacity-0 scale-50'
          ]"
        ></span>
      </button>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

const activeIdx = ref(0)

const categories = [
  { name: 'Xe 4 chỗ', icon: 'sedan' },
  { name: 'Xe 7 chỗ', icon: 'suv' },
  { name: 'Xe điện', icon: 'ev' },
  { name: 'Xe bán tải', icon: 'pickup' },
  { name: 'Xe hạng sang', icon: 'luxury' }
]

const selectCategory = (idx: number) => {
  activeIdx.value = idx
}
</script>

<style scoped>
/* Standard hide scrollbar styles in case tailwind isn't active on custom utility */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>

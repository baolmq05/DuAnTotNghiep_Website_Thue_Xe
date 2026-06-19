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
          <!-- Xe 4 chỗ & Xe 7 chỗ (Sedan & SUV) -->
          <svg 
            v-if="cat.icon === 'sedan' || cat.icon === 'suv'" 
            viewBox="0 0 32 32"
            class="w-7 h-7"
            stroke="currentColor" 
            stroke-width="1.8" 
            stroke-linecap="round" 
            stroke-linejoin="round"
            fill="none"
          >
            <path d=" M27.2,12.499H30c0.276,0,0.5,0.223,0.5,0.5v1c0,0.276-0.212,0.57-0.475,0.658" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M6.5,26.999v2.5c0,0.552-0.447,1-1,1h-2c-0.553,0-1-0.448-1-1v-2.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M29.5,26.499v3c0,0.552-0.447,1-1,1h-2c-0.553,0-1-0.448-1-1v-3" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M4.8,12.499H2c-0.276,0-0.5,0.223-0.5,0.5v1c0,0.276,0.212,0.57,0.475,0.658" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M29.5,26.499h-27c-0.553,0-1-0.448-1-1v-1h29v1C30.5,26.051,30.053,26.499,29.5,26.499z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M1.5,22.499v-3.5c0-2.938,3.583-3.5,14.5-3.5s14.5,0.563,14.5,3.5v3.188" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M30.5,19.499v3h-6v-2c0-0.553,0.447-1,1-1h3" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M3.5,19.499h3c0.553,0,1,0.447,1,1v2h-6v-3" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M9.5,22.499v-1c0-1.1,0.9-2,2-2h9c1.1,0,2,0.9,2,2v1" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M4.101,15.997l1.507-7.536c0.217-1.083,1.221-2.312,2.312-2.487C9.408,5.732,11.51,5.5,16,5.5s6.592,0.232,8.081,0.474 c1.091,0.176,2.095,1.404,2.312,2.487l1.509,7.547" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
          </svg>

          <!-- Xe điện (EV) -->
          <svg 
            v-else-if="cat.icon === 'ev'" 
            viewBox="0 0 32 32"
            class="w-7 h-7"
            stroke="currentColor" 
            stroke-width="1.8" 
            stroke-linecap="round" 
            stroke-linejoin="round"
            fill="none"
          >
            <path d=" M27.2,13.499H30c0.276,0,0.5,0.223,0.5,0.5v1c0,0.276-0.212,0.57-0.475,0.658" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M6.5,26.999v2.5c0,0.552-0.447,1-1,1h-2c-0.553,0-1-0.448-1-1v-2.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M29.5,26.499v3c0,0.552-0.447,1-1,1h-2c-0.553,0-1-0.448-1-1v-3" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M4.8,13.499H2c-0.276,0-0.5,0.223-0.5,0.5v1c0,0.276,0.212,0.57,0.475,0.658" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M4.109,17.003l1.494-6.74c0.267-1.2,1.233-2.121,2.448-2.313c0.851-0.133,1.908-0.262,3.479-0.348" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M20.967,7.631c1.303,0.085,2.225,0.201,2.981,0.319c1.215,0.192,2.182,1.113,2.448,2.313l1.494,6.741" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M29.5,26.499h-27c-0.553,0-1-0.448-1-1v-1h29v1C30.5,26.051,30.053,26.499,29.5,26.499z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M1.5,21.999v-2.5c0-2.938,3.583-3,14.5-3s14.5,0.063,14.5,3v2.188" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M30.5,19.499v3h-6v-2c0-0.553,0.447-1,1-1h3" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M3.5,19.499h3c0.553,0,1,0.447,1,1v2h-6v-3" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <path d=" M9.5,22.499v-1c0-1.1,0.9-2,2-2h9c1.1,0,2,0.9,2,2v1" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></path>
            <polyline points=" 16.5,6.498 17.5,1.498 12.5,9.498 15.5,9.498 14.5,14.498 19.5,6.498 " stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"></polyline>
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
  { name: 'Xe điện', icon: 'ev' }
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

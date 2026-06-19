<template>
  <div class="glass-search-bar shadow-xl rounded-2xl md:rounded-full p-4 md:p-3 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 md:gap-1">
    
    <!-- Field 1: Địa điểm -->
    <div class="flex-1 min-w-0 px-4 py-2 md:py-1 flex items-center gap-3 hover:bg-white/20 rounded-xl md:rounded-full transition-colors duration-200 cursor-pointer">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>
      <div class="flex-grow min-w-0">
        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Địa điểm</label>
        <input 
          v-model="location"
          type="text" 
          placeholder="Bạn muốn đi đâu?" 
          class="block w-full bg-transparent border-0 p-0 text-sm font-semibold text-slate-800 placeholder-slate-500 focus:ring-0 focus:outline-none"
        />
      </div>
    </div>

    <!-- Divider (Desktop) -->
    <div class="hidden md:block h-8 w-px bg-slate-300/60"></div>

    <!-- Field 2: Ngày nhận -->
    <div @click="isDatePickerOpen = true" class="flex-1 min-w-0 px-4 py-2 md:py-1 flex items-center gap-3 hover:bg-white/20 rounded-xl md:rounded-full transition-colors duration-200 cursor-pointer">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
      <div class="flex-grow min-w-0">
        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Ngày nhận</label>
        <div class="block w-full text-sm font-semibold truncate" :class="formattedStart ? 'text-slate-800' : 'text-slate-400'">
          {{ formattedStart || 'Chọn ngày nhận xe' }}
        </div>
      </div>
    </div>

    <!-- Divider (Desktop) -->
    <div class="hidden md:block h-8 w-px bg-slate-300/60"></div>

    <!-- Field 3: Ngày trả -->
    <div @click="isDatePickerOpen = true" class="flex-1 min-w-0 px-4 py-2 md:py-1 flex items-center gap-3 hover:bg-white/20 rounded-xl md:rounded-full transition-colors duration-200 cursor-pointer">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
      <div class="flex-grow min-w-0">
        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Ngày trả</label>
        <div class="block w-full text-sm font-semibold truncate" :class="formattedEnd ? 'text-slate-800' : 'text-slate-400'">
          {{ formattedEnd || 'Chọn ngày trả xe' }}
        </div>
      </div>
    </div>

    <!-- Divider (Desktop) -->
    <div class="hidden md:block h-8 w-px bg-slate-300/60"></div>

    <!-- Field 4: Dòng xe -->
    <div class="flex-1 min-w-0 px-4 py-2 md:py-1 flex items-center gap-3 hover:bg-white/20 rounded-xl md:rounded-full transition-colors duration-200 cursor-pointer">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
      </svg>
      <div class="flex-grow min-w-0">
        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Dòng xe</label>
        <select 
          v-model="carType"
          class="block w-full bg-transparent border-0 p-0 pr-8 text-sm font-semibold text-slate-800 focus:ring-0 focus:outline-none appearance-none cursor-pointer"
        >
          <option value="">Tất cả các dòng</option>
          <option value="4">Xe 4 chỗ</option>
          <option value="7">Xe 7 chỗ</option>
          <option value="ev">Xe điện</option>
        </select>
      </div>
    </div>

    <!-- Search Button -->
    <button 
      @click="handleSearch"
      type="button" 
      class="bg-brand-primary hover:bg-brand-dark text-white font-semibold py-3.5 px-8 rounded-xl md:rounded-full transition-all duration-200 shadow-md shadow-brand-primary/20 active:scale-95 flex items-center justify-center gap-2 flex-shrink-0"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
      <span>Tìm xe</span>
    </button>

  </div>
  <DatePickerModal 
    :is-open="isDatePickerOpen" 
    :initial-start="selectedStart || undefined" 
    :initial-end="selectedEnd || undefined" 
    @close="isDatePickerOpen = false" 
    @apply="handleApplyDates"
  />
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'
import { useRouter, useRoute } from '#app'
import DatePickerModal from '~/components/Shared/DatePickerModal.vue'

const route = useRoute()
const router = useRouter()

const location = ref((route.query.location as string) || '')
const carType = ref((route.query.carType as string) || '')

const isDatePickerOpen = ref(false)

const formatDateString = (date: Date | null | undefined): string | undefined => {
  if (!date) return undefined
  const pad = (num: number) => String(num).padStart(2, '0')
  return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`
}

const parseDateString = (str: string | null | undefined): Date | null => {
  if (!str) return null
  const formattedStr = str.replace(' ', 'T')
  const date = new Date(formattedStr)
  return isNaN(date.getTime()) ? null : date
}

const selectedStart = ref<Date | null>(parseDateString(route.query.startDate as string))
const selectedEnd = ref<Date | null>(parseDateString(route.query.endDate as string))

const formattedStart = computed(() => {
  if (!selectedStart.value) return ''
  const d = selectedStart.value
  return `${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')} ${d.getDate().toString().padStart(2, '0')}/${(d.getMonth()+1).toString().padStart(2, '0')}`
})

const formattedEnd = computed(() => {
  if (!selectedEnd.value) return ''
  const d = selectedEnd.value
  return `${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')} ${d.getDate().toString().padStart(2, '0')}/${(d.getMonth()+1).toString().padStart(2, '0')}`
})

const handleApplyDates = (payload: any) => {
  selectedStart.value = payload.start
  selectedEnd.value = payload.end
  isDatePickerOpen.value = false
}

const handleSearch = () => {
  router.push({
    path: '/vehicle-list',
    query: {
      location: location.value || undefined,
      startDate: formatDateString(selectedStart.value),
      endDate: formatDateString(selectedEnd.value),
      carType: carType.value || undefined
    }
  })
}
</script>

<style scoped>
/* Removing standard browser arrow for select dropdown to style nicely */
select {
  background-image: url("data:image/svg+xml;utf8,<svg fill='%231e293b' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/><path d='M0 0h24v24H0z' fill='none'/></svg>");
  background-repeat: no-repeat;
  background-position-x: 95%;
  background-position-y: 50%;
}
</style>

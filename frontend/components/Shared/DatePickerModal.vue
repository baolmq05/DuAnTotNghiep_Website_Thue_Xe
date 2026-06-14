<template>
  <Teleport to="body">
    <Transition name="modal-fade">
      <div v-if="isOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div @click="close" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white w-full max-w-3xl rounded-3xl shadow-2xl overflow-hidden flex flex-col">
          
          <!-- Header -->
          <div class="px-6 py-4 flex justify-between items-center border-b border-slate-100">
            <h3 class="text-xl font-black text-slate-800 absolute left-1/2 -translate-x-1/2">Thời gian</h3>
            <div class="w-full flex justify-end">
              <button @click="close" class="p-2 rounded-full hover:bg-slate-100 text-slate-500 transition-colors">
                <Icon name="lucide:x" class="w-5 h-5" />
              </button>
            </div>
          </div>

          <!-- Tabs -->
          <div class="flex border-b border-slate-100">
            <button 
              @click="activeTab = 'daily'"
              class="flex-1 py-4 text-sm font-bold transition-colors border-b-2"
              :class="activeTab === 'daily' ? 'border-brand-primary text-slate-800' : 'border-transparent text-slate-400 hover:text-slate-600'"
            >
              Thuê theo ngày
            </button>
            <button 
              @click="activeTab = 'hourly'"
              class="flex-1 py-4 text-sm font-bold transition-colors border-b-2"
              :class="activeTab === 'hourly' ? 'border-brand-primary text-slate-800' : 'border-transparent text-slate-400 hover:text-slate-600'"
            >
              Thuê theo giờ
            </button>
          </div>

          <!-- Body -->
          <div class="p-6 overflow-y-auto max-h-[70vh]">
            <!-- Calendar -->
            <div class="flex justify-center mb-6">
              <ClientOnly>
                <VDatePicker 
                  v-model="range" 
                  is-range 
                  :columns="columns"
                  :step="1"
                  color="green"
                  :min-date="new Date()"
                  borderless
                  expanded
                  class="custom-calendar"
                />
              </ClientOnly>
            </div>

            <!-- Time Selectors -->
            <div class="flex items-center gap-4">
              <div class="flex-1 bg-white border border-slate-200 rounded-xl p-3 focus-within:border-brand-primary focus-within:ring-1 focus-within:ring-brand-primary transition-all">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nhận xe</label>
                <select v-model="startTime" class="w-full bg-transparent border-0 p-0 text-base font-bold text-slate-800 focus:ring-0 cursor-pointer outline-none">
                  <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                </select>
              </div>

              <div class="flex-shrink-0 text-slate-400">
                <Icon name="lucide:arrow-right-circle" class="w-6 h-6" />
              </div>

              <div class="flex-1 bg-white border border-slate-200 rounded-xl p-3 focus-within:border-brand-primary focus-within:ring-1 focus-within:ring-brand-primary transition-all">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Trả xe</label>
                <select v-model="endTime" class="w-full bg-transparent border-0 p-0 text-base font-bold text-slate-800 focus:ring-0 cursor-pointer outline-none">
                  <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="w-full sm:w-auto text-center sm:text-left">
              <div class="text-base font-bold text-slate-800">{{ summaryText }}</div>
              <div class="text-sm font-medium text-slate-500 flex items-center justify-center sm:justify-start gap-1">
                Thời gian thuê: <span class="text-brand-primary font-bold">{{ durationText }}</span>
                <Icon name="lucide:help-circle" class="w-4 h-4" />
              </div>
            </div>
            <button @click="apply" class="w-full sm:w-auto bg-brand-primary hover:bg-opacity-90 text-white font-bold py-3 px-8 rounded-xl shadow-md shadow-brand-primary/20 transition-all active:scale-95">
              Tiếp tục
            </button>
          </div>

        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'

const props = defineProps({
  isOpen: Boolean,
  initialStart: Date,
  initialEnd: Date
})

const emit = defineEmits(['close', 'apply'])

const activeTab = ref('daily')
const range = ref({
  start: props.initialStart || new Date(),
  end: props.initialEnd || new Date(Date.now() + 86400000 * 2)
})

const startTime = ref('21:00')
const endTime = ref('20:00')

// Responsive columns for VDatePicker
const windowWidth = ref(1024)
const columns = computed(() => windowWidth.value >= 768 ? 2 : 1)

const updateWidth = () => {
  windowWidth.value = window.innerWidth
}

onMounted(() => {
  windowWidth.value = window.innerWidth
  window.addEventListener('resize', updateWidth)
})

onUnmounted(() => {
  window.removeEventListener('resize', updateWidth)
})

const timeOptions = Array.from({ length: 48 }).map((_, i) => {
  const h = Math.floor(i / 2).toString().padStart(2, '0')
  const m = i % 2 === 0 ? '00' : '30'
  return `${h}:${m}`
})

const close = () => {
  emit('close')
}

const apply = () => {
  if (!range.value || !range.value.start || !range.value.end) {
    alert("Vui lòng chọn ngày nhận và ngày trả")
    return
  }
  
  const start = new Date(range.value.start)
  const [sh, sm] = startTime.value.split(':').map(Number)
  start.setHours(sh, sm, 0, 0)
  
  const end = new Date(range.value.end)
  const [eh, em] = endTime.value.split(':').map(Number)
  end.setHours(eh, em, 0, 0)

  emit('apply', { start, end, activeTab: activeTab.value })
}

const summaryText = computed(() => {
  if (!range.value || !range.value.start || !range.value.end) return 'Vui lòng chọn ngày'
  
  const startDay = range.value.start.getDate().toString().padStart(2, '0')
  const startMonth = (range.value.start.getMonth() + 1).toString().padStart(2, '0')
  const startWeekday = range.value.start.getDay() === 0 ? 'CN' : `T${range.value.start.getDay() + 1}`
  
  const endDay = range.value.end.getDate().toString().padStart(2, '0')
  const endMonth = (range.value.end.getMonth() + 1).toString().padStart(2, '0')
  const endWeekday = range.value.end.getDay() === 0 ? 'CN' : `T${range.value.end.getDay() + 1}`

  return `${startTime.value} ${startWeekday}, ${startDay}/${startMonth} - ${endTime.value} ${endWeekday}, ${endDay}/${endMonth}`
})

const durationText = computed(() => {
  if (!range.value || !range.value.start || !range.value.end) return '0 ngày'
  
  const start = new Date(range.value.start)
  const [sh, sm] = startTime.value.split(':').map(Number)
  start.setHours(sh, sm, 0, 0)
  
  const end = new Date(range.value.end)
  const [eh, em] = endTime.value.split(':').map(Number)
  end.setHours(eh, em, 0, 0)

  if (activeTab.value === 'hourly') {
    const ms = end.getTime() - start.getTime()
    const hours = Math.max(0, Math.ceil(ms / (1000 * 60 * 60)))
    return `${hours} giờ`
  } else {
    // Thuê theo ngày thì tính theo ngày hoặc tròn ngày
    const ms = end.getTime() - start.getTime()
    const days = Math.max(1, Math.ceil(ms / (1000 * 60 * 60 * 24)))
    return `${days} ngày`
  }
})

watch(() => props.isOpen, (val) => {
  if (val) {
    if (props.initialStart && props.initialEnd) {
      range.value = {
        start: new Date(props.initialStart),
        end: new Date(props.initialEnd)
      }
      startTime.value = `${props.initialStart.getHours().toString().padStart(2, '0')}:${props.initialStart.getMinutes().toString().padStart(2, '0')}`
      endTime.value = `${props.initialEnd.getHours().toString().padStart(2, '0')}:${props.initialEnd.getMinutes().toString().padStart(2, '0')}`
    }
  }
})
</script>

<style>
/* Custom styling for v-calendar to match brand */
.custom-calendar {
  --vc-color-green-50: #f0fdf4;
  --vc-color-green-100: #dcfce7;
  --vc-color-green-200: #bbf7d0;
  --vc-color-green-300: #86efac;
  --vc-color-green-400: #4ade80;
  --vc-color-green-500: #22c55e;
  --vc-color-green-600: #1e4e57; /* using project's deep green brand */
  --vc-font-family: inherit;
  font-weight: 600;
}
.vc-day {
  min-height: 40px;
}
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.25s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>

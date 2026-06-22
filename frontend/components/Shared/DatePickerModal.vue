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
            <!-- THUÊ THEO NGÀY -->
            <div v-if="activeTab === 'daily'">
              <!-- Calendar -->
              <div class="flex justify-center mb-6">
                <ClientOnly>
                  <VDatePicker 
                    v-model.range="range" 
                    :columns="columns"
                    :step="1"
                    color="green"
                    :min-date="new Date()"
                    :disabled-dates="disabledDatesFormatted"
                    borderless
                    expanded
                    class="custom-calendar"
                    @dayclick="onDayClick"
                  />
                </ClientOnly>
              </div>

              <!-- Time Selectors -->
              <div class="flex items-center gap-4">
                <div class="flex-1 bg-white border border-slate-200 rounded-xl p-3 focus-within:border-brand-primary focus-within:ring-1 focus-within:ring-brand-primary transition-all relative cursor-pointer">
                  <div class="min-w-0">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nhận xe</label>
                    <div class="text-base font-bold text-slate-800">{{ startTime }}</div>
                  </div>
                  <select v-model="startTime" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                  </select>
                </div>

                <div class="flex-shrink-0 text-slate-400">
                  <Icon name="lucide:arrow-right-circle" class="w-6 h-6" />
                </div>

                <div class="flex-1 bg-white border border-slate-200 rounded-xl p-3 focus-within:border-brand-primary focus-within:ring-1 focus-within:ring-brand-primary transition-all relative cursor-pointer">
                  <div class="min-w-0">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Trả xe</label>
                    <div class="text-base font-bold text-slate-800">{{ endTime }}</div>
                  </div>
                  <select v-model="endTime" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- THUÊ THEO GIỜ -->
            <div v-else class="flex flex-col gap-4">
              <!-- Row 1: Ngày bắt đầu & Giờ nhận xe -->
              <div class="flex items-center gap-4">
                <!-- Ngày bắt đầu -->
                <div @click="isCalendarOpen = !isCalendarOpen" class="flex-1 bg-white border border-slate-200 rounded-xl p-3 hover:border-brand-primary transition-all cursor-pointer flex justify-between items-center relative">
                  <div class="min-w-0">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Ngày bắt đầu</label>
                    <div class="text-base font-bold text-slate-800">{{ formattedSingleDate }}</div>
                  </div>
                  <Icon name="lucide:chevron-down" class="w-5 h-5 text-slate-500 flex-shrink-0" />
                </div>

                <!-- Giờ nhận xe -->
                <div class="flex-1 bg-white border border-slate-200 rounded-xl p-3 focus-within:border-brand-primary focus-within:ring-1 focus-within:ring-brand-primary transition-all flex justify-between items-center relative cursor-pointer">
                  <div class="flex-grow min-w-0">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Giờ nhận xe</label>
                    <div class="text-base font-bold text-slate-800">{{ startTime }}</div>
                  </div>
                  <Icon name="lucide:chevron-down" class="w-5 h-5 text-slate-500 flex-shrink-0" />
                  <select v-model="startTime" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                  </select>
                </div>
              </div>

              <!-- Row 2: Calendar hiển thị khi click Ngày bắt đầu -->
              <div v-if="isCalendarOpen" class="border border-slate-200 rounded-2xl p-4 bg-slate-50/50 flex justify-center transition-all duration-300">
                <ClientOnly>
                  <VDatePicker 
                    v-model="singleDate" 
                    :columns="columns"
                    :step="1"
                    color="green"
                    :min-date="new Date()"
                    :disabled-dates="disabledDatesFormatted"
                    borderless
                    expanded
                    class="custom-calendar"
                    @dayclick="onDayClick"
                  />
                </ClientOnly>
              </div>

              <!-- Row 3: Thời gian thuê -->
              <div class="bg-white border border-slate-200 rounded-xl p-3 focus-within:border-brand-primary focus-within:ring-1 focus-within:ring-brand-primary transition-all flex justify-between items-center w-full relative cursor-pointer">
                <div class="flex-grow min-w-0">
                  <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Thời gian thuê</label>
                  <div class="text-base font-bold text-slate-800">
                    {{ rentDuration }} giờ <span class="text-xs font-normal text-slate-500">(kết thúc: {{ getEndTimeTextForDuration(rentDuration) }})</span>
                  </div>
                </div>
                <Icon name="lucide:chevron-down" class="w-5 h-5 text-slate-500 flex-shrink-0" />
                <select v-model="rentDuration" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                  <option v-for="hours in durationOptions" :key="hours" :value="hours">
                    {{ hours }} giờ (kết thúc: {{ getEndTimeTextForDuration(hours) }})
                  </option>
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

const { showToast } = useToast()

const props = defineProps({
  isOpen: Boolean,
  initialStart: Date,
  initialEnd: Date,
  disabledDates: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['close', 'apply'])

const activeTab = ref('daily')
const range = ref({
  start: props.initialStart || new Date(),
  end: props.initialEnd || new Date(Date.now() + 86400000 * 2)
})

const startTime = ref('21:00')
const endTime = ref('20:00')

// Hourly mode state
const singleDate = ref<Date | null>(props.initialStart || new Date())
const rentDuration = ref<number>(4)
const isCalendarOpen = ref(false)

const formattedSingleDate = computed(() => {
  if (!singleDate.value) return ''
  const d = singleDate.value
  return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth()+1).toString().padStart(2, '0')}/${d.getFullYear()}`
})

const durationOptions = Array.from({ length: 24 }).map((_, i) => i + 1)

// Close calendar when date is selected
watch(singleDate, () => {
  isCalendarOpen.value = false
})

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

const getEndTimeTextForDuration = (hours: number) => {
  if (!singleDate.value || !startTime.value) return ''
  const start = new Date(singleDate.value)
  const [sh, sm] = startTime.value.split(':').map(Number)
  start.setHours(sh, sm, 0, 0)
  
  const end = new Date(start.getTime() + hours * 3600000)
  const eh = end.getHours().toString().padStart(2, '0')
  const em = end.getMinutes().toString().padStart(2, '0')
  const ed = end.getDate().toString().padStart(2, '0')
  const emonth = (end.getMonth() + 1).toString().padStart(2, '0')
  const eyear = end.getFullYear()
  return `${eh}:${em} ${ed}/${emonth}/${eyear}`
}

const close = () => {
  emit('close')
}

// Computed: normalize disabledDates to Date objects for VDatePicker
const disabledDatesFormatted = computed(() => {
  if (!props.disabledDates || props.disabledDates.length === 0) return []
  return props.disabledDates.map((range: any) => ({
    start: range.start instanceof Date ? range.start : new Date(String(range.start).replace(' ', 'T')),
    end: range.end instanceof Date ? range.end : new Date(String(range.end).replace(' ', 'T')),
  }))
})

const isDayDisabled = (date: Date): boolean => {
  const t = date.getTime()
  return disabledDatesFormatted.value.some((range: any) => {
    const s = range.start.getTime()
    const e = range.end.getTime()
    // check if date falls on or between start and end (compare by day only)
    const dayStart = new Date(range.start); dayStart.setHours(0,0,0,0)
    const dayEnd = new Date(range.end); dayEnd.setHours(23,59,59,999)
    const checkDate = new Date(date); checkDate.setHours(12,0,0,0)
    return checkDate.getTime() >= dayStart.getTime() && checkDate.getTime() <= dayEnd.getTime()
  })
}

const onDayClick = (day: any) => {
  if (day.isDisabled) {
    showToast('Ngày này xe đã được đặt trước. Vui lòng chọn ngày khác!', 'error')
  }
}

const hasOverlap = (start: Date, end: Date) => {
  if (!disabledDatesFormatted.value || disabledDatesFormatted.value.length === 0) return false;
  
  const startMs = start.getTime();
  const endMs = end.getTime();
  
  return disabledDatesFormatted.value.some((range: any) => {
    if (!range.start || !range.end) return false;
    const rangeStart = range.start.getTime();
    const rangeEnd = range.end.getTime();
    return startMs <= rangeEnd && rangeStart <= endMs;
  });
};

const apply = () => {
  if (activeTab.value === 'daily') {
    if (!range.value || !range.value.start || !range.value.end) {
      showToast("Vui lòng chọn ngày nhận và ngày trả", "error")
      return
    }
    
    const start = new Date(range.value.start)
    const [sh, sm] = startTime.value.split(':').map(Number)
    start.setHours(sh, sm, 0, 0)
    
    const end = new Date(range.value.end)
    const [eh, em] = endTime.value.split(':').map(Number)
    end.setHours(eh, em, 0, 0)

    if (hasOverlap(start, end)) {
      showToast("Khoảng thời gian bạn chọn có ngày xe đã bận. Vui lòng chọn lịch khác.", "error")
      return
    }

    emit('apply', { start, end, activeTab: activeTab.value })
  } else {
    if (!singleDate.value) {
      showToast("Vui lòng chọn ngày bắt đầu", "error")
      return
    }
    
    const start = new Date(singleDate.value)
    const [sh, sm] = startTime.value.split(':').map(Number)
    start.setHours(sh, sm, 0, 0)
    
    const end = new Date(start.getTime() + rentDuration.value * 3600000)

    if (hasOverlap(start, end)) {
      showToast("Khoảng thời gian bạn chọn có ngày xe đã bận. Vui lòng chọn lịch khác.", "error")
      return
    }
    
    emit('apply', { start, end, activeTab: activeTab.value })
  }
}

const summaryText = computed(() => {
  if (activeTab.value === 'daily') {
    if (!range.value || !range.value.start || !range.value.end) return 'Vui lòng chọn ngày'
    
    const startDay = range.value.start.getDate().toString().padStart(2, '0')
    const startMonth = (range.value.start.getMonth() + 1).toString().padStart(2, '0')
    const startWeekday = range.value.start.getDay() === 0 ? 'CN' : `T${range.value.start.getDay() + 1}`
    
    const endDay = range.value.end.getDate().toString().padStart(2, '0')
    const endMonth = (range.value.end.getMonth() + 1).toString().padStart(2, '0')
    const endWeekday = range.value.end.getDay() === 0 ? 'CN' : `T${range.value.end.getDay() + 1}`

    return `${startTime.value} ${startWeekday}, ${startDay}/${startMonth} - ${endTime.value} ${endWeekday}, ${endDay}/${endMonth}`
  } else {
    if (!singleDate.value) return 'Vui lòng chọn ngày'
    
    const start = new Date(singleDate.value)
    const [sh, sm] = startTime.value.split(':').map(Number)
    start.setHours(sh, sm, 0, 0)
    
    const end = new Date(start.getTime() + rentDuration.value * 3600000)
    
    const startDay = start.getDate().toString().padStart(2, '0')
    const startMonth = (start.getMonth() + 1).toString().padStart(2, '0')
    const startWeekday = start.getDay() === 0 ? 'CN' : `T${start.getDay() + 1}`
    
    const endDay = end.getDate().toString().padStart(2, '0')
    const endMonth = (end.getMonth() + 1).toString().padStart(2, '0')
    const endWeekday = end.getDay() === 0 ? 'CN' : `T${end.getDay() + 1}`
    
    const eh = end.getHours().toString().padStart(2, '0')
    const em = end.getMinutes().toString().padStart(2, '0')
    
    return `${startTime.value} ${startWeekday}, ${startDay}/${startMonth} - ${eh}:${em} ${endWeekday}, ${endDay}/${endMonth}`
  }
})

const durationText = computed(() => {
  if (activeTab.value === 'daily') {
    if (!range.value || !range.value.start || !range.value.end) return '0 ngày'
    
    const start = new Date(range.value.start)
    const [sh, sm] = startTime.value.split(':').map(Number)
    start.setHours(sh, sm, 0, 0)
    
    const end = new Date(range.value.end)
    const [eh, em] = endTime.value.split(':').map(Number)
    end.setHours(eh, em, 0, 0)

    const ms = end.getTime() - start.getTime()
    const days = Math.max(1, Math.ceil(ms / (1000 * 60 * 60 * 24)))
    return `${days} ngày`
  } else {
    return `${rentDuration.value} giờ`
  }
})

watch(() => props.isOpen, (val) => {
  if (val) {
    if (props.initialStart && props.initialEnd) {
      const start = new Date(props.initialStart)
      const end = new Date(props.initialEnd)
      
      const isSameDay = start.getDate() === end.getDate() &&
                        start.getMonth() === end.getMonth() &&
                        start.getFullYear() === end.getFullYear()
      
      if (isSameDay) {
        activeTab.value = 'hourly'
        singleDate.value = start
        startTime.value = `${start.getHours().toString().padStart(2, '0')}:${start.getMinutes().toString().padStart(2, '0')}`
        const diffMs = end.getTime() - start.getTime()
        const hours = Math.round(diffMs / (1000 * 60 * 60))
        rentDuration.value = hours > 0 ? hours : 4
      } else {
        activeTab.value = 'daily'
        range.value = {
          start: start,
          end: end
        }
        startTime.value = `${start.getHours().toString().padStart(2, '0')}:${start.getMinutes().toString().padStart(2, '0')}`
        endTime.value = `${end.getHours().toString().padStart(2, '0')}:${end.getMinutes().toString().padStart(2, '0')}`
      }
    } else {
      singleDate.value = new Date()
      startTime.value = '21:00'
      rentDuration.value = 4
      range.value = {
        start: new Date(),
        end: new Date(Date.now() + 86400000 * 2)
      }
      startTime.value = '21:00'
      endTime.value = '20:00'
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

/* Visually mark disabled/busy days - v-calendar v3 uses .vc-disabled class */
.custom-calendar .vc-day-content.vc-disabled {
  color: #ef4444 !important;
  text-decoration: line-through !important;
  opacity: 1 !important;
  cursor: not-allowed !important;
  background-color: #fee2e2 !important;
  border-radius: 50%;
  --vc-day-content-disabled-color: #ef4444;
}

/* Style the day cell background for disabled days */
.custom-calendar .vc-day:has(.vc-day-content.vc-disabled) {
  background-color: #fff1f2 !important;
  position: relative;
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

<template>
  <div
    class="rounded-[10px] bg-[#FAF6F0] shadow-xl border border-white/40 p-2 lg:p-3 relative z-40"
  >
    <div
      class="grid grid-cols-1 lg:grid-cols-[1.5fr_auto_1fr_auto_1fr_auto_1.1fr_auto] items-center"
    >

      <!-- Địa điểm nhận xe -->
      <div class="flex items-center gap-3 px-4 lg:px-3 xl:px-5 py-4 hover:bg-black/5 rounded-2xl transition cursor-pointer">
        <Icon
          name="heroicons:map-pin"
          class="h-6 w-6 text-slate-700 shrink-0"
        />
        <div class="flex flex-col flex-grow min-w-0">
          <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500/80 mb-0.5">
            Địa điểm nhận xe
          </label>
          <div class="relative">
            <input
              v-model="location"
              type="text"
              placeholder="Chọn hoặc nhập địa điểm..."
              class="w-full bg-transparent text-[14px] font-bold text-slate-800 focus:outline-none placeholder:text-slate-400/80 pr-5"
              @focus="showSuggestions = true"
              @blur="handleBlur"
            />
            <Icon
              name="heroicons:chevron-down"
              class="h-4 w-4 text-slate-500 absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none"
            />

            <!-- Suggestions list -->
            <div
              v-if="showSuggestions && filteredProvinces.length > 0"
              class="absolute left-0 top-full z-50 mt-2 max-h-[140px] overflow-y-auto rounded-xl border border-slate-200 bg-white py-1 shadow-lg min-w-[220px] lg:min-w-[260px]"
            >
              <button
                v-for="province in filteredProvinces"
                :key="province"
                type="button"
                @mousedown="selectProvince(province)"
                class="w-full px-4 py-2 text-left text-sm font-semibold text-slate-700 hover:bg-slate-100 transition z-50"
              >
                {{ province }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Divider -->
      <div class="hidden lg:block w-px bg-slate-300/60 h-10 self-center mx-1"></div>

      <!-- Ngày nhận xe -->
      <div
        @click="isDatePickerOpen=true"
        class="flex items-center gap-3 px-4 lg:px-3 xl:px-5 py-4 hover:bg-black/5 rounded-2xl transition cursor-pointer"
      >
        <Icon
          name="heroicons:calendar"
          class="h-6 w-6 text-slate-700 shrink-0"
        />
        <div class="flex flex-col flex-grow min-w-0">
          <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500/80 mb-0.5">
            Ngày nhận xe
          </label>
          <div class="flex items-center justify-between relative">
            <span class="text-[14px] font-bold text-slate-800 truncate pr-5">
              {{ formattedStart || 'Chọn ngày' }}
            </span>
            <Icon
              name="heroicons:chevron-down"
              class="h-4 w-4 text-slate-500 absolute right-0 pointer-events-none"
            />
          </div>
        </div>
      </div>

      <!-- Divider -->
      <div class="hidden lg:block w-px bg-slate-300/60 h-10 self-center mx-1"></div>

      <!-- Ngày trả xe -->
      <div
        @click="isDatePickerOpen=true"
        class="flex items-center gap-3 px-4 lg:px-3 xl:px-5 py-4 hover:bg-black/5 rounded-2xl transition cursor-pointer"
      >
        <Icon
          name="heroicons:calendar"
          class="h-6 w-6 text-slate-700 shrink-0"
        />
        <div class="flex flex-col flex-grow min-w-0">
          <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500/80 mb-0.5">
            Ngày trả xe
          </label>
          <div class="flex items-center justify-between relative">
            <span class="text-[14px] font-bold text-slate-800 truncate pr-5">
              {{ formattedEnd || 'Chọn ngày' }}
            </span>
            <Icon
              name="heroicons:chevron-down"
              class="h-4 w-4 text-slate-500 absolute right-0 pointer-events-none"
            />
          </div>
        </div>
      </div>

      <!-- Divider -->
      <div class="hidden lg:block w-px bg-slate-300/60 h-10 self-center mx-1"></div>

      <!-- Loại xe -->
      <div
        class="flex items-center gap-3 px-4 lg:px-3 xl:px-5 py-4 hover:bg-black/5 rounded-2xl transition cursor-pointer"
      >
        <Icon
          name="lucide:car"
          class="h-6 w-6 text-slate-700 shrink-0"
        />
        <div class="flex flex-col flex-grow min-w-0">
          <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500/80 mb-0.5">
            Loại xe
          </label>
          <div class="flex items-center justify-between relative">
            <select
              v-model="carType"
              class="w-full bg-transparent text-[14px] font-bold text-slate-800 focus:outline-none cursor-pointer pr-5 appearance-none"
            >
              <option value="">Tất cả loại xe</option>
              <option value="4">Xe 4 chỗ</option>
              <option value="7">Xe 7 chỗ</option>
              <option value="ev">Xe điện</option>
            </select>
            <Icon
              name="heroicons:chevron-down"
              class="h-4 w-4 text-slate-500 absolute right-0 pointer-events-none"
            />
          </div>
        </div>
      </div>

      <!-- Button -->
      <div
        class="flex items-center justify-center p-2 lg:pl-4"
      >
        <button
          @click="handleSearch"
          class="flex h-14 w-full lg:w-auto items-center justify-center gap-2 rounded-2xl bg-brand-dark px-8 text-[16px] font-bold text-white shadow-md transition hover:scale-[1.02] hover:bg-slate-900"
        >
          <Icon
            name="heroicons:magnifying-glass"
            class="h-5 w-5"
          />
          Tìm xe
        </button>
      </div>
    </div>
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
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from '#app'
import DatePickerModal from '~/components/Shared/DatePickerModal.vue'

const route = useRoute()
const router = useRouter()

const location = ref((route.query.location as string) || '')
const carType = ref((route.query.carType as string) || '')

const isDatePickerOpen = ref(false)
const provinces = ref<string[]>([])
const showSuggestions = ref(false)

const loadProvinces = async () => {
  try {
    const res = await fetch('https://provinces.open-api.vn/api/v2/')
    if (!res.ok) throw new Error('API request failed')
    const data = await res.json()
    provinces.value = data.map((p: any) => {
      // Dọn dẹp tên dạng "Thành phố Cần Thơ" -> "Cần Thơ"
      let name = p.name.replace(/^(Thành phố|Tỉnh)\s+/i, '')
      if (name === 'Thừa Thiên Huế') return 'Huế'
      return name
    })
  } catch (error) {
    console.error('Error fetching provinces:', error)
    // Fallback list of provinces
    provinces.value = [
      'Hồ Chí Minh', 'Hà Nội', 'Đà Nẵng', 'Bình Dương', 'Đồng Nai',
      'Cần Thơ', 'Hải Phòng', 'Khánh Hòa', 'Lâm Đồng', 'Quảng Ninh',
      'Huế', 'Bà Rịa - Vũng Tàu', 'Đà Lạt', 'Nha Trang'
    ]
  }
}

const filteredProvinces = computed(() => {
  const query = location.value.toLowerCase().trim()
  if (!query) return provinces.value
  return provinces.value.filter(p => p.toLowerCase().includes(query))
})

const selectProvince = (province: string) => {
  location.value = province
  showSuggestions.value = false
}

const handleBlur = () => {
  setTimeout(() => {
    showSuggestions.value = false
  }, 200)
}

onMounted(() => {
  loadProvinces()
})

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
select {
    appearance: none;
    background: none;
    border: none;
}
select:focus {
    outline: none;
}
</style>

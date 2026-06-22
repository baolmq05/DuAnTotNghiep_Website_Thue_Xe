<template>
  <div class="w-full min-h-screen bg-[#f8fafc] flex flex-col">
    <HeaderProfile />

    <div class="w-full flex-1 mt-[90px] p-4 md:p-6 space-y-6 max-w-7xl mx-auto">

      <div class="flex items-center justify-between">
        <NuxtLink to="/my-cars"
          class="inline-flex items-center gap-2 text-slate-500 hover:text-[#53cf84] transition-colors text-sm font-semibold">
          <Icon name="lucide:chevron-left" size="20" />
          Quay lại
        </NuxtLink>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Lịch trình xe chi tiết</h1>
        <div class="w-20"></div>
      </div>

      <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm border border-gray-100 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Xem lịch từ ngày:</label>
            <div class="relative">
              <input type="date" v-model="filter.fromDate"
                class="w-full text-sm rounded-xl border border-gray-200 px-3 py-2.5 outline-none focus:border-[#53cf84] font-medium text-slate-700 appearance-none" />
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Đến ngày:</label>
            <input type="date" v-model="filter.toDate" :min="filter.fromDate"
              class="w-full text-sm rounded-xl border border-gray-200 px-3 py-2.5 outline-none focus:border-[#53cf84] font-medium text-slate-700 appearance-none" />
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Tìm kiếm nhanh:</label>
            <div class="relative">
              <input v-model="filter.search" type="text" placeholder="Tên xe hoặc biển số..."
                class="w-full text-sm rounded-xl border border-gray-200 px-3 py-2.5 pl-9 outline-none focus:border-[#53cf84] text-slate-700" />
              <Icon name="lucide:search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size="16" />
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5 sm:items-end pt-2">
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Hãng xe:</label>
            <div class="relative">
              <select v-model="filter.brandFilter"
                class="w-full appearance-none text-sm rounded-xl border border-gray-200 px-3 py-2.5 outline-none focus:border-[#53cf84] font-medium bg-white text-slate-700">
                <option value="all">Tất cả các hãng</option>
                <option v-for="brand in availableBrands" :key="brand" :value="brand">{{ brand }}</option>
              </select>
              <Icon name="ic:round-keyboard-arrow-down"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="18" />
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Dòng xe:</label>
            <div class="relative">
              <select v-model="filter.typeFilter"
                class="w-full appearance-none text-sm rounded-xl border border-gray-200 px-3 py-2.5 outline-none focus:border-[#53cf84] font-medium bg-white text-slate-700">
                <option value="all">Tất cả dòng xe</option>
                <option v-for="type in availableTypes" :key="type" :value="type">{{ type }}</option>
              </select>
              <Icon name="ic:round-keyboard-arrow-down"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="18" />
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Trạng thái hiện tại:</label>
            <div class="relative">
              <select v-model="filter.statusFilter"
                class="w-full appearance-none text-sm rounded-xl border border-gray-200 px-3 py-2.5 outline-none focus:border-[#53cf84] font-medium bg-white text-slate-700">
                <option value="all">Tất cả trạng thái lịch</option>
                <option value="1">Đang đi chuyến</option>
                <option value="2">Chờ giao xe / Lịch hẹn</option>
                <option value="0">Đang trống lịch / Đã hoàn thành / Hủy</option>
              </select>
              <Icon name="ic:round-keyboard-arrow-down"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="18" />
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Sắp xếp theo lịch:</label>
            <div class="relative">
              <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 flex items-center">
                <Icon name="lucide:bar-chart-3" size="16" />
              </div>
              <select v-model="filter.sortBy"
                class="w-full appearance-none text-sm rounded-xl border border-gray-200 pl-9 pr-8 py-2.5 outline-none focus:border-[#53cf84] font-medium bg-white text-slate-700">
                <option value="default">Trống lịch (Mặc định)</option>
                <option value="brand">Hãng xe</option>
                <option value="type">Dòng xe</option>
                <option value="name">Tên xe</option>
                <option value="busy">Bận lịch</option>
              </select>
              <Icon name="ic:round-keyboard-arrow-down"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="18" />
            </div>
          </div>

          <button @click="clearFilters" :disabled="!hasActiveFilters"
            class="flex items-center justify-center gap-1.5 rounded-xl border py-2.5 px-4 text-sm font-semibold transition-all duration-200 w-full h-[42px]"
            :class="hasActiveFilters
              ? 'border-red-200 bg-red-50 text-red-600 shadow-sm hover:bg-red-100 cursor-pointer active:scale-95'
              : 'border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed opacity-60'">
            <Icon name="lucide:x" size="16" />
            Xóa lọc
          </button>
        </div>

        <div class="pt-3 border-t border-gray-100 flex flex-wrap gap-4 text-xs font-medium text-slate-600">
          <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Đang diễn ra
          </div>
          <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-500"></span> Chưa bắt đầu (Chờ
            giao)</div>
          <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-indigo-500"></span> Đã hoàn thành
          </div>
          <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-purple-500"></span> Người dùng hủy
          </div>
          <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-rose-500"></span> Chủ xe hủy</div>
          <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-gray-200"></span> Trống lịch</div>
        </div>
      </div>

      <ClientOnly>
        <div v-if="pending"
          class="w-full text-center py-12 text-slate-500 font-medium text-sm flex flex-col items-center justify-center gap-2">
          <Icon name="line-md:loading-twotone-loop" size="28" class="text-[#53cf84]" />
          Đang đồng bộ lịch trình xe...
        </div>

        <div v-else-if="filteredCars && filteredCars.length > 0"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="car in filteredCars" :key="car.licensePlate"
            class="group bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">

            <div class="relative overflow-hidden aspect-[16/10] shrink-0">
              <img :src="car.image || 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=600&q=80'"
                :alt="car.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent"></div>

              <div class="absolute top-3 left-3">
                <span
                  class="bg-slate-900/60 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider flex items-center gap-1">
                  <Icon name="lucide:calendar" class="w-3 h-3" /> Lịch tự lái
                </span>
              </div>

              <div class="absolute top-3 right-3">
                <span
                  class="bg-slate-900/70 backdrop-blur-sm text-white text-xs font-mono font-bold px-2.5 py-1 rounded-lg border border-white/20 tracking-wide">
                  {{ car.licensePlate }}
                </span>
              </div>
            </div>

            <div class="p-4 flex flex-col flex-grow justify-between space-y-4">
              <div>
                <div class="flex justify-between items-start mb-1">
                  <h3
                    class="font-bold text-base text-slate-800 line-clamp-1 group-hover:text-[#53cf84] transition-colors">
                    {{ car.name }}
                  </h3>
                  <span :class="getStatusBadgeClass(car.status, car.note)"
                    class="text-[10px] font-bold px-2 py-0.5 rounded uppercase shrink-0 ml-2 whitespace-nowrap">
                    {{ getStatusText(car.status, car.note) }}
                  </span>
                </div>

                <div class="flex items-center gap-2 text-xs text-slate-500 mb-2 font-medium">
                  <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md">{{ car.brand }}</span>
                  <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-md text-[11px]">{{ car.type }}</span>
                </div>

                <p class="text-xs text-slate-400 flex items-center gap-1 mb-3">
                  <Icon name="lucide:map-pin" size="14" /> {{ car.location }}
                </p>

                <div class="bg-slate-50 rounded-xl p-3 space-y-2 border border-slate-100">
                  <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1">
                    <Icon name="lucide:clock" size="12" class="text-[#53cf84]" /> Khoảng lịch ghi nhận:
                  </p>

                  <div v-if="car.bookedDates" class="space-y-1">
                    <div class="flex justify-between text-xs">
                      <span class="text-slate-600 font-medium">Thời gian bận:</span>
                      <span class="font-semibold text-slate-800 bg-white px-1.5 py-0.5 rounded border text-[11px]">
                        {{ car.bookedDates }}
                      </span>
                    </div>
                    <div class="flex justify-between text-xs">
                      <span class="text-slate-600 font-medium">Trạng thái:</span>
                      <span class="font-bold text-slate-700">{{ car.note }}</span>
                    </div>
                  </div>
                  <div v-else class="text-xs text-emerald-600 font-medium flex items-center gap-1 py-1">
                    <Icon name="lucide:check-circle" size="14" /> Trống lịch toàn bộ tuần này
                  </div>
                </div>
              </div>

              <div class="space-y-1.5">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mô phỏng lịch tuần hiện tại:
                </p>
                <div class="grid grid-cols-7 gap-1 w-full">
                  <div v-for="(day, idx) in car.weekTimeline" :key="idx"
                    class="flex flex-col items-center w-full min-w-0">
                    <div :class="day.color"
                      class="h-6 w-full rounded-md flex items-center justify-center text-[10px] font-bold text-white relative group/tip cursor-pointer">
                      {{ day.label }}
                      <span
                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover/tip:block bg-slate-800 text-white text-[9px] px-2 py-0.5 rounded whitespace-nowrap z-20">
                        {{ day.tooltip }}
                      </span>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 mt-1 select-none">{{ day.date_num }}</span>
                  </div>
                </div>
              </div>

              <div class="pt-2 border-t border-slate-100 flex gap-2">
                <button @click="openTripDetail(car)"
                  class="flex-1 py-2 bg-[#53cf84] text-white text-xs font-bold rounded-xl hover:bg-[#43bd73] transition-colors shadow-sm">
                  Chi tiết lịch
                </button>
              </div>
            </div>

          </div>
        </div>

        <div v-else class="bg-white rounded-2xl p-12 text-center border border-gray-100 flex flex-col items-center">
          <Icon name="lucide:calendar-x" class="text-gray-300 mb-3" size="48" />
          <p class="text-slate-500 font-medium text-sm">Không tìm thấy xe nào có lịch trình phù hợp.</p>
        </div>
      </ClientOnly>

    </div>
  </div>

  <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeModal"></div>

    <div
      class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-xl border border-slate-100 z-10 relative space-y-5 animate-in fade-in zoom-in-95 duration-200">

      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <div class="flex items-center gap-2">
          <Icon name="lucide:calendar-days" class="text-[#53cf84]" size="22" />
          <h3 class="text-lg font-bold text-slate-800">Chi tiết lịch trình chuyến đi</h3>
        </div>
        <button @click="closeModal"
          class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-50 transition-colors">
          <Icon name="lucide:x" size="20" />
        </button>
      </div>

      <div v-if="selectedTrip" class="space-y-4">
        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 space-y-2.5">
          <div class="flex justify-between items-center">
            <span class="text-sm font-bold text-slate-800">{{ selectedTrip.name }}</span>
            <span
              class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200">
              {{ selectedTrip.licensePlate }}
            </span>
          </div>

          <div class="h-px bg-slate-200/60 my-1"></div>

          <div class="flex justify-between text-xs font-medium">
            <span class="text-slate-500">Khoảng thời gian:</span>
            <span class="text-slate-800 font-semibold">{{ selectedTrip.bookedDates || 'N/A' }}</span>
          </div>
          <div class="flex justify-between text-xs font-medium">
            <span class="text-slate-500">Tình trạng bận:</span>
            <span class="text-slate-800 font-bold">{{ selectedTrip.note }}</span>
          </div>
        </div>

        <div class="space-y-2">
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Mô phỏng chu kỳ lịch chuyến đi:</p>
          <div class="flex flex-wrap gap-2 bg-slate-50 p-4 rounded-xl border border-slate-100">
            <div v-for="(day, idx) in selectedTrip.modalTimeline" :key="idx"
              class="flex flex-col items-center min-w-[45px] flex-1">
              <div :class="day.color"
                class="h-8 w-full rounded-lg flex items-center justify-center text-xs font-bold text-white relative group/tip cursor-pointer transition-all px-2">
                {{ day.label }}
                <span
                  class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover/tip:block bg-slate-800 text-white text-[9px] px-2 py-0.5 rounded whitespace-nowrap z-30">
                  {{ day.tooltip }} ({{ day.full_date }})
                </span>
              </div>
              <span class="text-xs font-bold text-slate-500 mt-1.5 select-none">{{ day.date_num }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { reactive, ref, computed, watch } from 'vue';
import HeaderProfile from '~/components/Profile/HeaderProfile.vue';
 

// Định nghĩa kiểu dữ liệu cho lịch trình xe
interface WeekDay {
  label: string;
  date_num: number;
  color: string;
  tooltip: string;
  full_date?: string;
}

interface CarCalendar {
  name: string;
  licensePlate: string;
  image: string;
  brand: string;
  type: string;
  location: string;
  status: number;
  note: string;
  bookedDates: string | null;
  weekTimeline: WeekDay[];
  modalTimeline?: WeekDay[];
}

const isModalOpen = ref(false);
const selectedTrip = ref<CarCalendar | null>(null);

const openTripDetail = (carData: CarCalendar) => {
  selectedTrip.value = carData;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  selectedTrip.value = null;
};

definePageMeta({
  layout: "default",
});

const config = useRuntimeConfig();
const baseApi = config.public.apiBase || 'http://127.0.0.1:8000/api';
const token = useCookie('USER_TOKEN').value || '';

if (!token && process.client) {
  useRouter().push('/');
}

// Hàm định dạng ngày tháng thành chuỗi YYYY-MM-DD
const formatDateString = (date: Date) => {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
};

// Hàm lấy ngày thứ 2 và chủ nhật của tuần hiện tại dựa trên ngày hôm nay
const getCalendarWeeks = () => {
  const current = new Date();
  const day = current.getDay();
  const diffToMonday = current.getDate() - day + (day === 0 ? -6 : 1);
  const monday = new Date(current.setDate(diffToMonday));

  const sunday = new Date(monday);
  sunday.setDate(monday.getDate() + 6);

  return { monday, sunday };
};

const { monday, sunday } = getCalendarWeeks();

// bộ lọc ban đầu 
const filter = reactive({
  fromDate: formatDateString(monday),
  toDate: formatDateString(sunday),
  statusFilter: 'all',
  brandFilter: 'all',
  typeFilter: 'all',
  sortBy: 'default',
  search: ''
});

const rawBrands = ref<string[]>([]);
const rawTypes = ref<string[]>([]);

// Sử dụng useLazyFetch để lấy dữ liệu lịch trình xe dựa trên bộ lọc
const { data: filteredCars, refresh, pending } = await useLazyFetch<CarCalendar[]>(() => `${baseApi}/car-calendar`, {
  params: filter,
  watch: [filter],
  server: false,
  immediate: !!token,
  headers: {
    Authorization: `Bearer ${token}`
  },
  onResponse({ response }) {
    if (response.status === 200 && response._data) {
      const data = response._data as CarCalendar[];
      if (Array.isArray(data)) {
        if (rawBrands.value.length === 0 || filter.brandFilter === 'all') {
          const brands = data.map((car: CarCalendar) => car.brand).filter(Boolean);
          rawBrands.value = [...new Set(brands)] as string[];
        }
        if (rawTypes.value.length === 0 || filter.typeFilter === 'all') {
          const types = data.map((car: CarCalendar) => car.type).filter(Boolean);
          rawTypes.value = [...new Set(types)] as string[];
        }
      }
    }
  }
});

const availableBrands = computed(() => rawBrands.value);

const availableTypes = computed(() => {
  if (filter.brandFilter === 'all') {
    return rawTypes.value;
  }
  if (!filteredCars.value || !Array.isArray(filteredCars.value)) return [];

  const typesByBrand = filteredCars.value
    .filter((car: CarCalendar) => car.brand === filter.brandFilter)
    .map((car: CarCalendar) => car.type)
    .filter(Boolean);

  return [...new Set(typesByBrand)] as string[];
});

// Điều kiện check xem người dùng có đang tương tác bộ lọc hay không (Bỏ qua lịch ngày tháng)
const hasActiveFilters = computed(() =>
  filter.search !== '' || filter.statusFilter !== 'all' || filter.brandFilter !== 'all' || filter.typeFilter !== 'all' || filter.sortBy !== 'default'
)

// xóa tất cả trừ lịch
function clearFilters() {
  filter.search = ''
  filter.statusFilter = 'all'
  filter.brandFilter = 'all'
  filter.typeFilter = 'all'
  filter.sortBy = 'default'
}

watch(() => filter.brandFilter, () => {
  filter.typeFilter = 'all';
});

const getStatusText = (status: number, note: string) => {
  if (status === 1) return 'Đang đi chuyến';
  if (status === 2) return 'Chờ giao xe';
  if (note.includes('hoàn thành')) return 'Đã xong cuốc';
  if (note.includes('hủy')) return 'Đã hủy lịch';
  return 'Trống lịch';
};

const getStatusBadgeClass = (status: number, note: string) => {
  if (status === 1) return 'bg-emerald-50 text-emerald-600 border border-emerald-200';
  if (status === 2) return 'bg-amber-50 text-amber-600 border border-amber-200';
  if (note.includes('hoàn thành')) return 'bg-indigo-50 text-indigo-600 border border-indigo-200';
  if (note.includes('hủy')) return 'bg-rose-50 text-rose-600 border border-rose-200';
  return 'bg-slate-100 text-slate-600 border border-slate-200';
};
</script>
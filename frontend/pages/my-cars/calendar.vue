<template>
  <div class="w-full min-h-screen bg-[#f8fafc] flex flex-col">
    <!-- Header hệ thống -->
    <HeaderProfile />

    <!-- Container chính dịch xuống dưới Header [90px] -->
    <div class="w-full flex-1 mt-[90px] p-4 md:p-6 space-y-6 max-w-7xl mx-auto">
      
      <!-- THANH TRÊN CÙNG: NÚT BACK & TIÊU ĐỀ TRANG -->
      <div class="flex items-center justify-between">
        <NuxtLink 
          to="/my-cars" 
          class="inline-flex items-center gap-2 text-slate-500 hover:text-[#53cf84] transition-colors text-sm font-semibold"
        >
          <Icon name="lucide:chevron-left" size="20" />
          Quay lại
        </NuxtLink>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Lịch trình xe chi tiết</h1>
        <div class="w-20"></div>
      </div>

      <!-- KHỐI BỘ LỌC THỜI GIAN, HÃNG, DÒNG XE & SẮP XẾP -->
      <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm border border-gray-100 space-y-4">
        <!-- Hàng 1: Thời gian & Tìm kiếm -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Từ ngày -->
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Xem lịch từ ngày:</label>
            <div class="relative">
              <input
                type="date"
                v-model="filter.fromDate"
                class="w-full text-sm rounded-xl border border-gray-200 px-3 py-2.5 outline-none focus:border-[#53cf84] font-medium text-slate-700 appearance-none"
              />
            </div>
          </div>

          <!-- Đến ngày -->
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Đến ngày:</label>
            <input
              type="date"
              v-model="filter.toDate"
              :min="filter.fromDate"
              class="w-full text-sm rounded-xl border border-gray-200 px-3 py-2.5 outline-none focus:border-[#53cf84] font-medium text-slate-700 appearance-none"
            />
          </div>

          <!-- Tìm kiếm xe -->
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Tìm kiếm nhanh:</label>
            <div class="relative">
              <input v-model="filter.search" type="text" placeholder="Tên xe hoặc biển số..." class="w-full text-sm rounded-xl border border-gray-200 px-3 py-2.5 pl-9 outline-none focus:border-[#53cf84] text-slate-700" />
              <Icon name="lucide:search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size="16" />
            </div>
          </div>

          <!-- Sắp xếp theo thiết kế image_56b3a0.png và image_56b3e0.png -->
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Sắp xếp theo lịch:</label>
            <div class="relative">
              <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 flex items-center">
                <Icon name="lucide:bar-chart-3" size="16" />
              </div>
              <select v-model="filter.sortBy" class="w-full appearance-none text-sm rounded-xl border border-gray-200 pl-9 pr-8 py-2.5 outline-none focus:border-[#53cf84] font-medium bg-white text-slate-700">
                <option value="default">Trống lịch (Mặc định)</option>
                <option value="brand">Hãng xe</option>
                <option value="type">Dòng xe</option>
                <option value="name">Tên xe</option>
                <option value="busy">Bận lịch</option>
              </select>
              <Icon name="ic:round-keyboard-arrow-down" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="18" />
            </div>
          </div>
        </div>

        <!-- Hàng 2: Bộ lọc phân loại xe chuyên sâu -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
          <!-- Bộ lọc Hãng xe -->
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Hãng xe:</label>
            <div class="relative">
              <select v-model="filter.brandFilter" class="w-full appearance-none text-sm rounded-xl border border-gray-200 px-3 py-2.5 outline-none focus:border-[#53cf84] font-medium bg-white text-slate-700">
                <option value="all">Tất cả các hãng</option>
                <option value="Toyota">Toyota</option>
                <option value="Honda">Honda</option>
                <option value="Mitsubishi">Mitsubishi</option>
                <option value="Ford">Ford</option>
              </select>
              <Icon name="ic:round-keyboard-arrow-down" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="18" />
            </div>
          </div>

          <!-- Bộ lọc Dòng xe chuẩn theo image_56b3e0.png -->
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Dòng xe:</label>
            <div class="relative">
              <select v-model="filter.typeFilter" class="w-full appearance-none text-sm rounded-xl border border-gray-200 px-3 py-2.5 outline-none focus:border-[#53cf84] font-medium bg-white text-slate-700">
                <option value="all">Tất cả dòng xe</option>
                <option value="4 chỗ (Mini)">4 chỗ (Mini)</option>
                <option value="4 chỗ (Sedan)">4 chỗ (Sedan)</option>
                <option value="5 chỗ (CUV Gầm cao)">5 chỗ (CUV Gầm cao)</option>
                <option value="7 chỗ (SUV Gầm cao)">7 chỗ (SUV Gầm cao)</option>
                <option value="7 chỗ (MPV Gầm thấp)">7 chỗ (MPV Gầm thấp)</option>
                <option value="Bán tải">Bán tải</option>
                <option value="Minivan">Minivan</option>
              </select>
              <Icon name="ic:round-keyboard-arrow-down" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="18" />
            </div>
          </div>

          <!-- Bộ lọc Trạng thái lịch ngày hiện tại -->
          <div class="space-y-1.5">
            <label class="text-xs font-semibold text-slate-500">Trạng thái hiện tại:</label>
            <div class="relative">
              <select v-model="filter.statusFilter" class="w-full appearance-none text-sm rounded-xl border border-gray-200 px-3 py-2.5 outline-none focus:border-[#53cf84] font-medium bg-white text-slate-700">
                <option value="all">Tất cả trạng thái lịch</option>
                <option value="1">Đang đi chuyến</option>
                <option value="2">Chờ giao xe</option>
                <option value="0">Đang trống lịch</option>
              </select>
              <Icon name="ic:round-keyboard-arrow-down" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="18" />
            </div>
          </div>
        </div>

        <!-- KHỐI CHÚ GIẢI MÀU LỊCH TRÌNH -->
        <div class="pt-3 border-t border-gray-100 flex flex-wrap gap-4 text-xs font-medium text-slate-600">
          <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Đang đi chuyến (Drivo)</div>
          <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-500"></span> Khách hẹn / Chờ giao xe</div>
          <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-gray-200"></span> Ngày trống lịch</div>
        </div>
      </div>

      <!-- LƯỚI THẺ XE PHÙ HỢP LOGIC LỊCH TRÌNH -->
      <div v-if="filteredCars.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="car in filteredCars" 
          :key="car.licensePlate"
          class="group bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full"
        >
          <!-- 1. Đầu thẻ: Ảnh và biển số xe -->
          <div class="relative overflow-hidden aspect-[16/10] shrink-0">
            <img :src="car.image" :alt="car.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent"></div>
            
            <div class="absolute top-3 left-3">
              <span class="bg-slate-900/60 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider flex items-center gap-1">
                <Icon name="lucide:calendar" class="w-3 h-3" /> Lịch tự lái
              </span>
            </div>

            <div class="absolute top-3 right-3">
              <span class="bg-slate-900/70 backdrop-blur-sm text-white text-xs font-mono font-bold px-2.5 py-1 rounded-lg border border-white/20 tracking-wide">
                {{ car.licensePlate }}
              </span>
            </div>
          </div>

          <!-- 2. Thân thẻ: Thông tin và Lịch đặt -->
          <div class="p-4 flex flex-col flex-grow justify-between space-y-4">
            <div>
              <!-- Badge Tên xe và phân loại phân khúc -->
              <div class="flex justify-between items-start mb-1">
                <h3 class="font-bold text-base text-slate-800 line-clamp-1 group-hover:text-[#53cf84] transition-colors">
                  {{ car.name }}
                </h3>
                <span :class="getStatusBadgeClass(car.status)" class="text-[10px] font-bold px-2 py-0.5 rounded uppercase shrink-0 ml-2">
                  {{ getStatusText(car.status) }}
                </span>
              </div>
              
              <!-- Hiển thị Hãng xe & Dòng xe ngay trên thông tin phụ của thẻ lịch -->
              <div class="flex items-center gap-2 text-xs text-slate-500 mb-2 font-medium">
                <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md">{{ car.brand }}</span>
                <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-md text-[11px]">{{ car.type }}</span>
              </div>
              
              <p class="text-xs text-slate-400 flex items-center gap-1 mb-3">
                <Icon name="lucide:map-pin" size="14" /> {{ car.location }}
              </p>

              <!-- HIỂN THỊ CHI TIẾT KHOẢNG LỊCH BẬN -->
              <div class="bg-slate-50 rounded-xl p-3 space-y-2 border border-slate-100">
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1">
                  <Icon name="lucide:clock" size="12" class="text-[#53cf84]" /> Khoảng lịch ghi nhận:
                </p>
                
                <div v-if="car.bookedDates" class="space-y-1">
                  <div class="flex justify-between text-xs">
                    <span class="text-slate-600 font-medium">Thời gian bận:</span>
                    <span class="font-semibold text-slate-800 bg-white px-1.5 py-0.5 rounded border text-[11px]">{{ car.bookedDates }}</span>
                  </div>
                  <div class="flex justify-between text-xs">
                    <span class="text-slate-600 font-medium">Lý do bận:</span>
                    <span class="font-bold text-slate-700">{{ car.note }}</span>
                  </div>
                </div>
                <div v-else class="text-xs text-emerald-600 font-medium flex items-center gap-1 py-1">
                  <Icon name="lucide:check-circle" size="14" /> Trống lịch toàn bộ tuần này
                </div>
              </div>
            </div>

            <!-- 3. Mini Timeline trực quan (Mô phỏng chuỗi ngày trong tuần) -->
            <div class="space-y-1.5">
              <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mô phỏng lịch tuần tới:</p>
              <div class="grid grid-cols-7 gap-1">
                <div 
                  v-for="(day, idx) in car.weekTimeline" 
                  :key="idx" 
                  :class="day.color"
                  class="h-6 rounded-md flex items-center justify-center text-[10px] font-bold text-white relative group/tip cursor-pointer"
                >
                  {{ day.label }}
                  <!-- Tooltip khi di chuột vào từng ngày -->
                  <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover/tip:block bg-slate-800 text-white text-[9px] px-2 py-0.5 rounded whitespace-nowrap z-20">
                    {{ day.tooltip }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Nút xử lý cập nhật lịch xe trực tiếp -->
            <div class="pt-2 border-t border-slate-100 flex gap-2">
              <button class="flex-1 py-2 border border-gray-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-gray-50 transition-colors">
                Khóa lịch xe
              </button>
              <button class="flex-1 py-2 bg-[#53cf84] text-white text-xs font-bold rounded-xl hover:bg-[#43bd73] transition-colors shadow-sm">
                Chi tiết lịch
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Không tìm thấy xe -->
      <div v-else class="bg-white rounded-2xl p-12 text-center border border-gray-100 flex flex-col items-center">
        <Icon name="lucide:calendar-x" class="text-gray-300 mb-3" size="48" />
        <p class="text-slate-500 font-medium text-sm">Không tìm thấy xe nào có lịch trình phù hợp.</p>
      </div>

    </div>
  </div>
</template>

<script lang="ts" setup>
import { reactive, computed } from 'vue';
import HeaderProfile from '~/components/Profile/HeaderProfile.vue';

definePageMeta({
  layout: "default",
});

// Mock Data gốc được đồng bộ hóa thêm trường `brand` (Hãng xe) và `type` (Dòng xe) chuẩn hóa theo yêu cầu thiết kế của Sếp
const rawCarsFromSeeder = [
  {
    name: 'Toyota Camry',
    brand: 'Toyota',
    type: '4 chỗ (Sedan)',
    license_plate: '30A-12345',
    manufacture_year: '2020-01-01',
    status: 1, // Đang đi chuyến
    booked_dates: '15/06 - 20/06/2026',
    note: 'Khách hàng: Nguyễn Văn A thuê',
    timeline: [
      { label: 'T2', color: 'bg-emerald-500', tooltip: 'Đang đi chuyến' },
      { label: 'T3', color: 'bg-emerald-500', tooltip: 'Đang đi chuyến' },
      { label: 'T4', color: 'bg-emerald-500', tooltip: 'Đang đi chuyến' },
      { label: 'T5', color: 'bg-emerald-500', tooltip: 'Đang đi chuyến' },
      { label: 'T6', color: 'bg-emerald-500', tooltip: 'Đang đi chuyến' },
      { label: 'T7', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'CN', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
    ]
  },
  {
    name: 'Honda Civic',
    brand: 'Honda',
    type: '4 chỗ (Mini)',
    license_plate: '30B-54321',
    manufacture_year: '2019-01-01',
    status: 2, // Chờ giao xe
    booked_dates: '16/06 - 18/06/2026',
    note: 'Khách hẹn: Trần Thị B đặt',
    timeline: [
      { label: 'T2', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T3', color: 'bg-amber-500', tooltip: 'Chờ giao xe' },
      { label: 'T4', color: 'bg-amber-500', tooltip: 'Chờ giao xe' },
      { label: 'T5', color: 'bg-amber-500', tooltip: 'Chờ giao xe' },
      { label: 'T6', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T7', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'CN', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
    ]
  },
  {
    name: 'Toyota Wigo',
    brand: 'Toyota',
    type: '4 chỗ (Mini)',
    license_plate: '30C-67890',
    manufacture_year: '2021-01-01',
    status: 0, // Trống lịch hoàn toàn
    booked_dates: null,
    note: 'Sẵn sàng đón khách',
    timeline: [
      { label: 'T2', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T3', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T4', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T5', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T6', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T7', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'CN', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
    ]
  },
  {
    name: 'Mitsubishi Xpander',
    brand: 'Mitsubishi',
    type: '7 chỗ (MPV Gầm thấp)',
    license_plate: '30D-98765',
    manufacture_year: '2022-01-01',
    status: 3, // Khóa lịch bảo dưỡng
    booked_dates: '14/06 - 15/06/2026',
    note: 'Chủ xe khóa: Bảo dưỡng định kỳ',
    timeline: [
      { label: 'T2', color: 'bg-blue-500', tooltip: 'Bảo dưỡng định kỳ' },
      { label: 'T3', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T4', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T5', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T6', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T7', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'CN', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
    ]
  },
  {
    name: 'Ford Ranger Wildtrak',
    brand: 'Ford',
    type: 'Bán tải',
    license_plate: '30E-11223',
    manufacture_year: '2023-01-01',
    status: 0,
    booked_dates: null,
    note: 'Sẵn sàng đón khách',
    timeline: [
      { label: 'T2', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T3', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T4', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T5', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T6', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'T7', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
      { label: 'CN', color: 'bg-gray-200 text-slate-600', tooltip: 'Trống lịch' },
    ]
  }
];

type DateRange = {
  start: Date;
  end: Date;
};

const parseVietnameseDate = (value: string) => {
  const [day, month, year] = value.split('/').map(Number);

  if (!day || !month || !year) return null;

  return new Date(year, month - 1, day);
};

const parseIsoDate = (value: string) => {
  if (!value) return null;

  const parsed = new Date(`${value}T00:00:00`);
  return Number.isNaN(parsed.getTime()) ? null : parsed;
};

const parseBookedDateRange = (value: string | null): DateRange | null => {
  if (!value) return null;

  const parts = value.split('-').map(part => part.trim());
  const startText = parts[0];
  const endText = parts[1];
  if (!startText || !endText) return null;

  const start = parseVietnameseDate(startText);
  const end = parseVietnameseDate(endText);

  if (!start || !end) return null;

  return { start, end };
};

const rangesOverlap = (left: DateRange, right: DateRange) => {
  return left.start <= right.end && left.end >= right.start;
};

// State quản lý bộ lọc trang lịch (Đã bổ sung brandFilter, typeFilter, sortBy)
const filter = reactive({
  fromDate: '2026-06-15',
  toDate: '2026-07-12',
  statusFilter: 'all',
  brandFilter: 'all',
  typeFilter: 'all',
  sortBy: 'default',
  search: ''
});

// Ánh xạ dữ liệu tương thích giao diện UI thẻ xe
const formattedCars = computed(() => {
  const sampleImages = [
    'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=600',
    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=600',
    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=600',
    'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600',
    'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=600'
  ];

  return rawCarsFromSeeder.map((car, index) => {
    return {
      name: car.name,
      brand: car.brand,
      type: car.type,
      licensePlate: car.license_plate,
      image: sampleImages[index] || sampleImages[0],
      location: 'Quận 1, TP. Hồ Chí Minh',
      manufactureYear: car.manufacture_year.split('-')[0],
      status: car.status,
      bookedDates: car.booked_dates,
      note: car.note,
      bookedRange: parseBookedDateRange(car.booked_dates),
      weekTimeline: car.timeline
    };
  });
});

// Xử lý logic lọc (Lọc chuỗi tìm kiếm, lọc hãng xe, lọc loại dòng xe, lọc trạng thái lịch)
const filteredCars = computed(() => {
  const selectedFrom = parseIsoDate(filter.fromDate);
  const selectedTo = parseIsoDate(filter.toDate);
  const selectedRange = selectedFrom && selectedTo && selectedFrom <= selectedTo
    ? { start: selectedFrom, end: selectedTo }
    : null;

  let list = formattedCars.value.filter(car => {
    const matchesSearch = car.name.toLowerCase().includes(filter.search.toLowerCase()) || 
                          car.licensePlate.toLowerCase().includes(filter.search.toLowerCase());
    
    const matchesStatus = filter.statusFilter === 'all' || car.status === parseInt(filter.statusFilter);
    const matchesBrand = filter.brandFilter === 'all' || car.brand === filter.brandFilter;
    const matchesType = filter.typeFilter === 'all' || car.type === filter.typeFilter;
    const matchesDateRange = !selectedRange || !car.bookedRange || rangesOverlap(car.bookedRange, selectedRange);

    return matchesSearch && matchesStatus && matchesBrand && matchesType && matchesDateRange;
  });

  // Xử lý logic sắp xếp dựa vào lựa chọn filter.sortBy (Mô phỏng hình image_56b3a0.png)
  if (filter.sortBy === 'name') {
    list.sort((a, b) => a.name.localeCompare(b.name));
  } else if (filter.sortBy === 'brand') {
    list.sort((a, b) => a.brand.localeCompare(b.brand));
  } else if (filter.sortBy === 'type') {
    list.sort((a, b) => a.type.localeCompare(b.type));
  } else if (filter.sortBy === 'busy') {
    // Đẩy xe đang có trạng thái bận (status != 0) lên trước
    list.sort((a, b) => b.status - a.status);
  } else if (filter.sortBy === 'default') {
    // Trống lịch mặc định: Đẩy các xe có status = 0 (Trống lịch) lên đầu danh sách
    list.sort((a, b) => {
      if (a.status === 0 && b.status !== 0) return -1;
      if (a.status !== 0 && b.status === 0) return 1;
      return 0;
    });
  }

  return list;
});

// Helper ánh xạ text trạng thái
const getStatusText = (status: number) => {
  if (status === 1) return 'Đang đi chuyến';
  if (status === 2) return 'Chờ giao xe';
  if (status === 3) return 'Đang khóa lịch';
  return 'Trống lịch';
};

// Helper ánh xạ màu badge
const getStatusBadgeClass = (status: number) => {
  if (status === 1) return 'bg-emerald-50 text-emerald-600';
  if (status === 2) return 'bg-amber-50 text-amber-600';
  if (status === 3) return 'bg-blue-50 text-blue-600';
  return 'bg-slate-100 text-slate-600';
};
</script>
<template>
  <CommonLoadingOverlay :loading="loading" text="Đang tải dữ liệu..." />
  <div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div class="flex gap-3 shrink-0">
        <NuxtLink to="/car-register"
          class="px-5 py-3 rounded-2xl bg-[#1e4e57] hover:bg-[#163a41] text-white flex items-center gap-2 font-bold text-sm shadow-md shadow-[#1e4e57]/15 transition-all duration-200 cursor-pointer active:scale-[0.98] transform">
          <Icon name="lucide:plus" class="w-4.5 h-4.5" />
          Đăng ký xe mới
        </NuxtLink>
      </div>

      <!-- Segmented Status Filter Pills -->
      <div class="flex overflow-x-auto scrollbar-hide gap-2 pb-2 -mx-4 px-4 md:mx-0 md:px-0 w-full md:w-auto">
        <button v-for="opt in statusOptions" :key="opt.value" @click="selectedStatus = opt.value"
          class="px-4 py-2.5 rounded-2xl text-sm font-semibold whitespace-nowrap transition-all flex items-center gap-2 border shadow-sm hover:scale-[1.02] transform active:scale-[0.98] duration-200"
          :class="selectedStatus === opt.value
            ? 'bg-[#1e4e57] border-[#1e4e57] text-white'
            : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'">
          <span class="w-2 h-2 rounded-full" :class="opt.dotColor"></span>
          <span>{{ opt.label }}</span>
          <span class="text-xs px-1.5 py-0.5 rounded-lg ml-0.5"
            :class="selectedStatus === opt.value ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">
            {{ opt.count }}
          </span>
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="i in 3" :key="i"
        class="animate-pulse bg-white rounded-3xl h-[440px] border border-slate-100 p-5 flex flex-col justify-between shadow-sm">
        <div class="bg-slate-100 h-48 w-full rounded-2xl mb-4"></div>
        <div class="space-y-3 flex-grow">
          <div class="h-4 bg-slate-100 rounded w-1/4"></div>
          <div class="h-6 bg-slate-100 rounded w-3/4"></div>
          <div class="h-4 bg-slate-100 rounded w-1/2"></div>
        </div>
        <div class="h-10 bg-slate-100 rounded-2xl w-full mt-4"></div>
      </div>
    </div>

    <!-- Loaded Cars -->
    <div v-else-if="filteredCars.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="car in filteredCars" :key="car.licensePlate"
        class="group bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col h-full transform translate-z-0">
        <!-- Card Image section -->
        <div class="relative overflow-hidden aspect-[16/10] shrink-0">
          <img :src="car.image" :alt="car.name"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />

          <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent"></div>

          <div class="absolute top-3.5 left-3.5 flex flex-col gap-1.5 z-10">
            <span
              class="bg-slate-900/60 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm uppercase tracking-wider flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="m21 2-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0 1.5 1.5M15.5 7.5 14 6">
                </path>
              </svg>
              {{ car.rentalType }}
            </span>
          </div>

          <div class="absolute top-3.5 right-3.5 z-10">
            <span
              class="bg-gradient-to-r from-slate-100 to-slate-200 text-slate-800 text-xs font-mono font-extrabold px-3 py-1 rounded-lg border border-slate-300 shadow-[0_2px_4px_rgba(0,0,0,0.08)] tracking-wider">
              {{ car.licensePlate }}
            </span>
          </div>

          <div
            class="absolute bottom-3 right-3 z-10 flex items-center gap-1.5 bg-slate-900/40 backdrop-blur-md rounded-full px-3 py-1 border border-white/10 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="text-emerald-400">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span class="text-xs font-semibold">Đời {{ car.manufactureYear }}</span>
          </div>
        </div>

        <!-- Card Body -->
        <div class="p-5 flex flex-col flex-grow justify-between">
          <div class="flex flex-col flex-grow justify-start">

            <!-- Status Badge & Toggle -->
            <div class="flex items-center justify-between gap-1.5 mb-3 min-h-[24px]">
              <div>
                <span v-if="car.status === 1"
                  class="inline-flex items-center gap-1.5 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-full uppercase tracking-wider">
                  Đang hoạt động
                </span>
                <span v-else-if="car.status === 2"
                  class="inline-flex items-center gap-1.5 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-100 px-2.5 py-1 rounded-full uppercase tracking-wider">
                  Chờ phê duyệt
                </span>
                <span v-else-if="car.status === 3"
                  class="inline-flex items-center gap-1.5 text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-100 px-2.5 py-1 rounded-full uppercase tracking-wider">
                  Bị từ chối
                </span>
                <span v-else-if="car.status === 0"
                  class="inline-flex items-center gap-1.5 text-[10px] font-bold text-slate-500 bg-slate-50 border border-slate-100 px-2.5 py-1 rounded-full uppercase tracking-wider">
                  Dừng hoạt động
                </span>
              </div>

              <!-- Toggle switch for Active / Inactive -->
              <div v-if="car.status === 1 || car.status === 0" class="flex items-center gap-1.5 shrink-0">
                <span class="text-[10px] text-slate-400 font-semibold">{{ car.status === 1 ? 'Hoạt động' : 'Tạm dừng' }}</span>
                <button
                  @click.stop="toggleStatus(car)"
                  :disabled="car.statusChanging || car.hasOngoingTrip"
                  class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                  :class="[
                    car.status === 1 ? 'bg-emerald-500' : 'bg-slate-200', 
                    (car.statusChanging || car.hasOngoingTrip) ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                  ]"
                >
                  <span
                    class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                    :class="car.status === 1 ? 'translate-x-4' : 'translate-x-0'"
                  ></span>
                </button>
              </div>
            </div>

            <!-- Title & Location -->
            <div class="flex justify-between items-start gap-3">
              <div class="flex-grow min-w-0">
                <h3
                  class="font-extrabold text-lg text-slate-800 line-clamp-1 group-hover:text-[#1e4e57] transition-colors">
                  {{ car.name }}
                </h3>
                <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="shrink-0 text-slate-400">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  <span class="truncate font-medium">{{ car.location }}</span>
                </p>
              </div>

              <div
                class="flex items-center gap-1 bg-slate-50 text-slate-600 px-2.5 py-1 rounded-xl text-xs font-semibold shrink-0 mt-0.5 border border-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="text-[#1e4e57]">
                  <path d="M12 22a7 7 0 0 0 7-7c0-4.3-7-11-7-11S5 10.7 5 15a7 7 0 0 0 7 7z"></path>
                </svg>
                {{ car.fuelConsumption }}L/100km
              </div>
            </div>
          </div>

          <!-- Specs, Progress bar, Revenue -->
          <div class="mt-4 space-y-4">
            <!-- Specification Grid -->
            <div class="grid grid-cols-3 gap-2 bg-slate-50/70 p-2.5 rounded-2xl text-center border border-slate-50/50">
              <div class="flex flex-col items-center justify-center py-1 border-r border-slate-200/50">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="text-[#1e4e57] mb-1.5">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                  <circle cx="9" cy="7" r="4"></circle>
                  <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                  <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span class="text-xs font-bold text-slate-800">{{ car.seats }} chỗ</span>
              </div>
              <div class="flex flex-col items-center justify-center py-1 border-r border-slate-200/50 min-w-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="text-[#1e4e57] mb-1.5">
                  <circle cx="12" cy="12" r="3"></circle>
                  <path
                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z">
                  </path>
                </svg>
                <span class="text-xs font-bold text-slate-800 truncate w-full px-1">{{ car.transmission }}</span>
              </div>
              <div class="flex flex-col items-center justify-center py-1 min-w-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="text-[#1e4e57] mb-1.5">
                  <line x1="3" y1="22" x2="15" y2="22"></line>
                  <line x1="4" y1="9" x2="14" y2="9"></line>
                  <path d="M14 22V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v18"></path>
                  <path d="M14 13h2a2 2 0 0 1 2 2v7"></path>
                  <path d="M16 5.5a2.5 2.5 0 0 0 0 5"></path>
                </svg>
                <span class="text-xs font-bold text-slate-800 truncate w-full px-1">{{ car.fuel }}</span>
              </div>
            </div>

            <!-- Occupancy Rate Progress Bar -->
            <!-- <div class="space-y-1.5">
              <div class="flex justify-between items-center text-xs">
                <span class="text-slate-400 font-semibold">Hiệu suất thuê tháng này</span>
                <span class="font-bold text-[#1e4e57]">
                  {{ car.activeDays }}/30 ngày ({{ Math.round((car.activeDays / 30) * 100) }}%)
                </span>
              </div>
              <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200/20">
                <div 
                  class="bg-gradient-to-r from-[#1e4e57] to-[#5FCF86] h-full rounded-full transition-all duration-500"
                  :style="{ width: `${(car.activeDays / 30) * 100}%` }"
                ></div>
              </div>
            </div> -->

            <!-- Footer: Revenue -->
            <div class="flex justify-between items-center pt-3.5 border-t border-slate-100 mt-auto">
              <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Doanh thu tháng này</p>
                <p class="font-black text-[#1e4e57] mt-0.5 text-base lg:text-lg">
                  {{ formatPrice(car.revenue) }}
                </p>
              </div>
              <button v-if="car.hasOngoingTrip" @click="showToast('Xe đang có chuyến đi, không thể chỉnh sửa', 'error')"
                class="px-4 py-2 text-xs font-bold text-slate-400 bg-slate-100 rounded-xl cursor-not-allowed flex items-center gap-1.5 border border-slate-200 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 20h9"></path>
                  <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                </svg>
                Chỉnh sửa
              </button>
              <NuxtLink v-else :to="`/my-cars/edit/${car.id}`"
                class="px-4 py-2 text-xs font-bold text-white bg-[#1e4e57] hover:bg-[#163a41] rounded-xl transition duration-200 flex items-center gap-1.5 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 20h9"></path>
                  <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                </svg>
                Chỉnh sửa
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else
      class="min-h-[450px] flex flex-col items-center justify-center bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
      <div
        class="w-36 h-36 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="text-slate-300 animate-bounce">
          <path d="m21 8-2 2-1.5-3.7A2 2 0 0 0 15.64 5H8.36a2 2 0 0 0-1.86 1.3L5 10 3 8"></path>
          <path d="M17 14h.01"></path>
          <path d="M7 14h.01"></path>
          <rect width="18" height="8" x="3" y="10" rx="2"></rect>
          <path d="M5 18v2"></path>
          <path d="M19 18v2"></path>
        </svg>
      </div>

      <h2 class="mt-6 text-xl font-extrabold text-slate-800">Không tìm thấy xe nào</h2>
      <p class="text-slate-400 mt-2 text-sm text-center max-w-sm">Không có phương tiện nào thuộc bộ lọc này hoặc bạn
        chưa đăng ký xe.</p>

      <button @click="selectedStatus = 'all'"
        class="mt-6 px-6 py-3 rounded-2xl bg-[#1e4e57] text-white hover:bg-[#163a41] transition-all font-bold text-sm shadow-md hover:scale-[1.02] active:scale-[0.98] transform">
        Xem tất cả xe
      </button>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted } from 'vue';
import { myCarService, type Car } from '~/services/my_car.service';
import { BASE_URL } from '~/enviroment/enviroment';
import { useToast } from '~/composables/useToast';

definePageMeta({
  layout: "my-cars",
});

const { user } = useAuth();
const { showToast } = useToast();
const loading = ref(true);
const userCars = ref<Car[]>([]);

// State lưu trạng thái bộ lọc đang được chọn (Mặc định hiển thị "Tất cả")
const selectedStatus = ref<string>('all');

const statusOptions = computed(() => {
  return [
    { value: 'all', label: 'Tất cả', count: formattedCars.value.length, dotColor: 'bg-slate-400' },
    { value: '1', label: 'Đang hoạt động', count: formattedCars.value.filter(c => c.status === 1).length, dotColor: 'bg-emerald-500' },
    { value: '2', label: 'Chờ duyệt', count: formattedCars.value.filter(c => c.status === 2).length, dotColor: 'bg-amber-500' },
    { value: '3', label: 'Bị từ chối', count: formattedCars.value.filter(c => c.status === 3).length, dotColor: 'bg-rose-500' },
    { value: '0', label: 'Dừng hoạt động', count: formattedCars.value.filter(c => c.status === 0).length, dotColor: 'bg-gray-400' },
  ];
});

// Định dạng dữ liệu và giả lập báo cáo kinh doanh cho Chủ xe
const formattedCars = computed(() => {
  const sampleImages = [
    'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=600',
    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=600',
    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=600',
    'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600'
  ];

  return userCars.value.map((car, index) => {
    const computedRevenue = Number(car.revenue || 0);
    const activeDays = Number(car.trips_count || 0);

    // Lấy ảnh đại diện
    let carImage = sampleImages[index % 4];
    if (car.images && car.images.length > 0) {
      const primaryImg = car.images.find(img => img.is_thumbnail === 1) || car.images[0];
      const imgPath = primaryImg.image_url || '';
      if (imgPath.startsWith('http') || imgPath.startsWith('/')) {
        carImage = imgPath;
      } else if (imgPath) {
        carImage = `${BASE_URL}storage/${imgPath}`;
      }
    }

    return {
      id: car.id,
      name: car.name,
      licensePlate: car.license_plate,
      image: carImage,
      location: car.car_location ? car.car_location.address : 'Chưa cập nhật',
      seats: Number(car.seat_count),
      transmission: car.transmission,
      fuel: car.fuel_type,
      fuelConsumption: car.fuel_consumption,
      manufactureYear: car.manufacture_year ? car.manufacture_year.split('-')[0] : '2020',
      status: car.status, // Giữ nguyên giá trị số (0, 1, 2, 3) để hiển thị và lọc
      rentalType: 'Xe tự lái',
      revenue: computedRevenue,
      activeDays: activeDays,
      hasOngoingTrip: !!car.has_ongoing_trip,
      statusChanging: (car as any).statusChanging || false
    };
  });
});

// Xử lý logic lọc xe thời gian thực dựa vào giá trị v-model của <select>
const filteredCars = computed(() => {
  if (selectedStatus.value === 'all') {
    return formattedCars.value;
  }
  // Chuyển đổi value từ string của thẻ select về kiểu number để so sánh chuẩn xác với car.status
  return formattedCars.value.filter(car => car.status === parseInt(selectedStatus.value));
});

const formatPrice = (val: any) => {
  if (val === undefined || val === null) return "0đ";
  const num = Math.round(Number(val)) || 0;
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + "đ";
};

const handleEditOngoingTrip = () => {
  showToast('Xe đang có chuyến đi đang diễn ra, không thể thay đổi trạng thái hoặc chỉnh sửa thông tin xe.', 'error');
};

const toggleStatus = async (car: any) => {
  if (car.hasOngoingTrip) {
    showToast('Xe đang có chuyến đi đang diễn ra, không thể thay đổi trạng thái.', 'error');
    return;
  }

  const origCar = userCars.value.find(c => c.id === car.id);
  if (!origCar) return;

  (origCar as any).statusChanging = true;

  try {
    const res = await myCarService.toggleCarStatus(car.id);
    if (res.success && res.data) {
      origCar.status = res.data.status;
      showToast(res.message, 'success');
    } else {
      showToast(res.message || 'Có lỗi xảy ra khi thay đổi trạng thái.', 'error');
    }
  } catch (err: any) {
    console.error('Lỗi khi thay đổi trạng thái xe:', err);
    const errMsg = err?.data?.message || 'Không thể thay đổi trạng thái xe.';
    showToast(errMsg, 'error');
  } finally {
    (origCar as any).statusChanging = false;
  }
};

onMounted(async () => {
  if (!user.value) {
    navigateTo('/');
    return;
  }

  if (user.value.role_id !== 3 && user.value.role_id !== 1) {
    navigateTo('/profile');
    return;
  }

  try {
    const res = await myCarService.getCars({ user_id: user.value.id });
    if (res.success && res.data) {
      userCars.value = res.data;
    }
  } catch (err) {
    console.error('Lỗi khi tải danh sách xe của tôi:', err);
  } finally {
    loading.value = false;
  }
});
</script>
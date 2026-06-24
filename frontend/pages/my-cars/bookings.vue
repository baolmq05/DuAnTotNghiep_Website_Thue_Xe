<template>
  <div class="space-y-6">
    <!-- Header Page Description -->
    <div class="flex flex-col gap-1.5 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
      <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-[#1e4e57]">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" y1="13" x2="8" y2="13"></line>
          <line x1="16" y1="17" x2="8" y2="17"></line>
          <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        Duyệt yêu cầu thuê xe
      </h2>
      <p class="text-xs text-slate-400 font-medium">
        Danh sách các xe đang có khách hàng gửi yêu cầu thuê đến. Vui lòng xác nhận hoặc từ chối yêu cầu.
      </p>
    </div>

    <!-- Segmented Filter Tabs for Owner Bookings -->
    <div class="flex flex-wrap gap-2">
      <button
        v-for="tab in filterTabs"
        :key="tab.value"
        @click="activeFilter = tab.value"
        class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 border shadow-sm hover:scale-[1.02] transform active:scale-[0.98] duration-200"
        :class="activeFilter === tab.value 
          ? 'bg-[#1e4e57] border-[#1e4e57] text-white' 
          : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'"
      >
        <span class="w-1.5 h-1.5 rounded-full" :class="tab.dotColor"></span>
        <span>{{ tab.label }}</span>
        <span 
          class="text-[10px] px-1.5 py-0.5 rounded-lg ml-0.5"
          :class="activeFilter === tab.value ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'"
        >
          {{ tab.count }}
        </span>
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div v-for="i in 2" :key="i" class="animate-pulse bg-white rounded-3xl h-[320px] border border-slate-100 p-5 flex flex-col justify-between shadow-sm">
        <div class="flex gap-4">
          <div class="bg-slate-100 h-28 w-40 rounded-2xl shrink-0"></div>
          <div class="space-y-3 flex-grow py-2">
            <div class="h-4 bg-slate-100 rounded w-1/3"></div>
            <div class="h-6 bg-slate-100 rounded w-3/4"></div>
            <div class="h-4 bg-slate-100 rounded w-1/2"></div>
          </div>
        </div>
        <div class="h-1 bg-slate-100 rounded-full w-full my-4"></div>
        <div class="flex gap-3">
          <div class="h-10 bg-slate-100 rounded-xl flex-1"></div>
          <div class="h-10 bg-slate-100 rounded-xl flex-1"></div>
        </div>
      </div>
    </div>

    <!-- Loaded Trips -->
    <div v-else-if="filteredTrips.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div 
        v-for="trip in filteredTrips" 
        :key="trip.id"
        class="group bg-white rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between"
      >
        <!-- Card Core Info -->
        <div class="p-5 flex flex-col flex-grow">
          <!-- Car details & Image -->
          <div class="flex gap-4 items-start pb-4 border-b border-slate-100">
            <!-- Thumbnail Image -->
            <div class="relative w-36 h-24 rounded-2xl overflow-hidden shrink-0 border border-slate-100 bg-slate-50">
              <img :src="trip.car.image" :alt="trip.car.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              <span class="absolute bottom-1.5 left-1.5 bg-slate-900/70 backdrop-blur-sm text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow-sm">
                {{ trip.trip_type === 0 ? 'Theo ngày' : 'Theo km' }}
              </span>
            </div>

            <!-- Basic Text Info -->
            <div class="flex-grow min-w-0">
              <div class="flex items-center gap-1.5">
                <span class="bg-slate-100 text-slate-800 text-[10px] font-mono font-bold px-2 py-0.5 rounded border border-slate-200">
                  {{ trip.car.license_plate }}
                </span>
                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[9px] font-bold" :class="statusClass(trip.status)">
                  <span class="h-1.5 w-1.5 rounded-full" :class="statusDot(trip.status)"></span>
                  {{ statusLabel(trip.status) }}
                </span>
              </div>
              <h3 class="font-extrabold text-base text-slate-800 mt-2 line-clamp-1 group-hover:text-[#1e4e57] transition-colors">
                {{ trip.car.name }}
              </h3>
              <p class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 text-slate-400">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span class="truncate font-medium">{{ trip.car.location }}</span>
              </p>
            </div>
          </div>

          <!-- Trip schedules & details -->
          <div class="mt-4 space-y-3 flex-grow">
            <!-- Renter details -->
            <div class="bg-slate-50/60 p-3 rounded-2xl border border-slate-100 flex items-center justify-between">
              <div class="min-w-0">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Khách thuê</p>
                <p class="text-xs font-bold text-slate-800 mt-0.5 truncate">{{ trip.renter.name }}</p>
                <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ trip.renter.phone }}</p>
              </div>
              <NuxtLink to="/chats" class="p-2.5 bg-white hover:bg-slate-100 text-[#1e4e57] rounded-xl border border-slate-200 shadow-sm transition-all flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
              </NuxtLink>
            </div>

            <!-- Time breakdown -->
            <div class="grid grid-cols-2 gap-3 text-xs">
              <div class="bg-slate-50/40 p-2.5 rounded-xl border border-slate-100">
                <p class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-[#1e4e57]"></span> Nhận xe
                </p>
                <p class="font-bold text-slate-700 mt-1">{{ formatDate(trip.start_at) }}</p>
              </div>
              <div class="bg-slate-50/40 p-2.5 rounded-xl border border-slate-100">
                <p class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Trả xe
                </p>
                <p class="font-bold text-slate-700 mt-1">{{ formatDate(trip.end_at) }}</p>
              </div>
            </div>

            <!-- Duration & Cost row -->
            <div class="flex items-center justify-between pt-2">
              <div>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Thời gian thuê</p>
                <p class="text-xs font-bold text-slate-700 mt-0.5">{{ duration(trip.start_at, trip.end_at) }}</p>
              </div>
              <div class="text-right">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Tổng doanh thu nhận</p>
                <div class="flex items-center gap-1.5 justify-end mt-0.5">
                  <span class="text-base font-black text-[#1e4e57]">{{ formatCurrency(trip.cost - trip.discount_amount) }}</span>
                  <span v-if="trip.discount_amount > 0" class="text-[10px] text-slate-400 line-through">
                    {{ formatCurrency(trip.cost) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Confirm / Reject Action buttons (ONLY for Pending - status 5) -->
        <div v-if="trip.status === 5" class="p-5 pt-0 flex gap-3 border-t border-slate-100 bg-slate-50/20">
          <button 
            @click="openRejectDialog(trip)"
            class="flex-1 py-3 px-4 border border-rose-200 bg-rose-50 hover:bg-rose-100 active:scale-[0.98] transition-all text-xs font-bold text-rose-600 rounded-xl flex items-center justify-center gap-1.5 shadow-sm transform"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
            Từ chối cho thuê
          </button>
          
          <button 
            @click="confirmTrip(trip)"
            class="flex-1 py-3 px-4 bg-[#1e4e57] hover:bg-[#163a41] active:scale-[0.98] transition-all text-xs font-bold text-white rounded-xl flex items-center justify-center gap-1.5 shadow-sm shadow-[#1e4e57]/15 transform"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            Xác nhận cho thuê
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="min-h-[350px] flex flex-col items-center justify-center bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
      <div class="w-28 h-28 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300">
          <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
          <line x1="16" y1="2" x2="16" y2="6"></line>
          <line x1="8" y1="2" x2="8" y2="6"></line>
          <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
      </div>

      <h3 class="mt-5 text-lg font-bold text-slate-800">Không tìm thấy yêu cầu nào</h3>
      <p class="text-slate-400 mt-1.5 text-xs text-center max-w-sm">Không có chuyến đi nào thuộc bộ lọc này.</p>
    </div>

    <!-- Reject Reason Dialog Modal -->
    <Transition name="fade">
      <div v-if="showRejectModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all p-6 space-y-4">
          <!-- Header -->
          <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-rose-500">
                <octagon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></octagon>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
              </svg>
              Lý do từ chối
            </h3>
            <button @click="closeRejectDialog" class="p-1 hover:bg-slate-100 rounded-full transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>

          <!-- Body Description -->
          <div v-if="selectedTripForReject" class="space-y-2">
            <p class="text-xs text-slate-500 font-medium">
              Vui lòng nhập lý do từ chối yêu cầu thuê xe <span class="font-bold text-slate-800">{{ selectedTripForReject.car.name }}</span> của khách hàng <span class="font-bold text-slate-800">{{ selectedTripForReject.renter.name }}</span>.
            </p>

            <textarea 
              v-model="rejectReason" 
              rows="4" 
              placeholder="Nhập lý do chi tiết (ví dụ: Xe đang bảo dưỡng, bận lịch đột xuất...)"
              class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-700 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 placeholder:text-slate-400"
            ></textarea>
          </div>

          <!-- Footer Actions -->
          <div class="flex gap-3 pt-2">
            <button 
              @click="closeRejectDialog" 
              class="flex-1 py-2.5 border border-slate-200 bg-white hover:bg-slate-50 active:scale-[0.98] transition-all text-xs font-bold text-slate-600 rounded-xl"
            >
              Hủy
            </button>
            <button 
              @click="submitRejection" 
              class="flex-1 py-2.5 bg-rose-500 hover:bg-rose-600 active:scale-[0.98] transition-all text-xs font-bold text-white rounded-xl shadow-sm shadow-rose-500/20"
            >
              Xác nhận từ chối
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { carService } from '~/services/car.service';
import { useToast } from '~/composables/useToast';

definePageMeta({
  layout: "my-cars",
});

const { showToast } = useToast();
const loading = ref(true);
const ownerTrips = ref<any[]>([]);
const activeFilter = ref<'pending' | 'active' | 'history'>('pending');

// Modal States
const showRejectModal = ref(false);
const selectedTripForReject = ref<any | null>(null);
const rejectReason = ref('');

// Computed filter tabs with counts
const filterTabs = computed(() => {
  return [
    { 
      value: 'pending', 
      label: 'Yêu cầu mới (Chờ duyệt)', 
      count: ownerTrips.value.filter(t => t.status === 5).length, 
      dotColor: 'bg-amber-500' 
    },
    { 
      value: 'active', 
      label: 'Đang & Sắp diễn ra', 
      count: ownerTrips.value.filter(t => t.status === 0 || t.status === 1).length, 
      dotColor: 'bg-blue-500' 
    },
    { 
      value: 'history', 
      label: 'Lịch sử cho thuê', 
      count: ownerTrips.value.filter(t => t.status === 2 || t.status === 3 || t.status === 4).length, 
      dotColor: 'bg-slate-400' 
    },
  ];
});

// Dữ liệu mẫu (Mock data) cho danh sách chuyến cho thuê của chủ xe
const mockTrips = [
  {
    id: -1,
    cost: 3600000,
    discount_amount: 300000,
    status: 5,
    trip_type: 0,
    start_at: "2026-06-26 08:00:00",
    end_at: "2026-06-29 18:00:00",
    car: {
      name: "Hyundai Tucson 2021",
      license_plate: "30F-888.88",
      image: "https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=600",
      location: "Cầu Giấy, Hà Nội"
    },
    renter: {
      name: "Trần Minh Hoàng",
      phone: "0912 *** 345"
    }
  },
  {
    id: -2,
    cost: 1200000,
    discount_amount: 0,
    status: 5,
    trip_type: 1,
    start_at: "2026-06-27 10:00:00",
    end_at: "2026-06-27 22:00:00",
    car: {
      name: "VinFast VF8 2023",
      license_plate: "29A-666.66",
      image: "https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=600",
      location: "Quận 1, TP. Hồ Chí Minh"
    },
    renter: {
      name: "Lê Thị Mai",
      phone: "0934 *** 789"
    }
  },
  {
    id: -3,
    cost: 900000,
    discount_amount: 0,
    status: 0,
    trip_type: 0,
    start_at: "2026-06-24 14:00:00",
    end_at: "2026-06-25 14:00:00",
    car: {
      name: "Mazda 3 2022",
      license_plate: "51G-555.55",
      image: "https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=600",
      location: "Thanh Xuân, Hà Nội"
    },
    renter: {
      name: "Phạm Văn Nam",
      phone: "0978 *** 123"
    }
  },
  {
    id: -4,
    cost: 2800000,
    discount_amount: 100000,
    status: 2,
    trip_type: 0,
    start_at: "2026-06-20 07:00:00",
    end_at: "2026-06-22 19:00:00",
    car: {
      name: "Toyota Camry 2020",
      license_plate: "30H-777.77",
      image: "https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600",
      location: "Nam Từ Liêm, Hà Nội"
    },
    renter: {
      name: "Hoàng Anh Đức",
      phone: "0904 *** 999"
    }
  }
];

// Load owner trips data
const loadOwnerTrips = async () => {
  loading.value = true;
  try {
    const res = await carService.getTrips();
    let apiTrips = [];
    if (res && res.success && res.data && res.data.owner) {
      apiTrips = res.data.owner.map((trip: any) => {
        const thumbnailImg = trip.car?.images?.find((img: any) => img.is_thumbnail === 1)?.image_url
          || trip.car?.images?.[0]?.image_url
          || 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=600';

        return {
          ...trip,
          car: {
            ...trip.car,
            image: thumbnailImg,
            location: trip.car?.car_location?.address || 'Chưa cập nhật'
          },
          renter: {
            name: trip.user?.name || 'Khách hàng',
            phone: trip.user?.phone || 'Chưa cập nhật SĐT'
          }
        };
      });
    }
    ownerTrips.value = [...mockTrips, ...apiTrips];
  } catch (err) {
    console.error('Không tải được danh sách yêu cầu thuê xe:', err);
    ownerTrips.value = [...mockTrips];
    showToast('Kết nối máy chủ thất bại, hiển thị dữ liệu mẫu.', 'info');
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadOwnerTrips();
});

// Filtering based on current active tab
const filteredTrips = computed(() => {
  if (activeFilter.value === 'pending') {
    return ownerTrips.value.filter(t => t.status === 5);
  } else if (activeFilter.value === 'active') {
    return ownerTrips.value.filter(t => t.status === 0 || t.status === 1);
  } else {
    return ownerTrips.value.filter(t => t.status === 2 || t.status === 3 || t.status === 4);
  }
});

// Helper labels and classes
function statusLabel(status: number) {
  return ['Chưa bắt đầu', 'Đang diễn ra', 'Đã hoàn thành', 'Đã hủy (khách)', 'Đã từ chối', 'Chờ duyệt'][status] ?? '—';
}

function statusClass(status: number) {
  return [
    'bg-slate-100 text-slate-600',                          // 0: Chưa bắt đầu
    'bg-blue-50 text-blue-600',                              // 1: Đang diễn ra
    'bg-emerald-50 text-emerald-600 border border-emerald-100', // 2: Đã hoàn thành
    'bg-red-50 text-red-500',                                // 3: Hủy bởi khách
    'bg-rose-50 text-rose-500 border border-rose-100',       // 4: Từ chối bởi chủ
    'bg-amber-50 text-amber-600 border border-amber-200',    // 5: Chờ duyệt
  ][status] ?? 'bg-slate-100 text-slate-500';
}

function statusDot(status: number) {
  return [
    'bg-slate-400',
    'bg-blue-500 animate-pulse',
    'bg-emerald-500',
    'bg-red-400',
    'bg-rose-400',
    'bg-amber-500 animate-pulse',
  ][status] ?? 'bg-slate-400';
}

function formatDate(dt: string) {
  if (!dt) return '—';
  return new Date(dt).toLocaleString('vi-VN', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit',
  });
}

function duration(start: string, end: string) {
  if (!start || !end) return '—';
  const diff = new Date(end).getTime() - new Date(start).getTime();
  const days = Math.floor(diff / 86400000);
  const hours = Math.floor((diff % 86400000) / 3600000);
  return days > 0 ? `${days} ngày${hours > 0 ? ` ${hours} giờ` : ''}` : `${hours} giờ`;
}

function formatCurrency(amount: number) {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}

// Client-side simulation functions (no backend API call as requested)
const confirmTrip = (trip: any) => {
  // Update state locally
  const index = ownerTrips.value.findIndex(t => t.id === trip.id);
  if (index !== -1) {
    ownerTrips.value[index].status = 0; // Set status to 0 (Chưa bắt đầu)
  }
  showToast(`Đã xác nhận cho thuê xe đối với yêu cầu của ${trip.renter.name} thành công.`, 'success');
};

const openRejectDialog = (trip: any) => {
  selectedTripForReject.value = trip;
  rejectReason.value = '';
  showRejectModal.value = true;
};

const closeRejectDialog = () => {
  showRejectModal.value = false;
  selectedTripForReject.value = null;
  rejectReason.value = '';
};

const submitRejection = () => {
  if (!rejectReason.value.trim()) {
    showToast('Vui lòng nhập lý do từ chối.', 'error');
    return;
  }
  
  if (selectedTripForReject.value) {
    const tripId = selectedTripForReject.value.id;
    const renterName = selectedTripForReject.value.renter.name;
    
    // Update status locally
    const index = ownerTrips.value.findIndex(t => t.id === tripId);
    if (index !== -1) {
      ownerTrips.value[index].status = 4; // Set status to 4 (Từ chối / Đã hủy bởi chủ xe)
      ownerTrips.value[index].reject_reason = rejectReason.value; // Store reason locally
    }
    
    showToast(`Đã từ chối yêu cầu thuê xe của ${renterName} thành công.`, 'success');
  }
  
  closeRejectDialog();
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

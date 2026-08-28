<template>
  <HeaderProfile />

  <main class="mt-[120px] pb-20 min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 pt-5 sm:px-6 lg:px-8">
      <!-- Premium Dashboard Header Card -->
      

      <!-- Account Suspended / Warning Banner (Clean & Minimal) -->
      <div v-if="ownerReportSummary?.is_account_suspended" 
           class="mb-6 bg-rose-50/90 border border-rose-200 text-rose-900 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 animate-fade-in">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
            <Icon name="lucide:shield-ban" class="w-5 h-5" />
          </div>
          <div>
            <h4 class="font-bold text-xs sm:text-sm tracking-wide text-rose-950 flex items-center gap-2">
              Tài khoản chủ xe đang bị Tạm Khóa
              <span class="text-[10px] uppercase font-bold bg-rose-200/80 text-rose-800 px-2 py-0.5 rounded-full">Đình chỉ</span>
            </h4>
            <p class="text-xs text-rose-800 font-normal mt-0.5">
              Tài khoản của bạn đã đạt mức giới hạn vi phạm. Các xe của bạn sẽ tạm thời không thể nhận chuyến mới.
            </p>
          </div>
        </div>
        <NuxtLink to="/my-cars/reports"
          class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all shrink-0 flex items-center gap-1.5 active:scale-95">
          <span>Xem chi tiết vi phạm</span>
          <Icon name="lucide:arrow-right" class="w-3.5 h-3.5" />
        </NuxtLink>
      </div>

      <div v-else-if="ownerReportSummary && ownerReportSummary.active_strikes > 0"
           class="mb-6 bg-amber-50/90 border border-amber-200 text-amber-900 rounded-2xl p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 animate-fade-in">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
            <Icon name="lucide:alert-triangle" class="w-5 h-5" />
          </div>
          <div>
            <h4 class="font-bold text-xs sm:text-sm tracking-wide text-amber-950 flex items-center gap-2">
              Cảnh báo: Bạn đang có {{ ownerReportSummary.active_strikes }} lần cảnh cáo vi phạm
              <span class="text-[10px] uppercase font-bold bg-amber-200/80 text-amber-800 px-2 py-0.5 rounded-full">Mức {{ ownerReportSummary.active_strikes }}/3</span>
            </h4>
            <p class="text-xs text-amber-800 font-normal mt-0.5">
              Vui lòng kiểm tra các khiếu nại và tuân thủ quy định để tránh bị tạm ngưng tài khoản khi đạt mức 3.
            </p>
          </div>
        </div>
        <NuxtLink to="/my-cars/reports"
          class="px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all shrink-0 flex items-center gap-1.5 active:scale-95">
          <span>Xem chi tiết vi phạm</span>
          <Icon name="lucide:arrow-right" class="w-3.5 h-3.5" />
        </NuxtLink>
      </div>

      <div class="bg-white rounded-2xl shadow-sm px-6 mb-8">
        <div ref="tabWrapper"
          class="flex overflow-x-auto scrollbar-hide gap-8 cursor-grab select-none active:cursor-grabbing"
          @mousedown="startDragging" @mousemove="handleDragging" @mouseup="stopDragging" @mouseleave="stopDragging">

        <NuxtLink to="/my-cars/dashboard"
            class="py-5 text-slate-500 whitespace-nowrap hover:text-[#1e4e57] transition-colors border-b-2 border-transparent flex items-center gap-2 text-sm font-semibold"
            active-class="!border-[#1e4e57] !text-[#1e4e57] !font-bold" @click="handleLinkClick">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="text-current opacity-85">
              <path d="M3 3v18h18"></path>
              <rect x="6" y="12" width="3" height="6" rx="1"></rect>
              <rect x="11" y="8" width="3" height="10" rx="1"></rect>
              <rect x="16" y="5" width="3" height="13" rx="1"></rect>
            </svg>
            Thống kê
          </NuxtLink>

          <NuxtLink to="/my-cars"
            class="py-5 text-slate-500 whitespace-nowrap hover:text-[#1e4e57] transition-colors border-b-2 border-transparent flex items-center gap-2 text-sm font-semibold"
            active-class="!border-[#1e4e57] !text-[#1e4e57] !font-bold" @click="handleLinkClick">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="text-current opacity-85">
              <path
                d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2">
              </path>
              <circle cx="7" cy="17" r="2"></circle>
              <circle cx="17" cy="17" r="2"></circle>
              <path d="M13 17H7"></path>
              <path d="M13 10h3"></path>
            </svg>
            Danh sách xe
          </NuxtLink>

          <NuxtLink to="/my-cars/bookings"
            class="py-5 text-slate-500 whitespace-nowrap hover:text-[#1e4e57] transition-colors border-b-2 border-transparent flex items-center gap-2 text-sm font-semibold"
            active-class="!border-[#1e4e57] !text-[#1e4e57] !font-bold" @click="handleLinkClick">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="text-current opacity-85">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
              <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            Chuyến cho thuê
          </NuxtLink>

          <NuxtLink to="/my-cars/reports"
            class="py-5 text-slate-500 whitespace-nowrap hover:text-[#1e4e57] transition-colors border-b-2 border-transparent flex items-center gap-2 text-sm font-semibold"
            active-class="!border-[#1e4e57] !text-[#1e4e57] !font-bold" @click="handleLinkClick">
            <Icon name="lucide:shield-alert" class="w-4 h-4 text-current opacity-85" />
            <span>Báo cáo & Vi phạm</span>
            <span v-if="ownerReportSummary && ownerReportSummary.active_strikes > 0"
                  class="px-1.5 py-0.2 bg-rose-500 text-white text-[10px] font-extrabold rounded-full animate-pulse shadow-sm">
              {{ ownerReportSummary.active_strikes }} cảnh cáo
            </span>
            <span v-else-if="ownerReportSummary && ownerReportSummary.reports?.pending > 0"
                  class="px-1.5 py-0.2 bg-amber-500 text-white text-[10px] font-bold rounded-full shadow-sm">
              {{ ownerReportSummary.reports.pending }} mới
            </span>
          </NuxtLink>

          <NuxtLink to="/my-cars/calendar"
            class="py-5 text-slate-500 whitespace-nowrap hover:text-[#1e4e57] transition-colors border-b-2 border-transparent flex items-center gap-2 text-sm font-semibold"
            active-class="!border-[#1e4e57] !text-[#1e4e57] !font-bold" @click="handleLinkClick">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="text-current opacity-85">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            Lịch xe
          </NuxtLink>

          <NuxtLink to="/my-cars/rentalguide"
            class="py-5 text-slate-500 whitespace-nowrap hover:text-[#1e4e57] transition-colors border-b-2 border-transparent flex items-center gap-2 text-sm font-semibold"
            active-class="!border-[#1e4e57] !text-[#1e4e57] !font-bold" @click="handleLinkClick">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="text-current opacity-85">
              <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
              <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
            Cẩm nang cho thuê
          </NuxtLink>

          <NuxtLink to="/my-cars/traffic-fines"
            class="py-5 text-slate-500 whitespace-nowrap hover:text-[#1e4e57] transition-colors border-b-2 border-transparent flex items-center gap-2 text-sm font-semibold"
            active-class="!border-[#1e4e57] !text-[#1e4e57] !font-bold" @click="handleLinkClick">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="text-current opacity-85">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            Tra cứu phạt nguội
          </NuxtLink>

          <NuxtLink to="/my-cars/gps"
            class="py-5 text-slate-500 whitespace-nowrap hover:text-[#1e4e57] transition-colors border-b-2 border-transparent flex items-center gap-2 text-sm font-semibold"
            active-class="!border-[#1e4e57] !text-[#1e4e57] !font-bold" @click="handleLinkClick">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="text-current opacity-85">
              <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
            Định vị
          </NuxtLink>

          <NuxtLink to="/my-cars/contract"
            class="py-5 text-slate-500 whitespace-nowrap hover:text-[#1e4e57] transition-colors border-b-2 border-transparent flex items-center gap-2 text-sm font-semibold"
            active-class="!border-[#1e4e57] !text-[#1e4e57] !font-bold" @click="handleLinkClick">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="text-current opacity-85">
              <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
              <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            Hợp đồng & chứng từ
          </NuxtLink>

          <NuxtLink to="/my-cars/privacy"
            class="py-5 text-slate-500 whitespace-nowrap hover:text-[#1e4e57] transition-colors border-b-2 border-transparent flex items-center gap-2 text-sm font-semibold"
            active-class="!border-[#1e4e57] !text-[#1e4e57] !font-bold" @click="handleLinkClick">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="text-current opacity-85">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            Chính sách bảo vệ dữ liệu
          </NuxtLink>
        </div>
      </div>

      <div>
        <slot />
      </div>
    </div>
  </main>
  <CommonToast />
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import HeaderProfile from "~/components/Profile/HeaderProfile.vue";
import { myCarService } from "~/services/my_car.service";
import { walletService } from "~/services/wallet.service";
import { reportService, type OwnerReportSummary } from "~/services/report.service";

const { user } = useAuth();
const totalCars = ref(0);
const activeCars = ref(0);
const walletBalance = ref(0);
const ownerReportSummary = ref<OwnerReportSummary | null>(null);

const activeRate = computed(() => {
  if (totalCars.value === 0) return 0;
  return Math.round((activeCars.value / totalCars.value) * 100);
});

const formatPrice = (val: any) => {
  if (val === undefined || val === null) return "0đ";
  const num = Math.round(Number(val)) || 0;
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + "đ";
};

onMounted(async () => {
  if (user.value) {
    try {
      // Get cars stats
      const carsRes = await myCarService.getCars({ user_id: user.value.id });
      if (carsRes.success && carsRes.data) {
        const carsList = carsRes.data;
        totalCars.value = carsList.length;
        activeCars.value = carsList.filter((c: any) => c.status === 1).length;
      }

      // Get wallet balance
      const walletRes = await walletService.getWalletDetails();
      if (walletRes.success && walletRes.data) {
        walletBalance.value = walletRes.data.balance || 0;
      }

      // Get owner report & strike summary
      const reportRes = await reportService.getOwnerSummary();
      if (reportRes.success && reportRes.data) {
        ownerReportSummary.value = reportRes.data;
      }
    } catch (e) {
      console.error("Error loading stats in layout", e);
    }
  }
});

const tabWrapper = ref<HTMLElement | null>(null);

let isDown = false;
let startX = 0;
let scrollLeft = 0;
let isDragging = false; // Biến check xem người dùng đang kéo hay đang click

// Khi nhấn giữ chuột xuống
const startDragging = (e: MouseEvent) => {
  if (!tabWrapper.value) return;
  isDown = true;
  isDragging = false; // Reset lại trạng thái
  startX = e.pageX - tabWrapper.value.offsetLeft;
  scrollLeft = tabWrapper.value.scrollLeft;
};

// Khi di chuyển chuột
const handleDragging = (e: MouseEvent) => {
  if (!isDown || !tabWrapper.value) return;

  const x = e.pageX - tabWrapper.value.offsetLeft;
  const walk = (x - startX) * 1.5;

  // Nếu di chuyển hơn 5px thì xác định đây là hành động KÉO
  if (Math.abs(walk) > 5) {
    isDragging = true;
  }

  e.preventDefault();
  tabWrapper.value.scrollLeft = scrollLeft - walk;
};

// Khi buông chuột ra
const stopDragging = () => {
  isDown = false;
};

// Chặn hành động chuyển trang của NuxtLink nếu đang kéo
const handleLinkClick = (e: MouseEvent) => {
  if (isDragging) {
    e.preventDefault();
    e.stopPropagation();
  }
};
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>

<template>
  <HeaderProfile />

  <main class="mt-[120px] pb-20 min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Premium Dashboard Header Card -->
      <div
        class="bg-gradient-to-br from-[#1d4454] via-[#163a41] to-[#0f272c] rounded-3xl p-6 lg:p-8 shadow-xl mb-8 text-white relative overflow-hidden border border-white/5">
        <!-- Decorative Glow Background Effects -->
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-[#5FCF86]/10 rounded-full blur-[100px] pointer-events-none">
        </div>
        <div
          class="absolute -left-20 -bottom-20 w-80 h-80 bg-cyan-500/10 rounded-full blur-[100px] pointer-events-none">
        </div>

        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between relative z-10">
          <div>
            <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight">Quản lý xe</h1>
            <p class="text-emerald-100/70 mt-2 text-sm lg:text-base font-medium">
              Quản lý phương tiện và theo dõi hoạt động kinh doanh cho thuê xe của bạn
            </p>
          </div>

          <div class="flex flex-wrap items-center gap-3.5 w-full sm:w-auto">
            <NuxtLink to="/car-register"
              class="flex-1 sm:flex-initial flex items-center justify-center gap-2 px-5 py-3 bg-[#5FCF86] text-slate-950 rounded-2xl text-sm font-bold hover:bg-[#6ee096] transition-all duration-300 shadow-[0_4px_20px_rgba(95,207,134,0.25)] hover:scale-[1.03] active:scale-[0.98] transform">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
              </svg>
              Đăng ký xe mới
            </NuxtLink>

            <NuxtLink to="/mywallet"
              class="flex-1 sm:flex-initial flex items-center justify-center gap-2.5 px-5 py-3 bg-white/10 backdrop-blur-md border border-white/10 text-white rounded-2xl text-sm font-bold hover:bg-white/15 transition-all duration-300 hover:scale-[1.03] active:scale-[0.98] transform shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="text-[#5FCF86]">
                <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path>
                <path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path>
                <path d="M18 12a2 2 0 0 0 0 4h4v-4Z"></path>
              </svg>
              <span>Ví chủ xe: <span class="text-[#5FCF86] font-extrabold ml-1">{{ formatPrice(walletBalance)
              }}</span></span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                class="text-white/45">
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </NuxtLink>
          </div>
        </div>

        <!-- Quick Stats Strip -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/10 relative z-10">
          <div class="bg-white/5 rounded-2xl p-4 border border-white/5 flex items-center gap-4">
            <div class="p-3 bg-white/10 rounded-xl text-slate-200 border border-white/5 shadow-inner">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                  d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2">
                </path>
                <circle cx="7" cy="17" r="2"></circle>
                <circle cx="17" cy="17" r="2"></circle>
                <path d="M13 17H7"></path>
                <path d="M13 10h3"></path>
              </svg>
            </div>
            <div>
              <p class="text-[11px] text-white/50 font-bold uppercase tracking-wider">Tổng số xe</p>
              <h4 class="text-xl font-black mt-0.5 text-white">{{ totalCars }} <span
                  class="text-xs font-normal text-white/60">xe</span></h4>
            </div>
          </div>

          <div class="bg-white/5 rounded-2xl p-4 border border-white/5 flex items-center gap-4">
            <div class="p-3 bg-white/10 rounded-xl text-slate-200 border border-white/5 shadow-inner">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
              </svg>
            </div>
            <div>
              <p class="text-[11px] text-white/50 font-bold uppercase tracking-wider">Đang hoạt động</p>
              <h4 class="text-xl font-black mt-0.5 text-white">{{ activeCars }} <span
                  class="text-xs font-normal text-white/60">xe</span></h4>
            </div>
          </div>

          <div class="bg-white/5 rounded-2xl p-4 border border-white/5 flex items-center gap-4">
            <div class="p-3 bg-white/10 rounded-xl text-slate-200 border border-white/5 shadow-inner">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
              </svg>
            </div>
            <div>
              <p class="text-[11px] text-white/50 font-bold uppercase tracking-wider">Hiệu suất thuê</p>
              <h4 class="text-xl font-black mt-0.5 text-white">{{ activeRate }}%</h4>
            </div>
          </div>

          <div
            class="bg-white/5 rounded-2xl p-4 border border-white/5 flex items-center gap-4 col-span-2 md:col-span-1">
            <div class="p-3 bg-white/10 rounded-xl text-slate-200 border border-white/5 shadow-inner">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                <polyline points="17 6 23 6 23 12"></polyline>
              </svg>
            </div>
            <div>
              <p class="text-[11px] text-white/50 font-bold uppercase tracking-wider">Doanh thu khả dụng</p>
              <h4 class="text-xl font-black mt-0.5 text-white">{{ formatPrice(walletBalance) }}</h4>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-sm px-6 mb-8">
        <div ref="tabWrapper"
          class="flex overflow-x-auto scrollbar-hide gap-8 cursor-grab select-none active:cursor-grabbing"
          @mousedown="startDragging" @mousemove="handleDragging" @mouseup="stopDragging" @mouseleave="stopDragging">
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

const { user } = useAuth();
const totalCars = ref(0);
const activeCars = ref(0);
const walletBalance = ref(0);

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

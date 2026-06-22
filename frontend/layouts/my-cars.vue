<template>
  <HeaderProfile />

  <main class="mt-[120px] pb-20 min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white rounded-3xl p-6 lg:p-8 shadow-sm mb-8">
        <div
          class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between"
        >
          <div>
            <h1 class="text-3xl lg:text-4xl font-bold text-dark">Quản lý xe</h1>
            <p class="text-gray-500 mt-2">
              Quản lý phương tiện và hoạt động cho thuê
            </p>
          </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
          <NuxtLink to="/car-register" class="flex-1 sm:flex-initial flex items-center justify-center gap-1.5 px-4 py-2.5 bg-white border border-gray-200 text-slate-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition shadow-sm">
            <Icon name="lucide:plus-circle" class="text-[#53cf84]" size="18" />
            Đăng ký xe
          </NuxtLink>
          
          <NuxtLink to="/mywallet" class="flex-1 sm:flex-initial flex items-center justify-center gap-1.5 px-4 py-2.5 bg-white border border-gray-200 text-slate-700 rounded-xl text-sm font-semibold hover:bg-gray-50 transition shadow-sm">
            <Icon name="lucide:wallet" class="text-emerald-500" size="18" />
            Số dư: <span class="text-[#53cf84] font-bold">{{ formatPrice(balance) }}</span>
            <Icon name="lucide:chevron-right" class="text-gray-400" size="16" />
          </NuxtLink>
        </div>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-sm px-6 mb-8">
        <div
          ref="tabWrapper"
          class="flex overflow-x-auto scrollbar-hide gap-8 cursor-grab select-none active:cursor-grabbing"
          @mousedown="startDragging"
          @mousemove="handleDragging"
          @mouseup="stopDragging"
          @mouseleave="stopDragging"
        >
          <NuxtLink
            to="/my-cars"
            class="py-5 text-gray-500 whitespace-nowrap hover:text-brand-primary transition-colors border-b-2 border-transparent"
            active-class="!border-brand-primary !text-brand-primary font-medium"
            @click="handleLinkClick"
          >
            Danh sách xe
          </NuxtLink>

          <NuxtLink
            to="/my-cars/calendar"
            class="py-5 text-gray-500 whitespace-nowrap hover:text-brand-primary transition-colors border-b-2 border-transparent"
            active-class="!border-brand-primary !text-brand-primary font-medium"
            @click="handleLinkClick"
          >
            Lịch xe
          </NuxtLink>

          <NuxtLink
            to="/my-cars/rentalguide"
            class="py-5 text-gray-500 whitespace-nowrap hover:text-brand-primary transition-colors border-b-2 border-transparent"
            active-class="!border-brand-primary !text-brand-primary font-medium"
            @click="handleLinkClick"
          >
            Cẩm nang cho thuê
          </NuxtLink>

          <NuxtLink
            to="/my-cars/traffic-fines"
            class="py-5 text-gray-500 whitespace-nowrap hover:text-brand-primary transition-colors border-b-2 border-transparent"
            active-class="!border-brand-primary !text-brand-primary font-medium"
            @click="handleLinkClick"
          >
            Tra cứu phạt nguội
          </NuxtLink>

          <NuxtLink
            to="/my-cars/gps"
            class="py-5 text-gray-500 whitespace-nowrap hover:text-brand-primary transition-colors border-b-2 border-transparent"
            active-class="!border-brand-primary !text-brand-primary font-medium"
            @click="handleLinkClick"
          >
            Định vị
          </NuxtLink>

          <NuxtLink
            to="/my-cars/contract"
            class="py-5 text-gray-500 whitespace-nowrap hover:text-brand-primary transition-colors border-b-2 border-transparent"
            active-class="!border-brand-primary !text-brand-primary font-medium"
            @click="handleLinkClick"
          >
            Hợp đồng & chứng từ
          </NuxtLink>

          <NuxtLink
            to="/my-cars/privacy"
            class="py-5 text-gray-500 whitespace-nowrap hover:text-brand-primary transition-colors border-b-2 border-transparent"
            active-class="!border-brand-primary !text-brand-primary font-medium"
            @click="handleLinkClick"
          >
            Chính sách bảo vệ dữ liệu
          </NuxtLink>
        </div>
      </div>

      <div>
        <slot />
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import HeaderProfile from "~/components/Profile/HeaderProfile.vue";
import { walletService } from "~/services/wallet.service";
import { useAuth } from "~/composables/useAuth";

const { isLoggedIn } = useAuth();
const balance = ref<number>(0);

const formatPrice = (val: number) => {
  return new Intl.NumberFormat('vi-VN').format(val) + 'đ';
};

const loadWalletBalance = async () => {
  try {
    const response = await walletService.getWalletDetails();
    if (response.success && response.data) {
      balance.value = response.data.balance;
    }
  } catch (error) {
    console.error("Lỗi khi lấy thông tin số dư ví:", error);
  }
};

onMounted(() => {
  loadWalletBalance();
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

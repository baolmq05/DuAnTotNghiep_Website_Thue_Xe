<template>
  <div class="space-y-6">
    <!-- Card White Box chứa nội dung Chính sách -->
    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-50 max-w-7xl">
      
      <!-- 1. Banner Đồ Họa Bảo Mật (Tái dựng mượt mà bằng CSS/SVG) -->
      <div class="w-full bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 rounded-2xl p-6 md:p-8 relative overflow-hidden min-h-[180px] md:min-h-[240px] flex items-center mb-6 shadow-inner">
        <!-- Các họa tiết vòng tròn bảo mật mờ phía sau -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_50%,rgba(255,255,255,0.15),transparent_60%)]"></div>
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center w-full relative z-10">
          <!-- Khối Khiên Bảo Mật Bên Trái -->
          <div class="md:col-span-5 flex justify-center md:justify-start">
            <div class="relative animate-pulse-[pulse_3s_infinite]">
              <Icon name="lucide:shield-check" class="text-white drop-shadow-md" size="100" />
              <Icon name="lucide:lock" class="text-emerald-500 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/3" size="32" />
            </div>
          </div>
          
          <!-- Khối Smartphone & Xe Công Nghệ Bên Phải -->
          <div class="md:col-span-7 flex justify-center md:justify-end items-center gap-4">
            <!-- Xe ô tô nhỏ -->
            <div class="hidden sm:block transform -translate-y-2">
              <Icon name="lucide:car" class="text-white/90 drop-shadow" size="64" />
            </div>
            <!-- Điện thoại chứa dữ liệu -->
            <div class="bg-slate-900 text-white rounded-2xl p-2.5 w-32 h-52 border-4 border-slate-700 shadow-2xl flex flex-col justify-between">
              <div class="w-8 h-1 bg-slate-700 rounded-full mx-auto mb-2"></div>
              <div class="flex-1 bg-emerald-50 text-slate-700 rounded-lg p-2 flex flex-col justify-between text-[8px] space-y-1">
                <div class="h-2 bg-emerald-200 rounded w-3/4"></div>
                <div class="h-1.5 bg-gray-200 rounded"></div>
                <div class="h-1.5 bg-gray-200 rounded w-5/6"></div>
                <div class="h-6 bg-emerald-500 rounded flex items-center justify-center text-white font-bold text-[7px]">DRIVO SECURE</div>
              </div>
              <div class="w-3 h-3 bg-slate-700 rounded-full mx-auto mt-2"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. Danh sách Checkbox điều khoản (Sử dụng đúng thương hiệu Drivo) -->
      <div class="space-y-4 mb-6">
        <!-- Checkbox 1 -->
        <label class="flex items-start gap-3 cursor-pointer group">
          <input 
            v-model="agreements.location"
            type="checkbox" 
            class="mt-1 w-4 h-4 rounded text-brand-primary border-gray-300 focus:ring-brand-primary accent-[#53cf84] shrink-0"
          />
          <span class="text-slate-700 text-sm leading-relaxed select-none group-hover:text-slate-900">
            Đồng ý cho thu thập và xử lý dữ liệu vị trí của tôi nhằm mục đích cung cấp dịch vụ theo quy định pháp luật và Chính sách bảo vệ dữ liệu cá nhân của <strong class="text-slate-800">Drivo</strong>.
          </span>
        </label>

        <!-- Checkbox 2 -->
        <label class="flex items-start gap-3 cursor-pointer group">
          <input 
            v-model="agreements.bank"
            type="checkbox" 
            class="mt-1 w-4 h-4 rounded text-brand-primary border-gray-300 focus:ring-brand-primary accent-[#53cf84] shrink-0"
          />
          <span class="text-slate-700 text-sm leading-relaxed select-none group-hover:text-slate-900">
            Đồng ý cho thu thập và xử lý dữ liệu thông tin tài khoản ngân hàng của tôi nhằm mục đích cung cấp dịch vụ theo quy định pháp luật và Chính sách bảo vệ dữ liệu cá nhân của <strong class="text-slate-800">Drivo</strong>.
          </span>
        </label>
      </div>

      <!-- 3. Các dòng văn bản cam kết -->
      <div class="text-sm text-slate-600 space-y-3 border-t border-gray-100 pt-5 mb-6">
        <p>
          <strong class="text-brand-primary">Drivo</strong> cam kết bảo vệ và sử dụng dữ liệu cá nhân của Khách hàng một cách minh bạch, an toàn và đúng quy định.
        </p>
        <p>
          Quý Khách hàng có thể xem thêm chi tiết tại 
          <NuxtLink to="/chinh-sach-bao-mat" class="text-[#53cf84] underline hover:text-[#43bd73] font-medium">
            Chính sách bảo vệ dữ liệu cá nhân
          </NuxtLink> 
          của <strong class="text-slate-800">Drivo</strong>.
        </p>
      </div>

      <!-- 4. Thời gian cập nhật & Nút xác nhận -->
      <div class="space-y-4">
        <p class="text-xs text-gray-400">
          Cập nhật lần cuối: 14/06/2026
        </p>

        <button
          @click="handleConfirm"
          :disabled="!isAllChecked"
          class="w-full text-center py-3.5 rounded-xl text-white font-semibold text-base transition-all duration-200 shadow-sm"
          :class="isAllChecked ? 'bg-[#53cf84] hover:bg-[#43bd73] cursor-pointer shadow-md' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
        >
          Xác nhận
        </button>
      </div>

    </div>
  </div>
</template>

<script lang="ts" setup>
import { reactive, computed } from 'vue';

definePageMeta({
  layout: "my-cars", // Đồng bộ với layout tab-bar của sếp
});

// Trạng thái tích chọn của 2 ô Checkbox
const agreements = reactive({
  location: false,
  bank: false
});

// Chỉ kích hoạt nút khi sếp / người dùng đã tick chọn cả 2 ô
const isAllChecked = computed(() => {
  return agreements.location && agreements.bank;
});

// Xử lý gửi xác nhận chính sách về hệ thống Drivo
const handleConfirm = () => {
  if (!isAllChecked.value) return;
  
  console.log('Chủ xe đã chấp thuận các điều khoản bảo mật của Drivo.');
  alert('Hệ thống Drivo đã ghi nhận xác nhận bảo mật dữ liệu của sếp thành công!');
};
</script>
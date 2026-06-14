<template>
  <div>
    <!-- Header Profile của hệ thống -->
    <HeaderProfile />

    <div class="w-full min-h-screen bg-[#f8fafc] flex flex-col mt-[90px]">
      <!-- Body Dashboard chia làm 2 cột theo ảnh mẫu image_63585b.png -->
      <div class="flex-1 flex flex-col md:flex-row h-[calc(100vh-90px)] overflow-hidden">
        
        <!-- BIÊN TRÁI: SIDEBAR BỘ LỌC THIẾT BỊ GPS -->
        <div class="w-full md:w-80 bg-white border-r border-gray-200 flex flex-col p-4 space-y-4 shrink-0">
          
          <!-- NÚT BACK (QUAY LẠI) - Vừa bổ sung chuẩn UI -->
          <NuxtLink 
            to="/my-cars" 
            class="inline-flex items-center gap-2 text-slate-500 hover:text-brand-primary transition-colors text-sm font-semibold pb-2 border-b border-gray-100"
          >
            <Icon name="lucide:arrow-left" size="18" />
            Quay lại Quản lý xe
          </NuxtLink>

          <!-- 1. Bộ lọc loại GPS (Có dây / Không dây) -->
          <div class="space-y-2.5 pt-1">
            <label class="flex items-center justify-between cursor-pointer group text-sm font-medium text-slate-700">
              <span>GPS có dây</span>
              <input 
                v-model="gpsType" 
                type="radio" 
                value="wired"
                name="gps_type"
                class="w-4 h-4 text-brand-primary accent-[#53cf84] cursor-pointer"
              />
            </label>
            
            <label class="flex items-center justify-between cursor-pointer group text-sm font-medium text-slate-700">
              <span>GPS không dây</span>
              <input 
                v-model="gpsType" 
                type="radio" 
                value="wireless"
                name="gps_type"
                class="w-4 h-4 text-brand-primary accent-[#53cf84] cursor-pointer"
              />
            </label>
          </div>

          <!-- 2. Thanh tìm kiếm xe (Biển số / Tên xe) -->
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
              <Icon name="lucide:search" size="16" />
            </span>
            <input 
              v-model="searchQuery"
              type="text" 
              placeholder="Lọc theo tên hoặc biển số"
              class="w-full text-xs rounded-lg border border-gray-200 bg-white pl-9 pr-4 py-2.5 outline-none focus:border-[#53cf84] text-slate-700 placeholder:text-gray-400 placeholder:font-normal"
            />
          </div>

          <!-- 3. Danh sách/Trạng thái Xe (Nút Tất cả xe viền xanh lá nhạt) -->
          <button class="w-full text-left px-3 py-2.5 rounded-lg border border-brand-primary bg-[#f2fbf6] text-brand-primary text-xs font-semibold transition duration-150">
            Tất cả xe
          </button>
        </div>

        <!-- BIÊN PHẢI: KHU VỰC HIỂN THỊ DỮ LIỆU / BẢN ĐỒ -->
        <div class="flex-1 bg-[#f4f6f8] flex flex-col items-center justify-center p-6 text-center overflow-y-auto">
          
          <!-- Khối thông báo "Không tìm thấy dữ liệu" chuẩn đồ họa gốc -->
          <div class="max-w-md flex flex-col items-center space-y-6 animate-fade-in">
            
            <!-- Đồ họa nhân vật cầm điện thoại và ghim định vị lớn bằng CSS/SVG -->
            <div class="relative w-64 h-64 flex items-center justify-center">
              <!-- Vòng nền tròn mờ mờ -->
              <div class="absolute inset-0 bg-white/60 rounded-full blur-xl transform scale-90"></div>
              
              <!-- Ghim định vị Map Pin màu cam lớn đặc trưng bay phía trên -->
              <div class="absolute top-4 right-16 animate-bounce duration-1000 z-20">
                <Icon name="ic:baseline-location-on" class="text-[#f15a24]" size="56" />
              </div>

              <!-- SVG Minh họa Nhân vật tối giản sắc nét -->
              <svg class="w-52 h-auto text-emerald-400 fill-current relative z-10" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <!-- Áo xanh lá -->
                <path d="M60,180 C60,140 80,120 100,120 C120,120 140,140 140,180 Z" fill="#53cf84" />
                <!-- Sọc ngang trên áo -->
                <path d="M72,150 Q100,160 128,150" stroke="#3bbf74" stroke-width="6" fill="none" stroke-linecap="round" />
                <path d="M65,170 Q100,180 135,170" stroke="#48c9b0" stroke-width="6" fill="none" stroke-linecap="round" />
                <!-- Cánh tay giơ điện thoại -->
                <path d="M140,170 C150,140 155,120 145,100 C140,95 135,100 135,105" stroke="#f0c49e" stroke-width="12" fill="none" stroke-linecap="round" />
                <!-- Đầu & Tóc -->
                <circle cx="100" cy="85" r="22" fill="#f0c49e" />
                <path d="M78,80 C78,60 100,55 115,65 C125,70 122,85 120,90 C110,85 90,90 78,80 Z" fill="#1a1a1a" />
                <!-- Khuôn mặt -->
                <circle cx="95" cy="82" r="2" fill="#333" />
                <circle cx="108" cy="82" r="2" fill="#333" />
                <path d="M98,95 Q102,92 106,95" stroke="#333" stroke-width="2" fill="none" />
              </svg>
              
              <!-- Điện thoại thông minh nhỏ trên tay -->
              <div class="absolute bottom-20 right-10 bg-slate-800 text-white rounded px-1 py-2 w-7 h-12 border border-slate-600 shadow-lg flex flex-col justify-between rotate-12">
                <div class="w-2 h-0.5 bg-slate-500 rounded-full mx-auto"></div>
                <div class="w-full h-7 bg-white rounded-sm flex items-center justify-center">
                  <div class="w-2 h-2 rounded-full bg-[#f15a24]"></div>
                </div>
              </div>
            </div>

            <!-- Đoạn Text nội dung thương hiệu Drivo -->
            <div class="space-y-2 px-4">
              <h3 class="text-xl font-bold text-slate-700">Không tìm thấy dữ liệu</h3>
              <p class="text-slate-500 text-sm leading-relaxed max-w-lg">
                (Tính năng mới trên <span class="font-semibold text-brand-primary">Drivo</span>, dành cho các chủ xe đã lắp đặt <span class="font-semibold text-slate-700">Drivo GPS</span>. <br>
                Liên hệ <span class="font-bold text-slate-800">1900 9271</span> để được tư vấn lắp đặt.)
              </p>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import HeaderProfile from '~/components/Profile/HeaderProfile.vue';


const gpsType = ref<string>('wired');
const searchQuery = ref<string>('');
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(8px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
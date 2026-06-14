<template>
  <div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-50 max-w-7xl">
      
      <label class="block text-slate-700 font-medium mb-3 text-sm md:text-base">
        Nhập biển số xe hoặc chọn xe
      </label>

      <div class="flex flex-col sm:flex-row gap-3 items-stretch mb-8">
        <div class="relative flex-1">
          <input 
            v-model="licensePlateInput"
            type="text" 
            placeholder="Nhập biển số xe (ví dụ: 30A-123.45)"
            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 pr-12 outline-none focus:border-brand-primary text-slate-700 font-semibold tracking-wider placeholder:font-normal placeholder:tracking-normal upprcase"
            @keyup.enter="handleSearch"
          />
          <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none flex items-center gap-1">
            <Icon name="ic:outline-directions-car" size="20" />
            <Icon name="ic:round-keyboard-arrow-down" size="16" />
          </div>
        </div>

        <button 
          @click="handleSearch"
          :disabled="isSearching"
          class="px-8 py-3 rounded-xl bg-gray-200 text-gray-500 font-semibold hover:bg-brand-primary hover:text-white disabled:bg-gray-100 disabled:text-gray-400 transition duration-200 shrink-0 flex items-center justify-center gap-2"
          :class="{ 'bg-brand-primary text-primary': licensePlateInput.trim().length > 0 }"
        >
          <Icon v-if="isSearching" name="line-md:loading-twotone-loop" size="18" />
          Tra cứu
        </button>
      </div>

      <div class="min-h-[350px] flex flex-col items-center justify-center py-6 text-center">
        
        <div v-if="isSearching" class="space-y-4">
          <Icon name="line-md:loading-twotone-loop" size="64" class="text-brand-primary" />
          <p class="text-gray-500 text-sm">Hệ thống đang kết nối dữ liệu phạt nguội...</p>
        </div>

        <div v-else class="space-y-6 flex flex-col items-center">
          <div class="relative w-64 h-40 flex items-center justify-center bg-emerald-50/30 rounded-3xl overflow-visible">
            <svg class="w-48 h-auto text-emerald-500 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1s.67-1 1.5-1 1.5.67 1.5 1-.67 1-1.5 1zm11 0c-.83 0-1.5-.67-1.5-1s.67-1 1.5-1 1.5.67 1.5 1-.67 1-1.5 1zM5 11l1.5-4.5h11L19 11H5z"/>
            </svg>
            
            <div class="absolute -top-2 left-1/3 bg-white border border-gray-100 shadow-sm rounded-md p-1 rotate-12 opacity-40">
              <Icon name="lucide:x" class="text-rose-400" size="14" />
            </div>
            <div class="absolute -top-4 right-1/3 bg-white border border-gray-100 shadow-sm rounded-md p-1 -rotate-12 opacity-40">
              <Icon name="lucide:x" class="text-rose-400" size="14" />
            </div>

            <div class="absolute bottom-0 left-6 w-8 h-12 flex flex-col items-center overflow-hidden">
              <div class="w-0 h-0 border-l-[12px] border-l-transparent border-r-[12px] border-r-transparent border-b-[40px] border-b-amber-500 relative flex items-center justify-center">
                <div class="absolute top-4 w-5 h-2 bg-yellow-100"></div>
              </div>
              <div class="w-8 h-1.5 bg-amber-600 rounded-sm"></div>
            </div>
          </div>

          <div>
            <h3 class="text-lg font-bold text-slate-700">Chưa ghi nhận vi phạm</h3>
            <p class="text-gray-400 text-xs md:text-sm mt-1 max-w-sm">
              Hiện tại hệ thống đăng kiểm và CSGT chưa ghi nhận lỗi phạt nguội nào cho phương tiện này.
            </p>
          </div>
        </div>

      </div>

    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';

definePageMeta({
  layout: "my-cars", // Giữ nguyên cấu trúc tab menu của Sếp
});

const licensePlateInput = ref<string>('');
const isSearching = ref<boolean>(false);

// Giả lập sự kiện bấm nút Tra cứu dữ liệu phạt nguội
const handleSearch = () => {
  if (!licensePlateInput.value.trim()) return;
  
  isSearching.value = true;
  
  // Tạo hiệu ứng loading giả lập trong 1.5 giây sau đó trả về trạng thái sạch lỗi
  setTimeout(() => {
    isSearching.value = false;
  }, 1500);
};
</script>
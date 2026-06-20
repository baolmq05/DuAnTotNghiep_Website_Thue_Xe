<template>
  <div class="space-y-6">
    <!-- Card White Box chứa toàn bộ nội dung Hợp đồng & Chứng từ -->
    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-50 max-w-7xl">
      
      <!-- PHẦN 1: HỢP ĐỒNG MẪU -->
      <div class="space-y-4 pb-8 border-b border-gray-100">
        <div>
          <h3 class="text-lg font-bold text-slate-800">Hợp đồng mẫu</h3>
          <p class="text-sm text-gray-400 mt-1">Chọn loại hợp đồng bạn muốn xem</p>
        </div>

        <!-- Khối chứa các nút Hợp đồng lớn màu xanh lá -->
        <div class="flex flex-wrap gap-4 pt-2">
          <!-- Nút Hợp đồng cho thuê -->
          <a 
            href="#" 
            class="inline-flex items-center gap-2 bg-[#53cf84] text-white px-5 py-3.5 rounded-xl font-medium transition duration-200 hover:bg-[#43bd73] shadow-sm text-sm"
            @click.prevent="viewContract('lease')"
          >
            <Icon name="lucide:file-text" size="18" />
            Hợp đồng cho thuê
          </a>

          <!-- Nút Biên bản bàn giao xe -->
          <a 
            href="#" 
            class="inline-flex items-center gap-2 bg-[#53cf84] text-white px-5 py-3.5 rounded-xl font-medium transition duration-200 hover:bg-[#43bd73] shadow-sm text-sm"
            @click.prevent="viewContract('handover')"
          >
            <Icon name="lucide:car" size="18" />
            Biên bản bàn giao xe
          </a>
        </div>
      </div>

      <!-- PHẦN 2: CHỨNG TỪ -->
      <div class="space-y-4 pt-6">
        <h3 class="text-lg font-bold text-slate-800 mb-2">Chứng từ</h3>

        <div class="divide-y divide-gray-50">
          <!-- Chứng từ quyết toán thuế -->
          <NuxtLink 
            to="/quan-ly-xe/hop-dong-chung-tu/quyet-toan-thue"
            class="group flex items-center justify-between py-3.5 hover:bg-slate-50/50 px-1 rounded-xl transition duration-150"
          >
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-slate-600 group-hover:bg-brand-secondary group-hover:text-brand-primary transition-colors">
                <Icon name="lucide:file-signature" size="16" />
              </div>
              <span class="text-slate-700 font-medium group-hover:text-brand-primary transition-colors text-sm md:text-base">
                Chứng từ quyết toán thuế
              </span>
            </div>
            <Icon name="lucide:chevron-right" class="text-gray-400 group-hover:translate-x-0.5 transition-transform" size="16" />
          </NuxtLink>

          <!-- Văn bản đề nghị hoàn thuế -->
          <NuxtLink 
            to="/quan-ly-xe/hop-dong-chung-tu/hoan-thue"
            class="group flex items-center justify-between py-3.5 hover:bg-slate-50/50 px-1 rounded-xl transition duration-150"
          >
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-slate-600 group-hover:bg-brand-secondary group-hover:text-brand-primary transition-colors">
                <Icon name="lucide:file-text" size="16" />
              </div>
              <span class="text-slate-700 font-medium group-hover:text-brand-primary transition-colors text-sm md:text-base">
                Văn bản đề nghị hoàn thuế
              </span>
            </div>
            <Icon name="lucide:chevron-right" class="text-gray-400 group-hover:translate-x-0.5 transition-transform" size="16" />
          </NuxtLink>
        </div>
      </div>

    </div>
  </div>
</template>

<script lang="ts" setup>
definePageMeta({
  layout: "my-cars",
});

const { showToast } = useToast();

// Hàm xử lý khi bấm xem hoặc tải file hợp đồng mẫu
const viewContract = (type: string) => {
  console.log(`Đang yêu cầu xem tài liệu mẫu loại: ${type}`);
  showToast(`Hệ thống đang chuẩn bị tệp tài liệu: [${type === 'lease' ? 'Hợp đồng cho thuê' : 'Biên bản bàn giao xe'}].`, 'info');
};
</script>
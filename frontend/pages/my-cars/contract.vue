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
            href="http://localhost:3000/files/00-HDCTXTL-Ver8-26-02-07.docx"
            download
            class="inline-flex items-center gap-2 bg-[#53cf84] text-white px-5 py-3.5 rounded-xl font-medium transition duration-200 hover:bg-[#43bd73] shadow-sm text-sm"
          >
            <Icon name="lucide:file-text" size="18" />
            Hợp đồng cho thuê
          </a>

          <!-- Nút Biên bản bàn giao xe -->
          <a 
            href="http://localhost:3000/files/Bien-ban-ban-giao-xe-v4-10-06.docx"
            download
            class="inline-flex items-center gap-2 bg-[#53cf84] text-white px-5 py-3.5 rounded-xl font-medium transition duration-200 hover:bg-[#43bd73] shadow-sm text-sm"
          >
            <Icon name="lucide:car" size="18" />
            Biên bản bàn giao xe
          </a>
        </div>
      </div>

    </div>
  </div>
</template>

<script lang="ts" setup>
definePageMeta({
  layout: "my-cars",
});

</script>
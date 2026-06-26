<template>
  <div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-50 max-w-7xl">
      
      <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
        <Icon name="lucide:book-open" class="text-brand-primary w-5 h-5" />
        Cẩm nang cho thuê xe
      </h2>

      <div class="divide-y divide-gray-100">
        
        <NuxtLink 
          to="https://drive.google.com/file/d/1NaUHX_mosrPt1657Jb207_e_QXzfiKep/view?usp=sharing"
          target="_blank"
          class="group flex items-center justify-between py-4 hover:bg-slate-50/50 px-2 rounded-xl transition duration-200"
        >
          <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-brand-secondary flex items-center justify-center text-brand-primary shrink-0">
              <Icon name="lucide:file-text" class="w-5 h-5" />
            </div>
            <span class="text-slate-700 font-medium group-hover:text-brand-primary transition-colors">
              Quy trình cho thuê xe
            </span>
          </div>
          <Icon name="lucide:chevron-right" class="text-gray-400 group-hover:translate-x-0.5 transition-transform" size="18" />
        </NuxtLink>

        <NuxtLink 
          to="https://drive.google.com/file/d/1YRbXpSunbp3LDFr4qVh9p_fLi69pmcPu/view?usp=drive_link"
          target="_blank"
          class="group flex items-center justify-between py-4 hover:bg-slate-50/50 px-2 rounded-xl transition duration-200"
        >
          <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-brand-secondary flex items-center justify-center text-brand-primary shrink-0">
              <Icon name="lucide:calculator" class="w-5 h-5" />
            </div>
            <span class="text-slate-700 font-medium group-hover:text-brand-primary transition-colors">
              Cẩm nang quyết toán thuế
            </span>
          </div>
          <Icon name="lucide:chevron-right" class="text-gray-400 group-hover:translate-x-0.5 transition-transform" size="18" />
        </NuxtLink>

        <div class="py-4 px-2">
          <div class="flex items-center gap-3.5 mb-3">
            <div class="w-10 h-10 rounded-xl bg-brand-secondary flex items-center justify-center text-brand-primary shrink-0">
              <Icon name="lucide:bell" class="w-5 h-5" />
            </div>
            <span class="text-slate-700 font-medium">
              Cập nhật thông tin mới
            </span>
          </div>

          <div class="pl-14">
            <NuxtLink 
              to="https://drive.google.com/file/d/1WIJb5Yk7aI34R5Iyuz_N3lzkPmLCkXy9/view?usp=drive_link"
              target="_blank"
              class="group inline-flex items-center gap-2 text-sm text-slate-500 hover:text-brand-primary transition-colors py-1.5"
            >
              <span class="w-1.5 h-1.5 rounded-full bg-gray-400 group-hover:bg-brand-primary transition-colors"></span>
              Nghị định 336/2025/NĐ-CP
              <Icon name="lucide:chevron-right" class="w-3.5 h-3.5 text-gray-400 opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all" />
            </NuxtLink>
          </div>
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
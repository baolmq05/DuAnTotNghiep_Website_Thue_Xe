<template>
  <aside class="col-span-12 lg:col-span-3">
    <div class="sticky top-24 bg-white rounded-3xl border border-gray-100 p-4 sm:p-6 shadow-sm">
      <h3 class="font-bold text-gray-900 mb-3 text-sm sm:text-base border-b border-gray-100 pb-2">
        Nội dung chính sách
      </h3>
      <ul class="grid grid-cols-1 gap-1.5">
        <li>
          <NuxtLink 
            to="/policy" 
            class="flex items-center text-xs sm:text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-3.5 py-2.5 rounded-xl transition-all duration-200"
            exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3.5 rounded-l-none rounded-r-xl shadow-2xs"
          >
            Chính sách và quy định
          </NuxtLink>
        </li>
        <li>
          <NuxtLink 
            to="/policy/term" 
            class="flex items-center text-xs sm:text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-3.5 py-2.5 rounded-xl transition-all duration-200"
            exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3.5 rounded-l-none rounded-r-xl shadow-2xs"
          >
            Nguyên tắc chung
          </NuxtLink>
        </li>
        <li>
          <NuxtLink 
            to="/policy/personalinfo" 
            class="flex items-center text-xs sm:text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-3.5 py-2.5 rounded-xl transition-all duration-200"
            exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3.5 rounded-l-none rounded-r-xl shadow-2xs"
          >
            Chính sách BVDLCN
          </NuxtLink>
        </li>
        <li>
          <NuxtLink 
            to="/policy/candidateprivacy" 
            class="flex items-center text-xs sm:text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-3.5 py-2.5 rounded-xl transition-all duration-200"
            exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3.5 rounded-l-none rounded-r-xl shadow-2xs"
          >
            DLCN của ứng viên
          </NuxtLink>
        </li>
        <li>
          <NuxtLink 
            to="/policy/resolveconflic" 
            class="flex items-center text-xs sm:text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-3.5 py-2.5 rounded-xl transition-all duration-200"
            exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3.5 rounded-l-none rounded-r-xl shadow-2xs"
          >
            Giải quyết khiếu nại
          </NuxtLink>
        </li>
        <li>
          <NuxtLink 
            to="/policy/inspection-completion" 
            class="flex items-center text-xs sm:text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-3.5 py-2.5 rounded-xl transition-all duration-200"
            exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3.5 rounded-l-none rounded-r-xl shadow-2xs"
          >
            Kiểm tra xe & Hoàn thành
          </NuxtLink>
        </li>
      </ul>
    </div>
  </aside>
</template>

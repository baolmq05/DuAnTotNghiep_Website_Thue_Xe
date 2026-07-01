<template>
  <section class="relative bg-[#0f3d46] overflow-x-hidden pb-10">

    <!-- Banner -->
    <div class="relative h-auto lg:h-[640px] pb-12 lg:pb-0">


      <!-- Background -->
      <img
        src="/images/index/banner/image1.psd.webp"
        alt="Banner"
        class="absolute inset-0 h-full w-full object-cover"
      >

      <!-- Overlay -->
      <div
        class="absolute inset-0 bg-gradient-to-r from-[#0f3d46]/85 via-[#0f3d46]/45 to-transparent"
      />

      <!-- Content -->
      <div
        class="relative mx-auto flex h-full max-w-7xl flex-col lg:flex-row lg:items-center px-6 lg:px-8 pt-24 pb-6 lg:pt-0 lg:pb-0"
      >
        <div class="max-w-2xl text-white">

          <span
            class="text-brand-accent uppercase tracking-wider font-extrabold text-[14px] sm:text-[15px] mb-4 block"
          >
            THUÊ XE DỄ DÀNG - TRẢI NGHIỆM XỨNG TẦM
          </span>

          <h1
            class="text-4xl font-extrabold leading-tight text-white sm:text-5xl lg:text-[60px] tracking-tight"
          >
            Khám phá mọi hành trình<br />
            cùng <span class="text-brand-accent">Drivio</span>
          </h1>

          <p
            class="mt-6 text-base sm:text-lg leading-relaxed text-white/90 font-medium max-w-xl"
          >
            Đa dạng dòng xe, giá tốt mỗi ngày,<br class="hidden sm:inline" />
            thủ tục đơn giản, sẵn sàng cho mọi chuyến đi của bạn.
          </p>

        </div>

        <!-- Search Bar (Mobile only, shown inline below the text) -->
        <div class="w-full mt-8 block lg:hidden">
          <HomePageSearchBar />
        </div>
      </div>

      <!-- Search Bar (Desktop only) -->
      <div
        class="hidden lg:block absolute bottom-0 left-1/2 z-30 w-full max-w-7xl -translate-x-1/2 translate-y-1/2 px-6"
      >
        <HomePageSearchBar />
      </div>
    </div>

    <!-- Benefits Row -->
    <div class="relative z-20 mx-auto max-w-7xl px-6 lg:px-8 pt-8 lg:pt-20 pb-4">
      <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Benefit 1 -->
        <div class="flex items-center gap-4 text-white">
          <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full border border-white/20 bg-white/5 shadow-sm">
            <Icon name="lucide:car" class="h-6 w-6 text-white" />
          </div>
          <div>
            <h4 class="font-bold text-[15px] leading-tight">Đa dạng dòng xe</h4>
            <p class="text-xs text-white/60 mt-1 leading-snug">Từ 4 chỗ đến 7 chỗ, cao cấp</p>
          </div>
        </div>

        <!-- Benefit 2 -->
        <div class="flex items-center gap-4 text-white">
          <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full border border-white/20 bg-white/5 shadow-sm">
            <Icon name="lucide:shield-check" class="h-6 w-6 text-white" />
          </div>
          <div>
            <h4 class="font-bold text-[15px] leading-tight">Giá cả minh bạch</h4>
            <p class="text-xs text-white/60 mt-1 leading-snug">Không phí ẩn, không phát sinh</p>
          </div>
        </div>

        <!-- Benefit 3 -->
        <div class="flex items-center gap-4 text-white">
          <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full border border-white/20 bg-white/5 shadow-sm">
            <Icon name="heroicons:phone" class="h-6 w-6 text-white" />
          </div>
          <div>
            <h4 class="font-bold text-[15px] leading-tight">Hỗ trợ 24/7</h4>
            <p class="text-xs text-white/60 mt-1 leading-snug">Tư vấn nhanh chóng, tận tâm</p>
          </div>
        </div>

        <!-- Benefit 4 -->
        <div class="flex items-center gap-4 text-white">
          <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full border border-white/20 bg-white/5 shadow-sm">
            <Icon name="lucide:file-text" class="h-6 w-6 text-white" />
          </div>
          <div>
            <h4 class="font-bold text-[15px] leading-tight">Thủ tục đơn giản</h4>
            <p class="text-xs text-white/60 mt-1 leading-snug">Nhanh gọn, nhận xe trong 5 phút</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
img {
  user-select: none;
  -webkit-user-drag: none;
}

h1 {
  text-wrap: balance;
}
</style>
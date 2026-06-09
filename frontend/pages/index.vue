<template>
  <div class="min-h-screen flex flex-col bg-[#f4f7f6]">
    <!-- Banner Section -->
    <BannerHero />
    
    <!-- Promotional Benefits Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="text-center max-w-xl mx-auto mb-12">
        <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Tại Sao Chọn DRIVIO?</h2>
        <p class="text-slate-600 mt-3 text-sm md:text-base">Chúng tôi mang lại giải pháp chia sẻ xe tự lái nhanh chóng, an toàn và tối ưu chi phí cho mọi hành trình.</p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Benefit 1 -->
        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group">
          <div class="w-12 h-12 rounded-xl bg-brand-light/50 text-brand-primary flex items-center justify-center mb-6 group-hover:bg-brand-primary group-hover:text-white transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <h3 class="text-lg font-bold text-slate-800 mb-2">Thủ tục nhanh gọn</h3>
          <p class="text-sm text-slate-600 leading-relaxed">Xác minh thông tin nhanh chóng qua căn cước công dân gắn chip hoặc bằng lái xe. Đặt xe chỉ trong 5 phút thao tác.</p>
        </div>
        
        <!-- Benefit 2 -->
        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group">
          <div class="w-12 h-12 rounded-xl bg-brand-light/50 text-brand-primary flex items-center justify-center mb-6 group-hover:bg-brand-primary group-hover:text-white transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-lg font-bold text-slate-800 mb-2">Chi phí tối ưu</h3>
          <p class="text-sm text-slate-600 leading-relaxed">Giá thuê xe hiển thị minh bạch. Không có bất cứ chi phí ẩn phát sinh nào trong suốt quá trình sử dụng xe.</p>
        </div>

        <!-- Benefit 3 -->
        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 group">
          <div class="w-12 h-12 rounded-xl bg-brand-light/50 text-brand-primary flex items-center justify-center mb-6 group-hover:bg-brand-primary group-hover:text-white transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <h3 class="text-lg font-bold text-slate-800 mb-2">Hỗ trợ 24/7 chuyên nghiệp</h3>
          <p class="text-sm text-slate-600 leading-relaxed">Đội ngũ chăm sóc khách hàng và cứu hộ khẩn cấp của chúng tôi luôn trực tuyến để xử lý nhanh chóng mọi tình huống.</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script lang="ts" setup>
useHead({
  title: 'DRIVIO - Dịch vụ chia sẻ xe tự lái cao cấp hàng đầu Việt Nam',
  meta: [
    { name: 'description', content: 'Đặt xe tự lái 4 chỗ, 7 chỗ, xe điện, xe bán tải và xe hạng sang nhanh chóng với giá cả ưu đãi. Đảm bảo thủ tục tiện lợi, hỗ trợ chu đáo.' }
  ]
})
</script>

<style scoped>
</style>
<template>
    <section
        class="relative isolate overflow-hidden bg-gradient-to-br from-brand-dark via-[#1a4b56] to-brand-primary min-h-[600px] flex items-center">

        <!-- Hiệu ứng ánh sáng gradient nền & Hình ảnh mờ nhẹ -->
        <div class="absolute inset-0 z-0 pointer-events-none">
            <img src="/images/about/aboutHero.jpg" alt="Background" loading="eager" fetchpriority="high"
                decoding="async" class="w-full h-full object-cover opacity-[0.16] mix-blend-overlay" />
            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/65 via-brand-primary/35 to-transparent" />
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-cyan-500/10 blur-[120px]" />
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-teal-500/10 blur-[120px]" />
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- Cột bên trái: Title, Description, Button Actions -->
                <div class="lg:col-span-6 text-left space-y-6 max-w-2xl mx-auto lg:mx-0">
                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-black text-white leading-tight">
                        Về chúng tôi
                    </h1>

                    <p class="text-lg text-gray-200/90 leading-relaxed">
                        Chúng tôi kết nối khách hàng với hàng trăm dòng xe chất lượng, mang đến trải nghiệm thuê xe
                        nhanh chóng, an toàn và tiện lợi cho mọi nhu cầu di chuyển
                    </p>

                    <div class="flex flex-wrap gap-4 pt-4">
                        <NuxtLink to="/"
                            class="px-8 py-4 bg-white text-[#286874] rounded-xl font-bold shadow-lg shadow-black/10 hover:shadow-xl hover:bg-cyan-50 hover:-translate-y-0.5 transition-all duration-200">
                            Thuê Xe Ngay
                        </NuxtLink>

                        <NuxtLink to="/"
                            class="px-8 py-4 border border-white/40 text-white rounded-xl font-semibold backdrop-blur-sm hover:bg-white/10 hover:border-white hover:-translate-y-0.5 transition-all duration-200">
                            Liên Hệ
                        </NuxtLink>
                    </div>
                </div>

                <!-- Cột bên phải: LottieAnimation -->
                <div class="lg:col-span-6 flex justify-center items-center w-full max-w-[550px] lg:max-w-none mx-auto">
                    <ClientOnly>
                        <LottieAnimation class="w-full h-auto" path="/animations/car_riving_landscape.json" />
                    </ClientOnly>
                </div>

            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import LottieAnimation from '~/components/Common/LottieAnimation.vue'
</script>

<style scoped></style>

<template>
    <section
        class="relative isolate overflow-hidden bg-gradient-to-br from-brand-dark via-[#1a4b56] to-brand-primary min-h-[500px] flex items-center">

        <!-- Background effects & overlay image -->
        <div class="absolute inset-0 z-0 pointer-events-none">
            <img src="/images/about/aboutHero.jpg" alt="Support Background" loading="eager" fetchpriority="high"
                decoding="async" class="w-full h-full object-cover opacity-[0.12] mix-blend-overlay" />
            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/75 via-brand-primary/45 to-transparent" />
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-cyan-500/10 blur-[120px]" />
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-teal-500/10 blur-[120px]" />
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16 items-center">

                <!-- Left Column: Title, Description, Quick Actions -->
                <div class="lg:col-span-7 text-left space-y-6 max-w-2xl mx-auto lg:mx-0">
                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-black text-white leading-tight">
                        Liên hệ & Hỗ trợ
                    </h1>

                    <p class="text-base md:text-lg text-gray-200/90 leading-relaxed">
                        Bạn có câu hỏi, đóng góp ý kiến hoặc cần hỗ trợ kỹ thuật? Đội ngũ hỗ trợ của DRIVIO luôn sẵn
                        sàng đồng hành cùng bạn trên mọi cung đường.
                    </p>

                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="#contact-form"
                            class="px-6 py-3.5 bg-white text-[#286874] rounded-xl font-bold shadow-lg shadow-black/10 hover:shadow-xl hover:bg-cyan-50 hover:-translate-y-0.5 transition-all duration-200">
                            Gửi tin nhắn
                        </a>

                        <a href="#faq"
                            class="px-6 py-3.5 border border-white/40 text-white rounded-xl font-semibold backdrop-blur-sm hover:bg-white/10 hover:border-white hover:-translate-y-0.5 transition-all duration-200">
                            Câu hỏi thường gặp
                        </a>
                    </div>
                </div>

                <!-- Right Column: LottieAnimation -->
                <div class="lg:col-span-5 flex justify-center items-center w-full max-w-[400px] lg:max-w-none mx-auto">
                    <ClientOnly>
                        <LottieAnimation class="w-full h-auto" path="/animations/waiting_car.json" />
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

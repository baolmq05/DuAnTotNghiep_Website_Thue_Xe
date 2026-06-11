<template>
    <div class="min-h-screen flex flex-col bg-white overflow-x-hidden">

        <!-- HERO -->
        <section
            class="relative overflow-hidden bg-gradient-to-r from-[#1e4e57] to-[#286874] min-h-[400px] flex items-center">
            <div class="absolute inset-0 z-0">
                <img src="/images/about/aboutHero.jpg"
                    class="w-full h-full object-cover opacity-25 mix-blend-overlay" />
            </div>

            <div class="relative z-10 max-w-3xl mx-auto px-4 py-24 text-center">
                <span class="inline-block text-xs font-bold uppercase tracking-[0.2em] text-teal-300 mb-4">Blog ·
                    Drivio</span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight">
                    Kinh Nghiệm Thuê Xe<br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-teal-200">Tự Lái Thông
                        Minh</span>
                </h1>
                <p class="mt-5 text-base md:text-lg text-cyan-50/85 leading-relaxed max-w-2xl mx-auto">
                    Mẹo chọn xe, hướng dẫn đặt chỗ, đánh giá các mẫu xe phổ biến và những lưu ý giúp bạn có
                    chuyến đi <strong class="text-white font-semibold">an toàn, tiết kiệm và chủ động</strong> hơn bao
                    giờ hết.
                </p>
            </div>
        </section>

        <!-- CONTENT -->
        <section class="max-w-7xl mx-auto px-4 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- POSTS -->
                <div class="lg:col-span-2 order-2 lg:order-1">
                    <h2 class="text-2xl font-black mb-6">Bài viết gần đây</h2>

                    <!-- List Blog — style giống HomePage/Blog.vue -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 auto-rows-[260px]">

                        <!-- Card 1 -->
                        <a href="#"
                            class="text-white p-6 rounded-2xl flex flex-col justify-end relative overflow-hidden group shadow-md">
                            <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80"
                                alt="Hướng dẫn thuê xe"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent">
                            </div>
                            <div class="z-10">
                                <span
                                    class="inline-block text-xs font-bold uppercase tracking-widest text-teal-300 mb-2">Hướng
                                    dẫn</span>
                                <h2 class="text-lg font-bold uppercase tracking-wide leading-tight">
                                    5 bước đặt xe tự lái dễ dàng cho người mới
                                </h2>
                                <div class="w-10 h-0.5 bg-white my-3 opacity-60"></div>
                                <p class="text-sm text-slate-300 font-medium">bởi Admin Drivio</p>
                            </div>
                        </a>

                        <!-- Card 2 -->
                        <a href="#"
                            class="text-white p-6 rounded-2xl flex flex-col justify-end relative overflow-hidden group shadow-md">
                            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80"
                                alt="Mẹo thuê xe"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                            </div>
                            <div class="z-10">
                                <span
                                    class="inline-block text-xs font-bold uppercase tracking-widest text-teal-300 mb-2">Mẹo
                                    & Thủ thuật</span>
                                <h2 class="text-lg font-bold uppercase tracking-wide leading-tight">
                                    15 tỉnh thành đẹp nhất để khám phá bằng xe tự lái
                                </h2>
                                <div class="w-10 h-0.5 bg-white my-3 opacity-60"></div>
                                <p class="text-sm text-slate-300 font-medium">bởi Davey Jones</p>
                            </div>
                        </a>

                        <!-- Card 3 -->
                        <a href="#"
                            class="text-white p-6 rounded-2xl flex flex-col justify-end relative overflow-hidden group shadow-md">
                            <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80"
                                alt="Đánh giá xe"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                            </div>
                            <div class="z-10">
                                <span
                                    class="inline-block text-xs font-bold uppercase tracking-widest text-teal-300 mb-2">Đánh
                                    giá xe</span>
                                <h2 class="text-lg font-bold uppercase tracking-wide leading-tight">
                                    Review Toyota Innova — Lựa chọn gia đình số 1
                                </h2>
                                <div class="w-10 h-0.5 bg-white my-3 opacity-60"></div>
                                <p class="text-sm text-slate-300 font-medium">bởi Maria Gonzalez</p>
                            </div>
                        </a>

                        <!-- Card 4 -->
                        <a href="#"
                            class="text-white p-6 rounded-2xl flex flex-col justify-end relative overflow-hidden group shadow-md">
                            <img src="https://images.unsplash.com/photo-1527631746610-bca00a040d60?auto=format&fit=crop&w=800&q=80"
                                alt="Khuyến mãi"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                            </div>
                            <div class="z-10">
                                <span
                                    class="inline-block text-xs font-bold uppercase tracking-widest text-teal-300 mb-2">Khuyến
                                    mãi</span>
                                <h2 class="text-lg font-bold uppercase tracking-wide leading-tight">
                                    Ưu đãi hè 2025 — Giảm 20% mọi chuyến đi nội thành
                                </h2>
                                <div class="w-10 h-0.5 bg-white my-3 opacity-60"></div>
                                <p class="text-sm text-slate-300 font-medium">bởi Davey Jones</p>
                            </div>
                        </a>

                        <!-- Card 5 -->
                        <a href="#"
                            class="text-white p-6 rounded-2xl flex flex-col justify-end relative overflow-hidden group shadow-md">
                            <img src="https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=800&q=80"
                                alt="Hành trình"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                            </div>
                            <div class="z-10">
                                <span
                                    class="inline-block text-xs font-bold uppercase tracking-widest text-teal-300 mb-2">Hành
                                    trình</span>
                                <h2 class="text-lg font-bold uppercase tracking-wide leading-tight">
                                    Top 10 cung đường đẹp nhất miền Trung Việt Nam
                                </h2>
                                <div class="w-10 h-0.5 bg-white my-3 opacity-60"></div>
                                <p class="text-sm text-slate-300 font-medium">bởi Bruce Leeroy</p>
                            </div>
                        </a>

                        <!-- Card 6 -->
                        <a href="#"
                            class="text-white p-6 rounded-2xl flex flex-col justify-end relative overflow-hidden group shadow-md">
                            <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80"
                                alt="Kinh nghiệm"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                            </div>
                            <div class="z-10">
                                <span
                                    class="inline-block text-xs font-bold uppercase tracking-widest text-teal-300 mb-2">Kinh
                                    nghiệm</span>
                                <h2 class="text-lg font-bold uppercase tracking-wide leading-tight">
                                    Bí kíp chọn xe phù hợp cho chuyến road trip dài ngày
                                </h2>
                                <div class="w-10 h-0.5 bg-white my-3 opacity-60"></div>
                                <p class="text-sm text-slate-300 font-medium">bởi BanneLee</p>
                            </div>
                        </a>

                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-8">
                        <nav aria-label="Page navigation example">
                            <ul class="list-style-none flex items-center gap-2">
                                <li>
                                    <a class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none focus:ring-0 active:bg-neutral-100 active:text-primary-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
                                        href="#">
                                        <Icon name="ri:arrow-left-s-line" size="24" />
                                    </a>
                                </li>
                                <li>
                                    <a class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none active:bg-neutral-100 active:text-primary-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
                                        href="#">1</a>
                                </li>
                                <li aria-current="page">
                                    <a class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none active:bg-neutral-100 active:text-primary-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
                                        href="#">2</a>
                                </li>
                                <li>
                                    <a class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none active:bg-neutral-100 active:text-primary-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
                                        href="#">3</a>
                                </li>
                                <li>
                                    <a class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none active:bg-neutral-100 active:text-primary-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:focus:text-primary-500 dark:active:bg-neutral-700 dark:active:text-primary-500"
                                        href="#">
                                        <Icon name="ri:arrow-right-s-line" size="24" />
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- SIDEBAR -->
                <div class="order-1 lg:order-2">
                    <h3 class="text-xl font-bold">Danh mục</h3>
                    <hr class="mt-3 mb-5 h-[0.5px] bg-gray-500">
                    <!-- SEARCH -->
                    <div class="mb-6 relative">
                        <input type="text" placeholder="Tìm bài viết..."
                            class="w-full pl-10 pr-4 py-2 border rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />

                        <!-- ICON -->
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M16 10a6 6 0 11-12 0 6 6 0 0112 0z" />
                        </svg>
                    </div>
                    <!-- Category -->

                    <div class="flex flex-wrap gap-3">
                        <!-- CATEGORY BUTTON -->
                        <button type="button"
                            class="inline-flex items-center gap-2 rounded-full border-2 border-blue-500 text-blue-500 hover:border-blue-600 hover:bg-blue-400 hover:bg-opacity-10 hover:text-blue-600 px-4 py-2 text-xs font-medium uppercase transition">
                            Tất cả
                        </button>
                        <button type="button"
                            class="inline-flex items-center gap-2 rounded-full border-2 border-blue-500 text-blue-500 hover:border-blue-600 hover:bg-blue-400 hover:bg-opacity-10 hover:text-blue-600 px-4 py-2 text-xs font-medium uppercase transition">
                            Hướng dẫn
                        </button>
                        <button type="button"
                            class="inline-flex items-center gap-2 rounded-full border-2 border-blue-500 text-blue-500 hover:border-blue-600 hover:bg-blue-400 hover:bg-opacity-10 hover:text-blue-600 px-4 py-2 text-xs font-medium uppercase transition">
                            Mẹo và Thủ thuật
                        </button>
                        <button type="button"
                            class="inline-flex items-center gap-2 rounded-full border-2 border-blue-500 text-blue-500 hover:border-blue-600 hover:bg-blue-400 hover:bg-opacity-10 hover:text-blue-600 px-4 py-2 text-xs font-medium uppercase transition">
                            Đánh giá xe
                        </button>
                        <button type="button"
                            class="inline-flex items-center gap-2 rounded-full border-2 border-blue-500 text-blue-500 hover:border-blue-600 hover:bg-blue-400 hover:bg-opacity-10 hover:text-blue-600 px-4 py-2 text-xs font-medium uppercase transition">
                            Khuyến mãi
                        </button>
                        <button type="button"
                            class="inline-flex items-center gap-2 rounded-full border-2 border-blue-500 text-blue-500 hover:border-blue-600 hover:bg-blue-400 hover:bg-opacity-10 hover:text-blue-600 px-4 py-2 text-xs font-medium uppercase transition">
                            Tất cả
                        </button>
                    </div>

                    <!-- Popular Posts -->
                    <h3 class="mt-10 text-xl font-bold">Bài viết nổi bật</h3>
                    <hr class="mt-3 mb-5 h-[0.5px] bg-gray-500">
                    <div class="flex flex-col gap-3">

                        <!-- Item -->
                        <div class="flex gap-3">
                            <div class="w-[30%] border rounded-md relative overflow-hidden">
                                <img src="https://tecdn.b-cdn.net/img/new/standard/nature/184.jpg"
                                    class="w-full h-full object-cover" />
                                <div class="absolute inset-0 bg-gradient-to-r from-[#1e4e57] to-[#286874] opacity-25">
                                </div>
                            </div>
                            <div class="w-[70%] flex flex-col justify-center gap-2">
                                <h5 class="flex items-center justify-start text-neutral-500 dark:text-neutral-300">
                                    <span class="mr-2">
                                        Category
                                    </span>
                                    <span
                                        class="inline-block whitespace-nowrap rounded-[0.27rem] bg-blue-100 px-[0.65em] pb-[0.25em] pt-[0.35em] text-center align-baseline text-[0.75em] font-bold leading-none text-blue-700">
                                        New
                                    </span>
                                </h5>
                                <h3 class="font-bold text-lg"><a href="#">Explore the hidden beauty</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</template>

<script setup lang="ts">
useSeoMeta({
    title: 'Blog Thuê Xe Ô Tô Tự Lái | Kinh Nghiệm, Mẹo & Đánh Giá Xe — Drivio',
    description:
        'Khám phá bài viết về kinh nghiệm thuê xe ô tô tự lái, mẹo chọn xe phù hợp, đánh giá các mẫu xe phổ biến và hướng dẫn đặt xe nhanh chóng tại Drivio.',
    ogTitle: 'Blog Thuê Xe Ô Tô Tự Lái | Drivio',
    ogDescription:
        'Mẹo chọn xe, kinh nghiệm đi đường dài, đánh giá xe và khuyến mãi thuê xe tự lái — tất cả tại Blog Drivio.',
    ogImage: '/images/about/aboutHero.jpg',
    twitterCard: 'summary_large_image',
})
</script>
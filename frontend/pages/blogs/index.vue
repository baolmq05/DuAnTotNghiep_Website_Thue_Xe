<template>
    <div class="min-h-screen flex flex-col bg-white overflow-x-hidden">
        <!-- Full-screen Loading Overlay -->
        <CommonLoadingOverlay :loading="loading" text="Đang tải danh sách bài viết" />

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

                    <div v-if="!loading && posts.length === 0" class="w-full py-16 px-4 flex flex-col items-center justify-center text-center bg-slate-50/50 rounded-3xl border border-dashed border-slate-200 lg:col-span-2">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center text-teal-600 animate-pulse">
                                <Icon name="ri:article-line" size="40" />
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center text-white border-2 border-white shadow-sm">
                                <Icon name="ri:compass-discover-line" size="12" />
                            </div>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Không tìm thấy bài viết</h3>
                        <p class="text-gray-500 max-w-md mb-6 text-sm md:text-base leading-relaxed">
                            Chúng tôi không tìm thấy bài viết nào phù hợp với danh mục hoặc từ khóa tìm kiếm của bạn. Hãy thử tìm kiếm với từ khóa khác nhé!
                        </p>
                    </div>
                    <div v-else-if="posts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-6 auto-rows-[260px]">
                        <NuxtLink v-for="post in posts" :key="post.id" :to="`/blogs/${post.id}`"
                            class="text-white p-6 rounded-2xl flex flex-col justify-end relative overflow-hidden group shadow-md">
                            <img :src="getThumbnailUrl(post.thumbnail)"
                                :alt="post.title"
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent">
                            </div>
                            <div class="z-10">
                                <span
                                    class="inline-block text-xs font-bold uppercase tracking-widest text-teal-300 mb-2">
                                    {{ post.category?.name || 'Tin tức' }}
                                </span>
                                <h2 class="text-lg font-bold uppercase tracking-wide leading-tight line-clamp-2">
                                    {{ post.title }}
                                </h2>
                                <div class="w-10 h-0.5 bg-white my-3 opacity-60"></div>
                                <p class="text-sm text-slate-300 font-medium">bởi {{ post.user?.name || 'Admin' }}</p>
                            </div>
                        </NuxtLink>
                    </div>

                    <!-- Pagination -->
                    <div v-if="totalPages > 1" class="flex justify-center mt-8">
                        <nav aria-label="Page navigation">
                            <ul class="list-style-none flex items-center gap-2">
                                <li>
                                    <button 
                                        @click="changePage(currentPage - 1)"
                                        :disabled="currentPage === 1"
                                        class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 disabled:opacity-50 disabled:hover:bg-transparent dark:text-white dark:hover:bg-neutral-700"
                                    >
                                        <Icon name="ri:arrow-left-s-line" size="24" />
                                    </button>
                                </li>
                                <li v-for="page in totalPages" :key="page">
                                    <button 
                                        @click="changePage(page)"
                                        class="relative block rounded px-3 py-1.5 text-sm font-semibold transition duration-300 focus:outline-none"
                                        :class="page === currentPage ? 'bg-teal-500 text-white shadow-sm' : 'bg-transparent text-neutral-700 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700'"
                                    >
                                        {{ page }}
                                    </button>
                                </li>
                                <li>
                                    <button 
                                        @click="changePage(currentPage + 1)"
                                        :disabled="currentPage === totalPages"
                                        class="relative block rounded bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 disabled:opacity-50 disabled:hover:bg-transparent dark:text-white dark:hover:bg-neutral-700"
                                    >
                                        <Icon name="ri:arrow-right-s-line" size="24" />
                                    </button>
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
                        <input 
                            type="text" 
                            :value="searchQuery"
                            @input="handleSearchInput"
                            placeholder="Tìm bài viết..."
                            class="w-full pl-10 pr-4 py-2 border rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" 
                        />
                        <!-- ICON -->
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M16 10a6 6 0 11-12 0 6 6 0 0112 0z" />
                        </svg>
                    </div>

                    <!-- Category -->
                    <div class="flex flex-wrap gap-3">
                        <button 
                            type="button"
                            @click="filterByCategory('')"
                            class="inline-flex items-center gap-2 rounded-full border-2 px-4 py-2 text-xs font-medium uppercase transition"
                            :class="selectedCategoryId === '' ? 'border-teal-500 bg-teal-500 text-white hover:bg-teal-600' : 'border-teal-500 text-teal-600 hover:border-teal-600 hover:bg-teal-400/10 hover:text-teal-600'"
                        >
                            Tất cả
                        </button>
                        <button 
                            v-for="cat in categories" 
                            :key="cat.id"
                            type="button"
                            @click="filterByCategory(cat.id)"
                            class="inline-flex items-center gap-2 rounded-full border-2 px-4 py-2 text-xs font-medium uppercase transition"
                            :class="selectedCategoryId === cat.id ? 'border-teal-500 bg-teal-500 text-white hover:bg-teal-600' : 'border-teal-500 text-teal-600 hover:border-teal-600 hover:bg-teal-400/10 hover:text-teal-600'"
                        >
                            {{ cat.name }}
                        </button>
                    </div>

                    <!-- Popular Posts -->
                    <div v-if="popularPosts.length > 0">
                        <h3 class="mt-10 text-xl font-bold">Bài viết nổi bật</h3>
                        <hr class="mt-3 mb-5 h-[0.5px] bg-gray-500">
                        <div class="flex flex-col gap-4">
                            <div v-for="pop in popularPosts" :key="pop.id" class="flex gap-3 h-20">
                                <NuxtLink :to="`/blogs/${pop.id}`" class="w-[30%] border rounded-md relative overflow-hidden flex-shrink-0">
                                    <img :src="getThumbnailUrl(pop.thumbnail)"
                                        class="w-full h-full object-cover" />
                                    <div class="absolute inset-0 bg-gradient-to-r from-[#1e4e57] to-[#286874] opacity-25">
                                    </div>
                                </NuxtLink>
                                <div class="w-[70%] flex flex-col justify-center">
                                    <h5 class="flex items-center justify-start text-neutral-500 text-xs mb-1">
                                        <span class="mr-2 font-medium">
                                            {{ pop.category?.name || 'Tin tức' }}
                                        </span>
                                    </h5>
                                    <h3 class="font-bold text-sm leading-snug line-clamp-2">
                                        <NuxtLink :to="`/blogs/${pop.id}`" class="hover:text-teal-600 transition">
                                            {{ pop.title }}
                                        </NuxtLink>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { postService, type Post, type PostCategory } from '~/services/post.service'
import { BASE_URL } from '~/enviroment/enviroment'

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

const posts = ref<Post[]>([])
const categories = ref<PostCategory[]>([])
const popularPosts = ref<Post[]>([])
const selectedCategoryId = ref<number | string>('')
const searchQuery = ref('')
const currentPage = ref(1)
const totalPages = ref(1)
const loading = ref(false)

// Thao tác tìm kiếm với debounce đơn giản
let searchTimeout: any = null
const handleSearchInput = (e: Event) => {
    const target = e.target as HTMLInputElement
    searchQuery.value = target.value
    currentPage.value = 1
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        fetchPosts()
    }, 400)
}

// Gọi API lấy bài viết
const fetchPosts = async (useOverlay = true) => {
    if (useOverlay) loading.value = true
    try {
        console.log('fetchPosts: Đang gọi API lấy danh sách bài viết...')
        const res = await postService.getPostsApi({
            page: currentPage.value,
            category_id: selectedCategoryId.value,
            search: searchQuery.value,
            limit: 6
        })
        console.log('fetchPosts: Phản hồi từ API:', res)
        if (res.success && res.data) {
            posts.value = res.data.data
            console.log('fetchPosts: Đã lưu danh sách bài viết:', posts.value)
            totalPages.value = res.data.last_page
        }
    } catch (err) {
        console.error('Lỗi khi lấy danh sách bài viết:', err)
    } finally {
        if (useOverlay) loading.value = false
    }
}

// Gọi API danh mục bài viết
const fetchCategories = async () => {
    try {
        console.log('fetchCategories: Đang gọi API lấy danh mục...')
        const res = await postService.getCategoriesApi()
        console.log('fetchCategories: Phản hồi danh mục từ API:', res)
        if (res.success && res.data) {
            categories.value = res.data
            console.log('fetchCategories: Đã lưu danh mục:', categories.value)
        }
    } catch (err) {
        console.error('Lỗi khi lấy danh mục bài viết:', err)
    }
}

// Lấy 3 bài viết nổi bật (mới nhất)
const fetchPopularPosts = async () => {
    try {
        console.log('fetchPopularPosts: Đang gọi API bài viết nổi bật...')
        const res = await postService.getPostsApi({ limit: 3 })
        console.log('fetchPopularPosts: Phản hồi nổi bật từ API:', res)
        if (res.success && res.data) {
            popularPosts.value = res.data.data
            console.log('fetchPopularPosts: Đã lưu nổi bật:', popularPosts.value)
        }
    } catch (err) {
        console.error('Lỗi khi lấy bài viết nổi bật:', err)
    }
}

// Lọc theo danh mục
const filterByCategory = (catId: number | string) => {
    selectedCategoryId.value = catId
    currentPage.value = 1
    fetchPosts()
}

// Chuyển trang
const changePage = (page: number) => {
    if (page < 1 || page > totalPages.value) return
    currentPage.value = page
    fetchPosts()
    // Cuộn lên đầu phần nội dung
    if (process.client) {
        window.scrollTo({ top: 400, behavior: 'smooth' })
    }
}

// Hàm format hình ảnh thumbnail
const getThumbnailUrl = (thumbnail: string | null) => {
    if (!thumbnail) {
        return 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80'
    }
    if (thumbnail.startsWith('http') || thumbnail.startsWith('/')) {
        return thumbnail
    }
    return `${BASE_URL}storage/${thumbnail}`
}

onMounted(async () => {
    loading.value = true
    try {
        await Promise.all([
            fetchPosts(false),
            fetchCategories(),
            fetchPopularPosts()
        ])
    } catch (err) {
        console.error('Lỗi khi khởi tạo dữ liệu trang blog:', err)
    } finally {
        loading.value = false
    }
})
</script>
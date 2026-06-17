<template>
	<!-- Blog -->
	<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
		<h2 class="text-3xl font-extrabold text-center mb-12 text-black tracking-tight">Blog Drivio</h2>

		<div class="container mx-auto p-4">
			<!-- Loading skeleton state -->
			<div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 auto-rows-[250px]">
				<div v-for="i in 5" :key="i" :class="[
					getCardClass(i - 1),
					'animate-pulse bg-slate-100 rounded-2xl p-6 flex flex-col justify-end relative overflow-hidden min-h-[250px] border border-slate-100'
				]">
					<div class="w-1/3 h-4 bg-slate-200 rounded mb-3"></div>
					<div class="w-3/4 h-6 bg-slate-200 rounded mb-2"></div>
					<div class="w-1/2 h-4 bg-slate-200 rounded"></div>
				</div>
			</div>

			<!-- Empty State -->
			<div v-else-if="posts.length === 0"
				class="w-full py-16 px-4 flex flex-col items-center justify-center text-center bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
				<div class="relative mb-6">
					<div
						class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center text-teal-600 animate-pulse">
						<Icon name="ri:article-line" size="40" />
					</div>
					<div
						class="absolute -bottom-1 -right-1 w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center text-white border-2 border-white shadow-sm">
						<Icon name="ri:compass-discover-line" size="12" />
					</div>
				</div>

				<h3 class="text-xl font-bold text-gray-800 mb-2">Đang Cập Nhật Bài Viết</h3>
				<p class="text-gray-500 max-w-md mb-6 text-sm md:text-base leading-relaxed">
					Chúng tôi đang chuẩn bị những bài viết chất lượng về kinh nghiệm thuê xe, cẩm nang đi đường dài và
					những cung đường du lịch tuyệt đẹp để chia sẻ đến bạn. Hãy quay lại sau nhé!
				</p>

				<NuxtLink to="/vehicle-list"
					class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white font-medium px-6 py-2.5 rounded-full shadow-sm hover:shadow transition-all duration-300 text-sm">
					<Icon name="ri:car-line" size="18" />
					Khám phá xe ngay
				</NuxtLink>
			</div>

			<!-- Dynamic Blog Grid -->
			<div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 auto-rows-[250px]">
				<NuxtLink v-for="(post, index) in posts" :key="post.id" :to="'/blogs/' + post.id" :class="[
					getCardClass(index),
					'text-white p-6 rounded-2xl flex flex-col justify-end relative overflow-hidden group shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer'
				]">
					<img :src="getThumbnailUrl(post.thumbnail)" :alt="post.title"
						class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
					<div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-transparent"></div>

					<div class="z-10">
						<span class="inline-block text-xs font-bold uppercase tracking-widest text-teal-300 mb-2">
							{{ post.category?.name || 'Tin tức' }}
						</span>
						<h3 :class="[
							index === 0 ? 'text-xl md:text-2xl' : 'text-md md:text-lg',
							'font-bold mb-2 uppercase tracking-wide leading-tight group-hover:text-teal-200 transition-colors duration-300 line-clamp-2'
						]">
							{{ post.title }}
						</h3>
						<p class="text-xs text-slate-300 mt-1 line-clamp-2">{{ post.excerpt }}</p>
						<div
							class="w-12 h-0.5 bg-teal-400 my-3 opacity-70 group-hover:w-20 transition-all duration-300">
						</div>
						<div class="flex items-center justify-between text-xs text-slate-300 font-medium">
							<span>bởi {{ post.user?.name || 'Admin' }}</span>
							<span>{{ formatDate(post.published_at || post.created_at) }}</span>
						</div>
					</div>
				</NuxtLink>
			</div>
		</div>
	</section>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import { postService, type Post } from '~/services/post.service'
import { BASE_URL } from '~/enviroment/enviroment'

const posts = ref<Post[]>([])
const loading = ref(true)

// Format Date (DD/MM/YYYY)
const formatDate = (dateStr: string | null) => {
	if (!dateStr) return ''
	const d = new Date(dateStr)
	if (isNaN(d.getTime())) return dateStr
	return d.toLocaleDateString('vi-VN', {
		day: '2-digit',
		month: '2-digit',
		year: 'numeric'
	})
}

// Format Image URL
const getThumbnailUrl = (thumbnail: string | null) => {
	if (!thumbnail) {
		return 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80'
	}
	if (thumbnail.startsWith('http') || thumbnail.startsWith('/')) {
		return thumbnail
	}
	return `${BASE_URL}storage/${thumbnail}`
}

const getCardClass = (index: number) => {
	switch (index) {
		case 0:
			return 'md:col-span-2 lg:col-span-2 lg:row-span-2'
		case 1:
		case 2:
			return 'md:col-span-2 lg:col-span-2'
		default:
			return 'md:col-span-2'
	}
}

onMounted(async () => {
	try {
		const res = await postService.getPostsApi({ limit: 5 })
		if (res.success && res.data && res.data.data) {
			posts.value = res.data.data
		}
	} catch (err) {
		console.error('Lỗi khi tải bài viết trang chủ:', err)
	} finally {
		loading.value = false
	}
})
</script>

<style scoped>
@keyframes spin-slow {
	from {
		transform: rotate(0deg);
	}

	to {
		transform: rotate(360deg);
	}
}

.animate-spin-slow {
	animation: spin-slow 8s linear infinite;
}
</style>
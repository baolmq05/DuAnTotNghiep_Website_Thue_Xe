<template>
  <section class="bg-gray-50 min-h-screen">
    <!-- Full-screen Loading Overlay -->
    <CommonLoadingOverlay :loading="loading" text="Đang tải nội dung bài viết" />

    <div v-if="!loading && !blogPost" class="max-w-md mx-auto py-24 text-center">
      <Icon name="ri:error-warning-line" size="48" class="mx-auto text-red-500 mb-4" />
      <h2 class="text-2xl font-bold text-gray-800 mb-2">Không tìm thấy bài viết</h2>
      <p class="text-gray-600 mb-6">Bài viết này không tồn tại hoặc đã bị gỡ bỏ.</p>
      <NuxtLink to="/blogs"
        class="inline-block bg-teal-500 text-white font-semibold px-6 py-2.5 rounded-full hover:bg-teal-600 transition">
        Quay lại trang Blog
      </NuxtLink>
    </div>
    <div v-else-if="!loading && blogPost">
      <!-- Hero section -->
      <div class="relative h-[400px] md:h-[500px] overflow-hidden">
        <img :src="getThumbnailUrl(blogPost.thumbnail)" :alt="blogPost.title" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/20"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 lg:px-8 py-8 mt-4 relative z-10">
        <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4 text-center leading-tight max-w-4xl mx-auto">
          {{ blogPost.title }}
        </h1>
        <p class="text-gray-600 text-base md:text-lg text-center font-medium">
          bởi {{ blogPost.user?.name || 'Admin' }} • {{ formatDate(blogPost.published_at || blogPost.created_at) }}
        </p>
      </div>

      <div class="max-w-7xl mx-auto px-4 lg:px-8 pb-16 pt-8">
        <div class="grid lg:grid-cols-12 gap-8 items-start">

          <!-- Main Content -->
          <div class="lg:col-span-8 xl:col-span-9 order-2 lg:order-1">
            <article class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 md:p-10 lg:p-12 blog-content">
              <div v-html="processedContent"
                class="prose prose-lg max-w-none text-gray-700 leading-relaxed text-justify"></div>
            </article>
          </div>

          <!-- Sidebar -->
          <aside class="lg:col-span-4 xl:col-span-3 order-1 lg:order-2 sticky top-24 space-y-6">

            <!-- Table of contents -->
            <div v-if="toc.length > 0" class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
              <h3 class="text-lg font-bold text-gray-900 mb-4 uppercase tracking-wide border-b pb-3 border-gray-100">
                Mục lục
              </h3>
              <nav>
                <ul class="space-y-3">
                  <li v-for="item in toc" :key="item.id">
                    <a :href="`#${item.id}`"
                      class="block text-gray-600 hover:text-teal-600 transition-colors duration-200"
                      :class="{ 'font-semibold text-gray-800': item.level === 2, 'ml-4 text-sm': item.level === 3 }">
                      {{ item.title }}
                    </a>
                  </li>
                </ul>
              </nav>
            </div>

            <!-- Related posts -->
            <div v-if="relatedPosts.length > 0" class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
              <h3 class="text-lg font-bold text-gray-900 mb-4 uppercase tracking-wide border-b pb-3 border-gray-100">
                Bài viết liên quan
              </h3>
              <ul class="space-y-5">
                <li v-for="post in relatedPosts" :key="post.id">
                  <NuxtLink :to="`/blogs/${post.slug}`" class="flex items-center gap-4 group">
                    <img :src="getThumbnailUrl(post.thumbnail)" :alt="post.title"
                      class="w-20 h-20 object-cover rounded-xl shadow-sm group-hover:opacity-80 transition flex-shrink-0" />
                    <div>
                      <h4
                        class="font-semibold text-gray-800 leading-snug group-hover:text-teal-600 transition line-clamp-2">
                        {{ post.title }}
                      </h4>
                      <p class="text-xs text-gray-500 mt-2">{{ formatDate(post.published_at || post.created_at) }}</p>
                    </div>
                  </NuxtLink>
                </li>
              </ul>
            </div>
          </aside>

        </div>
      </div>
    </div>
  </section>
</template>

<script lang="ts" setup>
import { ref, watch, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { postService, type Post } from '~/services/post.service'
import { BASE_URL } from '~/enviroment/enviroment'

definePageMeta({
  layout: 'profile-no-sidebar'
});

const route = useRoute()
const loading = ref(true)
const blogPost = ref<Post | null>(null)

const seoTitle = computed(() => {
  if (!blogPost.value) return 'Đang tải bài viết... | Blog DRIVIO';
  return `${blogPost.value.title} | Blog DRIVIO`;
})

const seoDescription = computed(() => {
  if (!blogPost.value) return 'Đọc chi tiết bài viết trên Blog DRIVIO.';
  return blogPost.value.excerpt || 'Đọc chi tiết bài viết trên Blog DRIVIO.';
})

const seoKeywords = computed(() => {
  if (!blogPost.value) return 'blog thuê xe, drivio blog';
  return blogPost.value.seo_keywords || `${blogPost.value.slug}, blog thuê xe, kinh nghiệm thuê xe, drivio blog`;
})

const seoImage = computed(() => {
  if (!blogPost.value) return 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80';
  return getThumbnailUrl(blogPost.value.thumbnail);
})

useSeoMeta({
  title: seoTitle,
  description: seoDescription,
  keywords: seoKeywords,
  ogTitle: seoTitle,
  ogDescription: seoDescription,
  ogImage: seoImage,
  ogType: 'article',
  twitterCard: 'summary_large_image',
})

const canonicalUrl = computed(() => {
  if (blogPost.value && blogPost.value.slug) {
    return `https://drivio.vn/blogs/${blogPost.value.slug}`;
  }
  return `https://drivio.vn/blogs/${route.params.id}`;
})

useHead({
  link: [
    {
      rel: 'canonical',
      href: canonicalUrl
    }
  ]
})

const relatedPosts = ref<any[]>([])

const processedContent = ref('')
const toc = ref<Array<{ id: string; title: string; level: number }>>([])

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

// Xử lý content và trích xuất Mục lục (TOC)
const processContentAndGenerateToc = (htmlContent: string) => {
  if (!process.client) {
    processedContent.value = htmlContent
    return
  }
  const parser = new DOMParser()
  const doc = parser.parseFromString(htmlContent, 'text/html')
  const headings = doc.querySelectorAll('h2, h3')
  const tempToc: Array<{ id: string; title: string; level: number }> = []

  headings.forEach((heading, index) => {
    const level = heading.tagName.toLowerCase() === 'h2' ? 2 : 3
    let id = heading.getAttribute('id')
    if (!id) {
      id = `heading-${index}`
      heading.setAttribute('id', id)
    }
    tempToc.push({
      id,
      title: heading.textContent || '',
      level
    })
  })

  toc.value = tempToc
  processedContent.value = doc.body.innerHTML
}

// Gọi API chi tiết bài viết
const fetchPostDetail = async (id: string | number) => {
  loading.value = true
  try {
    const res = await postService.getPostDetailApi(id)
    if (res.success && res.data) {
      blogPost.value = res.data
      relatedPosts.value = res.data.related_posts || []
      processContentAndGenerateToc(res.data.content)
    } else {
      blogPost.value = null
    }
  } catch (err) {
    console.error('Lỗi khi lấy chi tiết bài viết:', err)
    blogPost.value = null
  } finally {
    loading.value = false
  }
}

// Watch thay đổi ID trên URL
watch(() => route.params.id, (newId) => {
  if (newId) {
    fetchPostDetail(newId as string)
    // Cuộn lên đầu trang
    if (process.client) {
      window.scrollTo({ top: 0, behavior: 'smooth' })
    }
  }
})

onMounted(() => {
  if (route.params.id) {
    fetchPostDetail(route.params.id as string)
  }
})
</script>

<style>
html {
  scroll-behavior: smooth;
}

.scroll-mt-24 {
  scroll-margin-top: 6rem;
}

/* Scoped styles cho v-html blog content */
.blog-content h2 {
  font-size: 1.875rem;
  font-weight: 700;
  color: #111827;
  margin-top: 2.5rem;
  margin-bottom: 1.25rem;
  scroll-margin-top: 6rem;
}

.blog-content h3 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
  margin-top: 2rem;
  margin-bottom: 1rem;
  scroll-margin-top: 6rem;
}

.blog-content p {
  margin-bottom: 1.25rem;
  line-height: 1.75;
  color: #374151;
}

.blog-content img {
  border-radius: 1rem;
  margin: 2rem auto;
  max-height: 500px;
  object-fit: cover;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}
</style>
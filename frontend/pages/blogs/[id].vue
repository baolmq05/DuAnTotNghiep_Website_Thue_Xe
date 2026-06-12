<template>
    <section class="bg-gray-50 min-h-screen">
      <!-- Banner -->
      <div class="relative h-[400px] overflow-hidden">
        <img
          :src="blogPost.image"
          :alt="blogPost.title"
          class="w-full h-full object-cover"
        />
      </div>
  
      <!-- Title and Author -->
      <div class="max-w-7xl mx-auto px-4 lg:px-8 py-8 mt-8"> <!-- Thêm mt-8 để tạo khoảng cách -->
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 text-center">
          {{ blogPost.title }}
        </h1>
        <p class="text-gray-600 text-lg text-center">
          {{ blogPost.author }} - {{ blogPost.date }}
        </p>
      </div>
  
      <!-- Content -->
      <div class="max-w-7xl mx-auto px-4 lg:px-8 py-16">
        <div class="grid lg:grid-cols-12 gap-8">
          <!-- Main Content -->
          <div class="lg:col-span-9">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 md:p-12">
              <div v-for="(paragraph, index) in blogPost.content" :key="index" class="mb-6">
                <p class="text-gray-700 leading-8 text-lg">
                  {{ paragraph }}
                </p>
              </div>
            </div>
          </div>
  
          <!-- Related Posts -->
          <aside class="lg:col-span-3">
            <div class="sticky top-24 bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
              <h3 class="font-semibold text-gray-900 mb-5">Bài viết liên quan</h3>
              <ul class="space-y-4">
                <li v-for="post in relatedPosts" :key="post.id">
                  <NuxtLink
                    :to="`/blogs/${post.id}`"
                    class="flex items-center gap-4 hover:text-blue-600 transition"
                  >
                    <img
                      :src="post.image"
                      :alt="post.title"
                      class="w-16 h-16 object-cover rounded-lg"
                    />
                    <div>
                      <h4 class="font-medium text-gray-800 leading-tight">
                        {{ post.title }}
                      </h4>
                      <p class="text-sm text-gray-500">{{ post.date }}</p>
                    </div>
                  </NuxtLink>
                </li>
              </ul>
            </div>
          </aside>
        </div>
      </div>
    </section>
  </template>
  
  <script lang="ts" setup>
  import { ref } from 'vue'
  import { useRoute } from 'vue-router'
  
  const route = useRoute()
  const blogId = route.params.id
  
  // Dữ liệu mẫu cho bài viết chi tiết
  const blogPost = ref({
    id: blogId,
    title: 'Tiêu đề bài viết mẫu',
    author: 'Nguyễn Văn A',
    date: '12/06/2026',
    image: `/images/about/policy.webp`, // Ảnh banner mẫu
    content: [
      'Đây là nội dung đoạn văn đầu tiên của bài viết chi tiết. Bạn có thể thay thế bằng nội dung thực tế.',
      'Đây là nội dung đoạn văn thứ hai. Nội dung này có thể dài hơn và chứa thông tin chi tiết hơn.',
      'Đây là nội dung đoạn văn thứ ba. Bạn có thể thêm nhiều đoạn văn khác nếu cần.',
    ],
  })
  
  // Dữ liệu mẫu cho bài viết liên quan
  const relatedPosts = ref([
    {
      id: 1,
      title: 'Bài viết liên quan 1',
      date: '10/06/2026',
      image: `/images/about/policy.webp`,
    },
    {
      id: 2,
      title: 'Bài viết liên quan 2',
      date: '11/06/2026',
      image: `/images/about/policy.webp`,
    },
    {
      id: 3,
      title: 'Bài viết liên quan 3',
      date: '12/06/2026',
      image: `/images/about/policy.webp`,
    },
  ])
  </script>
  
  <style scoped>
  html {
    scroll-behavior: smooth;
  }
  
  p {
    @apply text-gray-600 leading-8 text-lg mb-4;
  }
  
  li {
    @apply text-gray-600 leading-8;
  }
  </style>
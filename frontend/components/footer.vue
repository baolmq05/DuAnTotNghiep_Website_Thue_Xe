<template>
  <footer class="bg-brand-primary text-white py-12 px-4 md:px-8 font-sans">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">

      <!-- Logo -->
      <div class="flex flex-col space-y-4 px-3">
        <div class="flex-shrink-0">
          <NuxtLink to="/" class="flex items-center gap-2 group focus:outline-none">
            <span
              class="text-2xl font-black tracking-wider text-white group-hover:text-brand-light transition-colors duration-200">
              DRIVIO
            </span>
          </NuxtLink>
        </div>

        <p class="text-sm text-gray-300">
          Cùng Bạn Trên Mọi Hành Trình
        </p>

        <div class="flex items-start gap-4 pt-2">
          <div class="flex flex-col gap-4">
            <a href="#">
              <img src="https://res.cloudinary.com/dfmoftnpw/image/upload/v1782292463/Google_Play_Store_badge_EN.svg_fdutvj.png"
                alt="Google Play" class="h-14 rounded border border-gray-600" />
            </a>
            <img src="https://res.cloudinary.com/dfmoftnpw/image/upload/v1782292642/bocongthuong.40599f29_cvipjv.png"
              alt="Đã đăng ký bộ công thương" class="h-15 object-contain" />
          </div>
        </div>
      </div>

      <!-- Khám phá -->
      <div class="flex flex-col space-y-3 px-3">
        <h3 class="font-semibold text-lg text-white">
          Khám phá DRIVIO
        </h3>

        <ul class="space-y-2 text-sm text-gray-300">
          <li>
            <NuxtLink to="/bookinghowto">Hướng dẫn chung</NuxtLink>
          </li>
          <li>
            <NuxtLink to="/bookinghowto#payment">Hướng dẫn thanh toán</NuxtLink>
          </li>
          <li>
            <NuxtLink to="/bookinghowto">Hướng dẫn đặt xe</NuxtLink>
          </li>
        </ul>
      </div>

      <!-- Đối tác -->
      <div class="flex flex-col space-y-3 px-3">
        <h3 class="font-semibold text-lg text-white">
          Dành cho đối tác
        </h3>

        <ul class="space-y-2 text-sm text-gray-300">
          <li>
            <NuxtLink to="/car-register">
              Đăng ký chủ xe DRIVIO
            </NuxtLink>
          </li>
        </ul>
      </div>

      <!-- Liên hệ -->
      <div class="flex flex-col space-y-3 px-3">
        <h3 class="font-semibold text-lg text-white">
          Liên hệ & Hỗ trợ
        </h3>

        <ul class="space-y-2 text-sm text-gray-300">
          <li>
            <NuxtLink to="/contact">
              Liên hệ & Hỗ trợ
            </NuxtLink>
          </li>
        </ul>
      </div>

      <!-- Đăng ký + MXH -->
      <div class="flex flex-col space-y-4">
        <h3 class="font-semibold text-sm text-white">
          Đăng ký nhận ưu đãi
        </h3>

        <form @submit.prevent="handleSubscribe" class="flex flex-col gap-2">
          <input v-model="emailInput" type="email" placeholder="Email nhận ưu đãi"
            class="bg-white text-gray-800 text-sm px-3 py-2 rounded-md" required />

          <button type="submit" class="bg-brand-accent text-white py-2 rounded-md hover:opacity-90 transition duration-200">
            Đăng ký
          </button>
        </form>

        <div class="flex gap-3">
          <a href="#"
            class="w-10 h-10 rounded-full border border-gray-500 flex items-center justify-center hover:border-white transition">
            <Icon name="fa6-brands:facebook-f" class="text-xl" />
          </a>

          <!-- <a href="#"
            class="w-10 h-10 rounded-full border border-gray-500 flex items-center justify-center hover:border-white transition">
            <Icon name="simple-icons:tiktok" class="text-xl" />
          </a> -->

          <a href="#"
            class="w-10 h-10 rounded-full border border-gray-500 flex items-center justify-center hover:border-white transition">
            <Icon name="simple-icons:zalo" class="text-3xl" />
          </a>
        </div>
              <div class="flex items-center gap-2 flex-wrap">
                <!-- <a href=""><img src="https://res.cloudinary.com/dfmoftnpw/image/upload/v1782293102/download_osrbwg.png" alt="" class="h-10"></a> -->
                <a href=""><img src="https://res.cloudinary.com/dfmoftnpw/image/upload/v1782292713/vnpay.780689d6_ftuqtj.png" alt="" class="h-10"></a>
                <!-- <a href=""><img src="https://res.cloudinary.com/dfmoftnpw/image/upload/v1782292711/download_1_zbu7en.png" alt="" class="h-10"></a> -->
                <a href=""><img src="https://res.cloudinary.com/dfmoftnpw/image/upload/v1782292709/download_2_v3hdjk.png" alt="" class="h-10"></a>
      </div>
      </div>

    </div>

    <div
      class="max-w-7xl mx-auto mt-8 pt-6 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-400">
      <div>
        <p>© Công ty Cổ phần Drivio | Công ty Drivio. Cần Thơ, Việt Nam</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref } from 'vue'

const emailInput = ref('')
const { showToast } = useToast()

const handleSubscribe = () => {
  // Logic xử lý khi người dùng đăng ký nhận ưu đãi
  console.log('Đăng ký email:', emailInput.value)
  showToast(`Đã ghi nhận bản tin gửi tới: ${emailInput.value}`, 'success')
  emailInput.value = ''
}
</script>

<style scoped>
/* Bạn có thể bổ sung font chữ hoặc custom style độc quyền không có sẵn trong Tailwind tại đây */
footer {
  letter-spacing: 0.02em;
}
</style>
<template>
  <div class="bg-slate-50 min-h-screen pb-16 font-sans text-slate-800">
    <!-- Hero Banner -->
    <BookingBanner />

    <!-- Sticky Navigation Sub-Bar -->
    <div class="sticky top-0 z-30 bg-white border-b border-slate-200">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 overflow-x-auto py-3 no-scrollbar text-xs md:text-sm font-medium">
          <a v-for="item in navItems" :key="item.id" :href="`#${item.id}`" :class="[
            'px-3.5 py-1.5 rounded-md transition whitespace-nowrap flex items-center gap-1.5',
            activeSection === item.id
              ? 'bg-brand-primary text-white font-semibold'
              : 'text-slate-600 hover:text-brand-primary hover:bg-slate-100'
          ]" @click="activeSection = item.id">
            <Icon :name="item.icon" class="w-4 h-4" />
            <span>{{ item.title }}</span>
          </a>
        </nav>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 space-y-12">

      <!-- ================= SECTION 1: 4 BƯỚC ĐẶT XE ================= -->
      <section id="steps" class="scroll-mt-20 space-y-6">
        <div class="border-b border-slate-200 pb-3">
          <h2 class="text-xl md:text-2xl font-bold text-slate-900">
            1. Quy Trình 4 Bước Đặt Xe
          </h2>
          <p class="text-slate-500 text-xs md:text-sm mt-1">
            Các bước thao tác trực quan giúp bạn hoàn tất giữ xe nhanh chóng.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

          <!-- STEP 1 -->
          <div class="bg-white rounded-lg p-5 border border-slate-200 space-y-3">
            <div class="flex items-center gap-3">
              <div
                class="w-8 h-8 rounded-md bg-brand-primary text-white flex items-center justify-center font-bold text-sm shrink-0">
                1
              </div>
              <h3 class="font-bold text-slate-900 text-base">Tìm & Chọn Xe</h3>
            </div>
            <p class="text-slate-600 text-sm leading-relaxed">
              Nhập địa điểm, chọn thời gian nhận và trả xe. Lọc xe theo loại xe (Sedan, SUV...), số tự động và tính năng
              giao xe tận nơi.
            </p>
          </div>

          <!-- STEP 2 -->
          <div class="bg-white rounded-lg p-5 border border-slate-200 space-y-3">
            <div class="flex items-center gap-3">
              <div
                class="w-8 h-8 rounded-md bg-brand-primary text-white flex items-center justify-center font-bold text-sm shrink-0">
                2
              </div>
              <h3 class="font-bold text-slate-900 text-base">Gửi Yêu Cầu Đặt Xe</h3>
            </div>
            <p class="text-slate-600 text-sm leading-relaxed">
              Kiểm tra bảng giá thuê, áp dụng mã giảm giá (nếu có), cập nhật thông tin Giấy phép lái xe (GPLX) và gửi
              yêu cầu tới chủ xe.
            </p>
          </div>

          <!-- STEP 3 -->
          <div class="bg-white rounded-lg p-5 border border-slate-200 space-y-3">
            <div class="flex items-center gap-3">
              <div
                class="w-8 h-8 rounded-md bg-brand-primary text-white flex items-center justify-center font-bold text-sm shrink-0">
                3
              </div>
              <h3 class="font-bold text-slate-900 text-base">Thanh Toán Đặt Cọc</h3>
            </div>
            <p class="text-slate-600 text-sm leading-relaxed">
              Thanh toán cọc (40% hoặc 100% tiền thuê) qua VNPay, ZaloPay hoặc chuyển khoản ngân hàng. Ngay sau khi cọc,
              bạn sẽ nhận được SĐT của chủ xe.
            </p>
          </div>

          <!-- STEP 4 -->
          <div class="bg-white rounded-lg p-5 border border-slate-200 space-y-3">
            <div class="flex items-center gap-3">
              <div
                class="w-8 h-8 rounded-md bg-brand-primary text-white flex items-center justify-center font-bold text-sm shrink-0">
                4
              </div>
              <h3 class="font-bold text-slate-900 text-base">Nhận Xe & Khởi Hành</h3>
            </div>
            <p class="text-slate-600 text-sm leading-relaxed">
              Gặp chủ xe, kiểm tra tình trạng thực tế của xe, chụp ảnh hiện trạng, bàn giao giấy tờ, ký hợp đồng và nhận
              chìa khóa.
            </p>
          </div>

        </div>
      </section>

      <!-- ================= SECTION 2: DỊCH VỤ THUÊ XE ================= -->
      <section id="services" class="scroll-mt-20 space-y-6">
        <div class="border-b border-slate-200 pb-3">
          <h2 class="text-xl md:text-2xl font-bold text-slate-900">
            2. Dịch Vụ Thuê Xe
          </h2>
          <p class="text-slate-500 text-xs md:text-sm mt-1">
            Hình thức dịch vụ phù hợp với từng nhu cầu di chuyển.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="bg-white p-5 rounded-lg border border-slate-200 space-y-2">
            <h3 class="font-bold text-slate-900 text-base">Thuê Xe Tự Lái</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              Tự do làm chủ vô lăng và lộ trình. Đa dạng các dòng xe 4 chỗ, 7 chỗ, xe bán tải và ô tô điện đời mới.
            </p>
          </div>

          <div class="bg-white p-5 rounded-lg border border-slate-200 space-y-2">
            <h3 class="font-bold text-slate-900 text-base">Giao Xe Tận Nơi</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
              Nhận và trả xe trực tiếp tại địa chỉ nhà riêng, sân bay hoặc khách sạn theo yêu cầu của bạn.
            </p>
          </div>
        </div>
      </section>

      <!-- ================= SECTION 3: THANH TOÁN & HỦY CHUYẾN ================= -->
      <section id="payment" class="scroll-mt-20 space-y-6">
        <div class="border-b border-slate-200 pb-3">
          <h2 class="text-xl md:text-2xl font-bold text-slate-900">
            3. Thanh Toán & Chính Sách Hủy Chuyến
          </h2>
          <p class="text-slate-500 text-xs md:text-sm mt-1">
            Hình thức thanh toán linh hoạt và điều khoản hoàn cọc rõ ràng.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-white p-6 rounded-lg border border-slate-200 space-y-4">
            <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-2">
              Hình Thức Thanh Toán
            </h3>
            <ul class="space-y-2 text-xs md:text-sm text-slate-700">
              <li class="flex items-center gap-2">
                <Icon name="lucide:check-circle-2" class="w-4 h-4 text-brand-primary" />
                <span>Ví điện tử DRIVIO</span>
              </li>
              <li class="flex items-center gap-2">
                <Icon name="lucide:check-circle-2" class="w-4 h-4 text-brand-primary" />
                <span>Cổng thanh toán VNPay / ZaloPay</span>
              </li>
              <li class="flex items-center gap-2">
                <Icon name="lucide:check-circle-2" class="w-4 h-4 text-brand-primary" />
                <span>Chuyển khoản ngân hàng 24/7</span>
              </li>
            </ul>
          </div>

          <div class="bg-white p-6 rounded-lg border border-slate-200 space-y-4">
            <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-2">
              Chính Sách Hủy Chuyến
            </h3>
            <div class="space-y-2 text-xs md:text-sm">
              <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded border border-slate-100 gap-2">
                <span>Trong vòng 1h sau khi đặt xe:</span>
                <span class="font-bold text-emerald-700 shrink-0 whitespace-nowrap">Miễn phí</span>
              </div>
              <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded border border-slate-100 gap-2">
                <div>
                  <span>Trước chuyến đi 7 ngày</span>
                  <div class="text-xs text-slate-500 font-normal mt-0.5">(Sau 1h khi đặt)</div>
                </div>
                <span class="font-bold text-amber-700 shrink-0 whitespace-nowrap">10% giá trị chuyến đi</span>
              </div>
              <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded border border-slate-100 gap-2">
                <div>
                  <span>Trong vòng 7 ngày trước chuyến đi</span>
                  <div class="text-xs text-slate-500 font-normal mt-0.5">(Sau 1h khi đặt)</div>
                </div>
                <span class="font-bold text-amber-700 shrink-0 whitespace-nowrap">40% giá trị chuyến đi</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ================= SECTION 4: CÂU HỎI THƯỜNG GẶP (FAQ) ================= -->
      <section id="faq" class="scroll-mt-20 space-y-6">
        <div class="border-b border-slate-200 pb-3">
          <h2 class="text-xl md:text-2xl font-bold text-slate-900">
            4. Câu Hỏi Thường Gặp
          </h2>
          <p class="text-slate-500 text-xs md:text-sm mt-1">
            Giải đáp các thắc mắc phổ biến của khách thuê.
          </p>
        </div>

        <div class="space-y-3">
          <div v-for="(item, index) in faqList" :key="index"
            class="bg-white rounded-lg border border-slate-200 overflow-hidden">
            <button @click="toggleFaq(index)"
              class="w-full p-4 text-left font-semibold text-slate-900 text-sm md:text-base flex items-center justify-between gap-4 focus:outline-none hover:bg-slate-50">
              <span>{{ index + 1 }}. {{ item.question }}</span>
              <Icon name="lucide:chevron-down" :class="[
                'w-4 h-4 text-slate-400 transition-transform shrink-0',
                openFaqIndex === index ? 'rotate-180 text-brand-primary' : ''
              ]" />
            </button>

            <div v-show="openFaqIndex === index"
              class="px-4 pb-4 text-xs md:text-sm text-slate-600 border-t border-slate-100 pt-3 leading-relaxed bg-slate-50/50">
              {{ item.answer }}
            </div>
          </div>
        </div>
      </section>

      <!-- ================= SECTION 5: CTA BANNER ================= -->
      <section class="bg-brand-dark p-8 rounded-xl text-white text-center space-y-4">
        <h2 class="text-xl md:text-2xl font-bold">
          Sẵn Sàng Bắt Đầu Hành Trình Cùng DRIVIO?
        </h2>
        <p class="text-slate-300 text-xs md:text-sm max-w-xl mx-auto">
          Tìm và lựa chọn chiếc xe phù hợp nhất cho chuyến đi của bạn ngay hôm nay.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
          <NuxtLink to="/vehicle-list"
            class="px-6 py-2.5 bg-brand-primary text-white text-sm font-semibold rounded-lg hover:bg-teal-700 transition">
            Tìm xe ngay
          </NuxtLink>

          <NuxtLink to="/car-register"
            class="px-6 py-2.5 bg-white/10 text-white text-sm font-semibold rounded-lg hover:bg-white/20 transition border border-white/20">
            Đăng ký làm chủ xe
          </NuxtLink>
        </div>
      </section>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import BookingBanner from '~/components/Banner/BookingBanner.vue'

useSeoMeta({
  title: 'Hướng Dẫn Đặt Xe Tự Lái Online | DRIVIO',
  description: 'Hướng dẫn 4 bước đặt thuê xe tự lái tại DRIVIO: chọn xe, đặt lịch, thanh toán, nhận xe. Quy trình minh bạch, đơn giản, nhanh chóng.',
  keywords: 'hướng dẫn thuê xe, cách đặt xe tự lái, quy trình thuê xe online, thuê xe drivio, đặt xe nhanh',
  ogTitle: 'Hướng Dẫn Đặt Xe | DRIVIO',
  ogDescription: '4 bước đơn giản để thuê xe tự lái tại DRIVIO.',
  ogImage: '/images/og/booking-guide.jpg',
  twitterCard: 'summary_large_image',
})

useHead({
  link: [
    { rel: 'canonical', href: 'https://drivio.vn/bookinghowto' }
  ]
})

const activeSection = ref('steps')
const openFaqIndex = ref<number | null>(0)

const toggleFaq = (index: number) => {
  openFaqIndex.value = openFaqIndex.value === index ? null : index
}

const navItems = [
  { id: 'steps', title: '1. Quy trình 4 bước', icon: 'lucide:list-ordered' },
  { id: 'services', title: '2. Dịch vụ thuê xe', icon: 'lucide:car' },
  { id: 'payment', title: '3. Thanh toán & Hủy chuyến', icon: 'lucide:credit-card' },
  { id: 'faq', title: '4. Câu hỏi thường gặp', icon: 'lucide:help-circle' }
]

const faqList = [
  {
    question: 'Tôi cần chuẩn bị những gì khi đến nhận xe?',
    answer: 'Bạn cần mang theo bản gốc Giấy phép lái xe (hạng B1/B2/C trở lên), CCCD gắn chip hoặc Hộ chiếu chính chủ, và tài sản thế chấp (Xe máy + Cavet gốc hoặc khoản tiền thế chấp 15-20 triệu).'
  },
  {
    question: 'Tôi có được kiểm tra xe trước khi nhận chìa khóa không?',
    answer: 'Có. Bạn và chủ xe sẽ cùng kiểm tra ngoại thất, nội thất, số km và lượng xăng/dầu. Nên quay video và chụp ảnh lại tình trạng xe trước khi bàn giao.'
  },
  {
    question: 'Tiền thế chấp (15 - 20 triệu) khi nào tôi nhận lại được?',
    answer: 'Khi bạn trả xe nguyên vẹn đúng hạn, chủ xe sẽ hoàn lại 100% tiền thế chấp cho bạn ngay thời điểm bàn giao xe.'
  },
  {
    question: 'Nếu trả xe trễ hạn thì phí tính như thế nào?',
    answer: 'Nếu trễ từ 1 giờ trở lên, khoản phụ thu sẽ tính theo quy định (thường từ 50.000đ - 100.000đ/giờ). Hãy báo trước cho chủ xe nếu cần gia hạn.'
  },
  {
    question: 'Phạt nguội trong thời gian thuê xử lý thế nào?',
    answer: 'Hợp đồng ghi nhận cụ thể mốc thời gian thuê. Nếu có phạt nguội phát sinh trong khung giờ bạn thuê xe, bạn có trách nhiệm thanh toán khoản phạt đó.'
  },
  {
    question: 'Nếu chủ xe hủy chuyến đột xuất thì sao?',
    answer: 'Bạn sẽ được hoàn 100% tiền cọc lập tức, đồng thời DRIVIO sẽ đền bù mã giảm giá và hỗ trợ tìm xe thay thế tương đương.'
  }
]
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>

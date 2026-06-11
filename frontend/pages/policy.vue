<template>
  <section class="bg-gray-50 min-h-screen">
    <!-- Hero Banner -->
    <div class="relative h-[420px] overflow-hidden">
      <img
        src="../../images/about/policy.webp"
        alt="Drivio Policy"
        class="w-full h-full object-cover"
      />
      <div class="absolute inset-0 bg-black/60"></div>
      <div class="absolute inset-0 flex flex-col justify-center items-center text-center px-6">
        <span class="uppercase tracking-[6px] text-gray-300 text-sm mb-5">
          DRIVIO
        </span>
        <h1 class="text-5xl md:text-6xl font-bold text-white mb-5">
          Chính Sách & Quy Định
        </h1>
        <p class="max-w-2xl text-gray-200 text-lg">
          Điều khoản, chính sách và quy định áp dụng cho người dùng
          trên nền tảng thuê xe DRIVIO.
        </p>
      </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-16">
      <div class="grid lg:grid-cols-12 gap-8">
        <!-- Sidebar -->
        <aside class="lg:col-span-3">
          <div class="sticky top-24 bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-5">Nội dung</h3>
            <ul class="space-y-4">
              <li>
                <a href="#privacy" class="text-gray-600 hover:text-blue-600 transition">
                  Chính sách bảo mật
                </a>
              </li>
              <li>
                <a href="#intro" class="text-gray-600 hover:text-blue-600 transition">
                  Giới thiệu
                </a>
              </li>
              <li>
                <a href="#collect" class="text-gray-600 hover:text-blue-600 transition">
                  Thu thập dữ liệu
                </a>
              </li>
              <li>
                <a href="#usage" class="text-gray-600 hover:text-blue-600 transition">
                  Mục đích sử dụng
                </a>
              </li>
              <li>
                <a href="#cancel" class="text-gray-600 hover:text-blue-600 transition">
                  Chính sách hủy chuyến
                </a>
              </li>
            </ul>
          </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:col-span-9">
          <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 md:p-12">
            <!-- Chính sách bảo mật -->
            <section id="privacy" class="mb-14">
              <h2 class="text-3xl font-semibold text-gray-900 mb-6 pb-4 border-b border-gray-100">
                Chính sách bảo mật
              </h2>
              <p class="text-gray-600 leading-8 text-[17px] mb-4">
                DRIVIO xin thông báo về việc bổ sung Chính sách bảo mật liên quan đến các vấn đề mới trong việc bảo vệ dữ liệu cá nhân theo Nghị định 13/2023/NĐ-CP của Chính phủ Việt Nam.
              </p>
              <p class="text-gray-600 leading-8 text-[17px] mb-4">
                Trong quá trình thiết lập mối quan hệ giữa DRIVIO và Người dùng, giữ các người dùng với nhau phụ thuộc vào từng loại hình dịch vụ mà chúng tôi cung cấp, DRIVIO có thể thu thập và xử lý dữ liệu cá nhân của Quý Khách hàng. DRIVIO cam kết đảm bảo an toàn và bảo vệ dữ liệu cá nhân của Quý người dùng theo quy định của pháp luật Việt Nam.
              </p>
              <p class="text-gray-600 leading-8 text-[17px] mb-4">
                Theo đó bắt đầu từ ngày ra thông báo này, chúng tôi cần xác nhận lại sự đồng ý của bạn để tiếp tục thu thập, xử lý và chia sẻ dữ liệu cá nhân của bạn. Tuy nhiên, chúng tôi muốn nhắc nhở rằng nếu thu hồi sự đồng ý của mình, Quý Người dùng sẽ không thể tiếp cận với những người dùng khác trên nền tảng để phục vụ nhu cầu sử dụng dịch vụ của mình.
              </p>
            </section>

            <!-- Giới thiệu -->
            <section id="intro" class="mb-14">
              <h2 class="text-3xl font-semibold text-gray-900 mb-6 pb-4 border-b border-gray-100">
                Giới thiệu
              </h2>
              <p class="text-gray-600 leading-8 text-[17px] mb-4">
                Chào mừng bạn đến với nền tảng DRIVIO.vn (bao gồm website và ứng dụng di động) được vận hành bởi Công ty Cổ phần DRIVIO Asia. DRIVIO thông báo đến bạn và cần sự đồng ý và cho phép của bạn để xử lý, thu thập và lưu trữ dữ liệu của bạn nhằm mục đích cung cấp các dịch vụ cho bạn trong suốt quá trình sử dụng Website và Ứng dụng di động.
              </p>
              <p class="text-gray-600 leading-8 text-[17px] mb-4">
                DRIVIO là một sàn giao dịch thương mại điện tử (“Sàn TMĐT”) đã được đăng ký với Bộ Công Thương. Người dùng trên sàn bao gồm người bán (chủ xe), người mua (khách thuê) và các bên khác sử dụng dịch vụ của chúng tôi.
              </p>
            </section>

            <!-- Chính sách hủy chuyến -->
            <section id="cancel">
              <h2 class="text-3xl font-semibold text-gray-900 mb-6 pb-4 border-b border-gray-100">
                Chính sách hủy chuyến
              </h2>
              <div class="overflow-hidden rounded-2xl border border-gray-200">
                <table class="w-full">
                  <thead>
                    <tr style="background-color: #13424C; color: #ffffff;">                      <th class="px-6 py-4 text-left font-medium">Thời điểm hủy chuyến</th>
                      <th class="px-6 py-4 text-left font-medium">Phí hủy chuyến</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                      <td class="px-6 py-4">Trong vòng 1 giờ sau khi giữ chỗ</td>
                      <td class="px-6 py-4 text-green-600 font-medium">Miễn phí</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                      <td class="px-6 py-4">Trước chuyến đi trên 7 ngày</td>
                      <td class="px-6 py-4">10% giá trị chuyến đi</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                      <td class="px-6 py-4">Trong vòng 7 ngày trước chuyến đi</td>
                      <td class="px-6 py-4 text-red-500 font-medium">40% giá trị chuyến đi</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
useHead({
  title: 'Chính Sách & Quy Định | DRIVIO'
})
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}

p {
  @apply text-gray-600 leading-8 text-[17px] mb-4;
}

li {
  @apply text-gray-600 leading-8;
}
</style>
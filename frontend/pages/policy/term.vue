<template>
    <section class="bg-gray-50 min-h-screen">
        <!-- Hero Banner -->
        <PolicyBanner />

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 lg:px-8 py-16">
            <div class="grid lg:grid-cols-12 gap-8">
                <!-- Sidebar -->
        <aside class="lg:col-span-3">
          <div class="sticky top-24 bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-5">Nội dung</h3>
            <ul class="space-y-2">
              <li>
                <NuxtLink 
                  to="/policy" 
                  class="flex items-center text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-4 py-2.5 rounded-xl transition-all duration-200"
                  exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3 rounded-l-none rounded-r-xl"
                >
                  Chính sách và quy định
                </NuxtLink>
              </li>
              <li>
                <NuxtLink 
                  to="/policy/term" 
                  class="flex items-center text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-4 py-2.5 rounded-xl transition-all duration-200"
                  exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3 rounded-l-none rounded-r-xl"
                >
                  Nguyên tắc chung
                </NuxtLink>
              </li>
              <li>
                <NuxtLink 
                  to="/policy/personalinfo" 
                  class="flex items-center text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-4 py-2.5 rounded-xl transition-all duration-200"
                  exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3 rounded-l-none rounded-r-xl"
                >
                  Chính sách BVDLCN
                </NuxtLink>
              </li>
              <li>
                <NuxtLink 
                  to="/policy/candidateprivacy" 
                  class="flex items-center text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-4 py-2.5 rounded-xl transition-all duration-200"
                  exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3 rounded-l-none rounded-r-xl"
                >
                  DLCN của ứng viên
                </NuxtLink>
              </li>
              <li>
                <NuxtLink 
                  to="/policy/resolveconflic" 
                  class="flex items-center text-sm text-slate-600 hover:text-brand-primary hover:bg-slate-50/50 px-4 py-2.5 rounded-xl transition-all duration-200"
                  exact-active-class="text-brand-primary bg-brand-secondary font-bold border-l-4 border-brand-primary pl-3 rounded-l-none rounded-r-xl"
                >
                  Giải quyết khiếu nại
                </NuxtLink>
              </li>
            </ul>
          </div>
        </aside>
                <!-- Main Content -->
                <div class="tutorial-content col-span-9">
                    <div class="category-option mb-5">
                        <h1 class="font-bold text-2xl">Nguyên tắc chung</h1>
                    </div>
                    <div class="main-content text-justify">
                        <h6 class="font-bold mb-3">Trách nhiệm và trung thực</h6>
                        <p>Mioto hướng đến việc xây dựng một cộng đồng thuê xe với mong muốn mang lại sự tiện ích cho
                            khách thuê xe và chủ xe. Chính vì vậy, khách thuê xe cần có trách nhiệm giữ gìn xe thuê, và
                            trân trọng như chính xe của bạn. Nếu có phát sinh vấn đề hay bất cứ sự cố nào đối với xe
                            trên chuyến đi của bạn, hãy mạnh dạn giải quyết với chủ xe một cách TRUNG THỰC và CÓ TRÁCH
                            NHIỆM.</p>
                        <h6 class="font-bold mb-3">Tạo sự tin tưởng</h6>
                        <p>Các chủ xe hãy tạo hồ sơ của mình trên ứng dụng Mioto thật rõ ràng và minh bạch, bao gồm các
                            hình ảnh cá nhân, tên, thông tin liên lạc của bạn, mô tả kĩ càng và đầu tư nhiều vào chất
                            lượng hình ảnh để chiếc xe của bạn trông thật cuốn hút, điều này sẽ giúp khách thuê TIN
                            TƯỞNG và tỉ lệ thuê xe của bạn sẽ cao hơn.<br>Bạn có thể kiểm tra trực tuyến hồ sơ của Khách
                            hàng trước khi quyết định cho thuê, điều này sẽ giúp bạn an tâm hơn trước khi giao chìa khóa
                            xe cho những vị khách của mình.</p>
                        <h6 class="font-bold mb-3">Hãy giữ xe sạch sẽ</h6>
                        <p>Mioto hướng đến việc xây dựng một cộng đồng văn minh, vì vậy hãy yêu quý và giữ vệ sinh những
                            chiếc xe bạn thuê. Việc giao nhận và hoàn trả những chiếc xe SẠCH SẼ giúp cả hai bên kết
                            thúc giao dịch một cách tốt đẹp và sẵn sàng cho những giao dịch tuyệt vời sau này.</p>
                        <h6 class="font-bold mb-3">Thông báo ngay khi có vấn đề phát sinh</h6>
                        <p>Bất kì khi nào có phát sinh vấn đề trong quá trình thuê xe, chủ xe và khách thuê xe nên THÔNG
                            BÁO ngay cho nhau, và sử dụng các điều khoản trong hợp đồng thuê xe hoặc các chính sách của
                            Mioto được đăng trên website Mioto.vn để giải quyết nhanh chóng.</p>
                        <h6 class="font-bold mb-3">Tuân thủ pháp luật</h6>
                        <p>Các chủ xe có trách nhiệm đảm bảo xe của bạn có đầy đủ giấy tờ pháp lý và trong tình trạng AN
                            TOÀN trước khi giao xe cho khách.<br>Khách thuê xe có trách nhiệm CHẤP HÀNH nghiêm chỉnh
                            luật lệ giao thông và tuân thủ tất cả các điều luật hiện hành có liên quan trong thời gian
                            thuê xe.</p>
                        <h6 class="font-bold mb-3">Cuối cùng...</h6>
                        <p>Nếu giữa hai bên chủ xe và khách thuê xe không thể tự giải quyết các vấn đề tranh chấp phát
                            sinh, hãy liên hệ với bộ phận Chăm sóc Khách hàng của Mioto. Chúng tôi luôn bên cạnh để hỗ
                            trợ bạn dàn xếp các vấn đề một cách tốt đẹp nhất, tuân theo các điều khoản, chính sách và
                            quy chế hoạt động cả hai bên đã cam kết với Mioto.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import PolicyBanner from '~/components/Banner/PolicyBanner.vue'

useSeoMeta({
    title: 'Chính Sách & Quy Định | DRIVIO — Trải Nghiệm Thuê Xe Khác Biệt',
    description: 'Các điều khoản sử dụng dịch vụ, chính sách bảo mật thông tin và quy định hủy chuyến trên nền tảng thuê xe tự lái Drivio.',
    ogTitle: 'Chính Sách & Quy Định | Drivio',
    ogDescription: 'Thông tin chi tiết về các điều khoản, quyền lợi và nghĩa vụ của khách thuê và chủ xe trên Drivio.',
    ogImage: '/images/about/policy.webp',
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
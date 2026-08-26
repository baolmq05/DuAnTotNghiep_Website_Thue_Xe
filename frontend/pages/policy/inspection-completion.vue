<template>
    <section class="bg-gray-50 min-h-screen">
        <!-- Hero Banner -->
        <PolicyBanner />

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 lg:px-8 py-16">
            <div class="grid grid-cols-12 gap-8">
                <!-- Sidebar -->
                <PolicySidebar />

                <!-- Main Content -->
                <div class="tutorial-content col-span-12 lg:col-span-9">
                    <div class="category-option mb-5">
                        <h1 class="font-bold text-2xl">Chính sách kiểm tra xe và hoàn thành chuyến đi</h1>
                    </div>
                    <div class="main-content text-justify">
                        <h6 class="font-bold mb-3">1. Mục đích của chính sách</h6>
                        <p>
                            Chính sách kiểm tra xe và hoàn thành chuyến đi được ban hành nhằm thiết lập quy trình bàn giao, kiểm tra xe minh bạch giữa Khách thuê và Chủ xe tại thời điểm kết thúc hành trình. Drivio hướng tới mục tiêu tối ưu hóa trải nghiệm người dùng, đơn giản hóa quy trình vận hành và bảo vệ quyền lợi chính đáng của cả hai bên một cách công bằng, rõ ràng và thuận tiện nhất.
                        </p>

                        <h6 class="font-bold mb-3">2. Trách nhiệm của Chủ xe trước khi kết thúc chuyến đi</h6>
                        <p>
                            Khi khách thuê hoàn trả xe, Chủ xe có trách nhiệm trực tiếp gặp mặt khách thuê để đồng kiểm tra hiện trạng thực tế của phương tiện. Chủ xe cần đối chiếu tình trạng xe thực tế với các hình ảnh và ghi nhận tại thời điểm giao xe ban đầu, đồng thời chụp và tải lên hệ thống Drivio tối thiểu từ 1 đến 5 hình ảnh rõ nét thể hiện hiện trạng xe khi nhận lại trước khi tiến hành xác nhận hoàn tất chuyến đi.
                        </p>

                        <h6 class="font-bold mb-3">3. Quy trình kiểm tra xe trước khi xác nhận hoàn thành</h6>
                        <p>
                            Trước khi bấm nút xác nhận hoàn tất chuyến đi trên hệ thống, Chủ xe cần kiểm tra kỹ lưỡng các danh mục tiêu chuẩn sau:<br>
                            - <strong>Ngoại thất xe:</strong> Kiểm tra thân vỏ, cản trước/sau, gương chiếu hậu, kính chắn gió và hệ thống đèn xe nhằm đảm bảo không phát sinh vết trầy xước sâu, móp méo hoặc nứt vỡ mới so với lúc bàn giao ban đầu.<br>
                            - <strong>Nội thất và vệ sinh:</strong> Kiểm tra ghế da, thảm lót sàn, trần xe và bảng điều khiển đảm bảo không bị rách, ố bẩn nặng hoặc lưu lại mùi hôi lạ/khói thuốc lá.<br>
                            - <strong>Mức nhiên liệu và số km (Odo):</strong> Đối chiếu mức xăng/dầu hoặc phần trăm pin và số km di chuyển thực tế so với thỏa thuận ban đầu trong hợp đồng thuê xe.<br>
                            - <strong>Giấy tờ và phụ kiện đi kèm:</strong> Nhận lại đầy đủ chìa khóa xe, giấy tờ xe (cà vẹt, giấy kiểm định, bảo hiểm), bánh dự phòng và bộ dụng cụ cứu hộ theo xe.<br>
                            - <strong>Tình trạng vận hành:</strong> Kiểm tra động cơ, hệ thống phanh, điều hòa và lốp xe hoạt động bình thường, không có đèn cảnh báo lỗi xuất hiện trên bảng đồng hồ.<br>
                            - <strong>Thời gian và địa điểm hoàn trả:</strong> Xác nhận khách thuê đã giao trả xe đúng thời gian và địa điểm theo thỏa thuận đơn đặt xe.
                        </p>

                        <h6 class="font-bold mb-3">4. Hiệu lực của việc "Xác nhận hoàn tất chuyến đi"</h6>
                        <p>
                            Khi Chủ xe đọc, tích chọn đầy đủ các điều khoản trong danh mục kiểm tra và nhấn nút "Xác nhận hoàn tất" trên hệ thống, hành động này mang giá trị xác nhận chính thức:<br>
                            - <strong>Chuyến đi chính thức kết thúc:</strong> Trạng thái đơn thuê xe được chuyển sang Hoàn thành (Complete).<br>
                            - <strong>Giải phóng thanh toán:</strong> Hệ thống tự động ghi nhận và giải phóng số dư doanh thu thuê xe về Ví của Chủ xe theo quy định.<br>
                            - <strong>Xác nhận đồng ý hiện trạng xe:</strong> Đồng nghĩa Chủ xe xác nhận xe đã được kiểm tra đầy đủ, tình trạng xe đạt yêu cầu và không có khiếu nại phát sinh đối với khách thuê.
                        </p>

                        <h6 class="font-bold mb-3">5. Các trường hợp Drivio không hỗ trợ bồi thường sau chuyến đi</h6>
                        <p>
                            Nhằm đảm bảo tính minh bạch và tránh các tranh chấp không thể xác minh nguồn gốc hư hỏng sau khi xe đã rời khỏi sự chứng kiến của hai bên, Drivio KHÔNG tiếp nhận xử lý khiếu nại hoặc yêu cầu bồi thường trong các trường hợp sau:<br>
                            - Các hư hại, trầy xước hoặc mất mát phụ kiện được phát hiện sau khi Chủ xe đã bấm nút "Xác nhận hoàn tất chuyến đi" trên hệ thống.<br>
                            - Thiếu hụt nhiên liệu hoặc phát sinh số km vượt mức được báo cáo sau thời điểm kết thúc chuyến đi.<br>
                            - Các thỏa thuận bồi thường dân sự riêng ngoài hệ thống không có biên bản bàn giao hoặc hình ảnh bằng chứng ghi nhận tại thời điểm trả xe.<br>
                            Mọi thỏa thuận về chi phí phát sinh (như phí vệ sinh xe, phụ thu xăng/km...) cần được hai bên trao đổi và thống nhất trực tiếp tại thời điểm nhận xe trước khi bấm xác nhận hoàn tất trên nền tảng.
                        </p>

                        <h6 class="font-bold mb-3">6. Khuyến nghị dành cho Chủ xe</h6>
                        <p>
                            Để quá trình nhận xe diễn ra thuận lợi và an toàn, Drivio khuyến nghị Chủ xe nên tiến hành bàn giao xe tại nơi có đủ ánh sáng, chụp ảnh đầy đủ 4 góc xe cùng mặt đồng hồ xăng/km làm bằng chứng lưu trữ, và luôn giữ thái độ lịch sự, chuyên nghiệp nhằm cùng Drivio xây dựng một cộng đồng thuê xe văn minh và tin cậy.
                        </p>

                        <h6 class="font-bold mb-3">7. Hỗ trợ và giải quyết vấn đề phát sinh</h6>
                        <p>
                            Trong trường hợp xảy ra sự cố bất thường khi nhận lại xe (như tai nạn nghiêm trọng hoặc khách thuê không hợp tác), Chủ xe vui lòng tạm thời CHƯA bấm xác nhận hoàn tất và liên hệ ngay với bộ phận Chăm sóc Khách hàng của Drivio qua số Hotline hoặc kênh hỗ trợ trực tuyến để được hướng dẫn giải quyết kịp thời.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import PolicyBanner from '~/components/Banner/PolicyBanner.vue'
import PolicySidebar from '~/components/PolicySidebar.vue'

useSeoMeta({
    title: 'Chính Sách Kiểm Tra Xe & Hoàn Thành Chuyến Đi | DRIVIO — Trải Nghiệm Thuê Xe Khác Biệt',
    description: 'Quy trình kiểm tra hiện trạng xe và trách nhiệm của chủ xe khi kết thúc chuyến thuê trên nền tảng thuê xe tự lái Drivio.',
    ogTitle: 'Chính Sách Kiểm Tra Xe & Hoàn Thành Chuyến Đi | Drivio',
    ogDescription: 'Quy trình kiểm tra hiện trạng xe và trách nhiệm của chủ xe khi kết thúc chuyến thuê trên nền tảng thuê xe tự lái Drivio.',
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

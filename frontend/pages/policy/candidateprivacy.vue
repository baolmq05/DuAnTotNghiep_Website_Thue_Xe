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
                        <h1 class="font-bold text-2xl">Thông báo về việc xử lý và bảo vệ dữ liệu cá nhân của ứng viên
                        </h1>
                    </div>
                    <div class="main-content text-justify">
                        <p>Bằng việc nộp hồ sơ ứng tuyển cho Công ty Cổ phần Mioto Asia (“Mioto”) thông qua bất kỳ hình
                            thức và phương tiện nào (tại trụ sở Công ty/ website của Công ty/ thư điện tử/ mạng xã hội/
                            website tuyển dụng/ đường bưu điện,….), ứng viên xác nhận hiểu rõ các nội dung liên quan tới
                            Thông báo về việc xử lý dữ liệu cá nhân này (“Thông báo”) và tự nguyện đồng ý với việc Công
                            ty và các bên có liên quan khác xử lý dữ liệu cá nhân của ứng viên.</p>
                        <h6 class="font-bold mb-3">1. Bên kiểm soát và xử lý Dữ liệu cá nhân</h6>
                        <p>Bên kiểm soát và xử lý DLCN là Công ty Cổ phần Mioto Asia, địa chỉ: Số 561A Điện Biên Phủ,
                            Phường Thạnh Mỹ Tây, Thành phố Hồ Chí Minh; số điện thoại: 028 6286 1989. Để biết thêm chi
                            tiết về Mioto, vui lòng truy cập website của chúng tôi: https://www.mioto.vn</p>
                        <h6 class="font-bold mb-3">2. Loại Dữ liệu cá nhân được xử lý</h6>
                        <p>Loại DLCN mà Mioto có thể xử lý tùy theo vị trí ứng tuyển và quy định pháp luật bao gồm:</p>
                        <h6 class="font-bold mb-3">2.1. DLCN cơ bản</h6>
                        <p>Họ, chữ đệm và tên khai sinh, tên gọi khác (nếu có); Ngày, tháng, năm sinh; Giới tính; Nơi
                            sinh, nơi đăng ký khai sinh, nơi đăng ký thường trú, nơi đăng ký tạm trú, nơi ở hiện tại,
                            quê quán, địa chỉ liên hệ; Quốc tịch; Hình ảnh của cá nhân; Số điện thoại, số định danh cá
                            nhân, số hộ chiếu, số giấy phép lái xe, số biển số xe; Tình trạng hôn nhân; Thông tin về mối
                            quan hệ gia đình (cha mẹ, con, vợ, chồng); Thông tin về tài khoản số của cá nhân; Các thông
                            tin khác gắn liền với một con người cụ thể hoặc giúp xác định một con người cụ thể không
                            thuộc danh mục DLCN nhạy cảm dưới đây và theo quy định của pháp luật hiện hành.</p>
                        <h6 class="font-bold mb-3">2.2. DLCN nhạy cảm</h6>
                        <p>Tình trạng sức khỏe; Dữ liệu về tội phạm, vi phạm pháp luật được thu thập, lưu trữ bởi các cơ
                            quan thực thi pháp luật; Hình ảnh căn cước/ căn cước công dân; DLCN khác được pháp luật quy
                            định cần giữ bí mật hoặc cần có biện pháp bảo vệ chặt chẽ.</p>
                        <h6 class="font-bold mb-3">3. Cách thức thu thập và xử lý</h6>
                        <p>DLCN của ứng viên được thu thập từ các nguồn sau:</p>
                        <p>- Từ ứng viên cung cấp cho chúng tôi qua email: contact@mioto.vn hoặc website:
                            https://www.mioto.vn hoặc đến địa chỉ trụ sở, chi nhánh, văn phòng đại diện, địa điểm kinh
                            doanh của Mioto.</p>
                        <p>- Từ các đơn vị cung cấp dịch vụ như bên cung cấp dịch vụ tuyển dụng, bên cung cấp dịch vụ
                            quảng cáo/đăng tin tuyển dụng, từ doanh nghiệp mà ứng viên đã/đang làm việc.</p>
                        <p>- Từ các cá nhân giới thiệu/kết nối ứng viên với chúng tôi.</p>
                        <p>- Từ các nguồn dữ liệu công khai sẵn có và/hoặc từ các nguồn và các bên thứ ba khác được ứng
                            viên đồng ý tiết lộ dữ liệu cho chúng tôi.</p>
                        <h6 class="font-bold mb-3">4. Mục đích xử lý</h6>
                        <p>Mioto sẽ chỉ xử lý DLCN của ứng viên mà chúng tôi tiếp nhận, thu thập được trong giới hạn cần
                            thiết để trao đổi với ứng viên về cơ hội nghề nghiệp, xem xét việc tuyển dụng ứng viên cho
                            các vai trò, vị trí cụ thể tại Mioto, bao gồm:</p>
                        <p>- Đánh giá và sàng lọc hồ sơ ứng tuyển;</p>
                        <p>- Liên hệ với ứng viên để đặt lịch phỏng vấn, trao đổi thông tin, thông báo kết quả tuyển
                            dụng;</p>
                        <p>- Xác minh tất cả các tài liệu tham khảo được cung cấp và tiến hành kiểm tra sàng lọc để đảm
                            bảo sự phù hợp của ứng viên với vị trí ứng tuyển;</p>
                        <p>- Kiểm tra, tham chiếu lịch sử, kinh nghiệm làm việc, trình độ học vấn, kỹ năng, chứng chỉ
                            của ứng viên;</p>
                        <p>- Kiểm tra, xác định quyền lợi xung đột lợi ích;</p>
                        <p>- Chuẩn bị, gửi Thư mời nhận việc, đề xuất thu nhập;</p>
                        <p>- Rà soát, cải thiện chính sách và quy trình tuyển dụng của chúng tôi;</p>
                        <p>- Nghiên cứu, lập kế hoạch, phân tích thống kê và các báo cáo nội bộ liên quan đến hoạt động
                            tuyển dụng;</p>
                        <p>- Bất kỳ mục đích nào khác liên quan đến hoặc phát sinh từ những nội dung nêu trên.</p>
                        <h6 class="font-bold mb-3">5. Dữ liệu cá nhân của ứng viên có thể được chia sẻ cho đối tượng nào</h6>
                        <p>Để thực hiện một hay nhiều mục đích nêu tại mục 4 của Thông báo này, Mioto có thể cần phải
                            chia sẻ DLCN của ứng viên cho Bên xử lý dữ liệu, và việc chia sẻ này sẽ được thực hiện trong
                            phạm vi nhất định, cần thiết, theo đúng quy định của pháp luật tại từng thời điểm. Bên xử lý
                            DLCN tại mục này bao gồm:</p>
                        <p>- Các cá nhân là đại diện theo ủy quyền của chúng tôi;</p>
                        <p>- Các tổ chức, cá nhân có liên quan để phục vụ cho mục đích kiểm tra, tham chiếu lịch sử,
                            kinh nghiệm làm việc, trình độ học vấn, kỹ năng, chứng chỉ của ứng viên; kiểm tra, xác định
                            quyền lợi xung đột lợi ích;</p>
                        <p>- Các nhà cung cấp dịch vụ các các bên thứ ba khác mà chúng tôi thuê để hỗ trợ hoặc bổ sung
                            cho việc thực hiện tuyển dụng hoặc các nghĩa vụ pháp lý khác. Các bên này có thể bao gồm:
                            bên cung cấp dịch vụ tuyển dụng, quảng cáo đăng tin tuyển dụng, bên cung cấp dịch vụ viễn
                            thông thông, máy tính, internet, dịch vụ công nghệ thông tin, dịch vụ điện toán đám mây,…;
                        </p>
                        <p>- Các cơ quan nhà nước có thẩm quyền và các tổ chức, cá nhân khác mà pháp luật cho phép hoặc
                            ứng viên đồng ý.</p>
                        <h6 class="font-bold mb-3">6. Thời hạn lưu trữ dữ liệu cá nhân</h6>
                        <p>DLCN của ứng viên sẽ được lưu trữ trong thời hạn cần thiết cho các mục đích nêu tại Thông báo
                            này và/hoặc Chính sách bảo vệ DLCN của Mioto áp dụng cho nhân viên (trong trường hợp ứng
                            viên được lựa chọn cho vị trí tuyển dụng).</p>
                        <p>Sau khi quy trình tuyển dụng kết thúc (ví dụ: từ chối từ phía chúng tôi hoặc ứng viên không
                            tiếp tục ứng tuyển), DLCN của ứng viên sẽ được xóa trong vòng 06 tháng.</p>
                        <p>Trong trường hợp pháp luật có quy định, chúng tôi có thể lưu trữ DLCN của ứng viên dài hơn
                            thời hạn lưu trữ theo quy định tại Thông báo này.</p>
                        <h6 class="font-bold mb-3">7. Quyền của ứng viên</h6>
                        <p>- Được biết về hoạt động xử lý dữ liệu cá nhân.</p>
                        <p>- Đồng ý hoặc không đồng ý, yêu cầu rút lại sự đồng ý: Ứng viên có thể rút lại sự đồng ý về
                            DLCN đã cung cấp. Rút lại sự đồng ý không làm ảnh hưởng tính hợp pháp của hoạt động xử lý đã
                            thực hiện trước thời điểm rút lại.</p>
                        <p>- Xem, chỉnh sửa hoặc yêu cầu chỉnh sửa dữ liệu cá nhân: Ứng viên có thể yêu cầu Mioto cung
                            cấp bản sao DLCN mà Mioto đang lưu giữ; có quyền yêu cầu sửa, cập nhật hoặc bổ sung dữ liệu
                            sai lệch, không đầy đủ. Mioto sẽ hỗ trợ cập nhật kịp thời sau khi nhận được yêu cầu và tài
                            liệu liên quan.</p>
                        <p>- Yêu cầu cung cấp, xóa, hạn chế xử lý dữ liệu cá nhân; gửi yêu cầu phản đối xử lý dữ liệu cá
                            nhân.</p>
                        <p>- Khiếu nại, tố cáo, khởi kiện, yêu cầu bồi thường thiệt hại theo quy định của pháp luật;</p>
                        <p>- Yêu cầu cơ quan có thẩm quyền hoặc cơ quan, tổ chức, cá nhân liên quan đến xử lý dữ liệu cá
                            nhân thực hiện các biện pháp, giải pháp bảo vệ dữ liệu cá nhân của mình theo quy định của
                            pháp luật.</p>
                        <h6 class="font-bold mb-3">8. Thông tin liên hệ</h6>
                        <p>Nếu có thắc mắc hoặc yêu cầu/khiếu nại nào liên quan đến quyền của mình như trên, ứng viên
                            vui lòng liên hệ với chúng tôi qua thông tin được cung cấp dưới đây:</p>
                        <p>Công ty Cổ phần Mioto Asia</p>
                        <p>Địa chỉ: Văn phòng 01, Tầng 09, Tòa nhà Pearl Plaza, Số 561A Điện Biên Phủ, Phường
                            Thạnh Mỹ Tây, Thành phố Hồ Chí Minh, Việt Nam.</p>
                        <p>Số điện thoại: 028 6286 1989; Email: contact@mioto.vn; Website: https://www.mioto.vn</p>
                        <h6 class="font-bold mb-3">9. Điều chỉnh Thông báo về về việc xử lý và bảo vệ dữ liệu cá nhân</h6>
                        <p>Chúng tôi có thể cập nhật, sửa đổi Thông báo về bảo vệ DLCN này tại từng thời điểm. Phiên bản
                            mới nhất của Thông báo này sẽ được chúng tôi niêm yết công khai tại website:
                            https://www.mioto.vn</p>
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
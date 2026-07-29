<template>
    <div class="bg-slate-50 min-h-screen font-sans antialiased text-gray-900 pb-16">
        <!-- Banner Component -->
        <BannerContactBanner />

        <!-- Main Content Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 md:mt-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- Left: Contact info & Support channels (5 cols) -->
                <div class="lg:col-span-5 space-y-8">
                    <!-- Info Card -->
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 space-y-6">
                        <div class="space-y-2">
                            <span class="text-brand-primary font-bold text-xs uppercase tracking-widest block">Thông Tin Liên Hệ</span>
                            <h2 class="text-2xl md:text-3xl font-black text-gray-950 tracking-tight">Kênh liên lạc chính thức</h2>
                        </div>
                        <p class="text-sm text-gray-500 leading-relaxed">
                            Vui lòng liên hệ với chúng tôi qua các kênh dưới đây hoặc điền vào biểu mẫu bên cạnh để nhận hỗ trợ nhanh chóng nhất.
                        </p>

                        <!-- Contact List -->
                        <div class="space-y-4 pt-2">
                            <!-- Phone -->
                            <div class="flex gap-4 items-start">
                                <div class="p-3 bg-teal-50 text-brand-primary rounded-2xl border border-teal-100">
                                    <Icon name="heroicons:phone" class="w-6 h-6" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-950 text-sm">Điện thoại Hotline</h4>
                                    <p class="text-brand-primary font-extrabold text-lg mt-0.5">1900 8888</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Hỗ trợ khẩn cấp & cứu hộ 24/7</p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex gap-4 items-start">
                                <div class="p-3 bg-amber-50 text-brand-accent rounded-2xl border border-amber-100">
                                    <Icon name="heroicons:envelope" class="w-6 h-6" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-950 text-sm">Địa chỉ Email</h4>
                                    <p class="text-gray-700 font-semibold text-sm mt-0.5">support@drivio.vn</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Thời gian phản hồi trung bình: 2-4 tiếng</p>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="flex gap-4 items-start">
                                <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100">
                                    <Icon name="heroicons:map-pin" class="w-6 h-6" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-950 text-sm">Văn phòng đại diện</h4>
                                    <p class="text-gray-700 text-sm mt-0.5">Số 1 Lý Tự Trọng, Quận Ninh Kiều, TP. Cần Thơ</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Giờ làm việc: 8:00 - 18:00 (Thứ 2 - Thứ 7)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Direct Support Channels -->
                    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 space-y-6">
                        <h3 class="font-bold text-lg text-gray-950">Kênh chat trực tuyến</h3>
                        <p class="text-sm text-gray-500">
                            Kết nối trực tiếp với nhân viên tư vấn qua các ứng dụng chat quen thuộc.
                        </p>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Messenger -->
                            <a href="#" class="flex flex-col items-center justify-center p-4 rounded-2xl border border-slate-100 hover:border-brand-primary/30 hover:bg-slate-50 transition-all duration-200 text-center group">
                                <Icon name="fa6-brands:facebook-messenger" class="w-8 h-8 text-blue-600 mb-2 group-hover:scale-110 transition-transform" />
                                <span class="font-bold text-xs text-gray-800">Facebook Chat</span>
                                <span class="text-[10px] text-gray-400 mt-0.5">Kết nối Fanpage</span>
                            </a>

                            <!-- Zalo -->
                            <a href="#" class="flex flex-col items-center justify-center p-4 rounded-2xl border border-slate-100 hover:border-brand-primary/30 hover:bg-slate-50 transition-all duration-200 text-center group">
                                <Icon name="simple-icons:zalo" class="w-8 h-8 text-sky-500 mb-2 group-hover:scale-110 transition-transform" />
                                <span class="font-bold text-xs text-gray-800">Zalo Hotline</span>
                                <span class="text-[10px] text-gray-400 mt-0.5">Hỗ trợ trực tiếp</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right: Contact Form Layout (7 cols) -->
                <div id="contact-form" class="lg:col-span-7 bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 space-y-6">
                    <div class="space-y-2">
                        <span class="text-brand-accent font-bold text-xs uppercase tracking-widest block">Gửi Tin Nhắn</span>
                        <h2 class="text-2xl md:text-3xl font-black text-gray-950 tracking-tight">Yêu cầu tư vấn & hỗ trợ</h2>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Hãy để lại lời nhắn cho DRIVIO bằng cách điền thông tin vào mẫu dưới đây.
                    </p>

                    <!-- Form Mockup -->
                    <form @submit.prevent class="space-y-4 pt-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700">Họ và tên</label>
                                <input type="text" placeholder="Nguyễn Văn A" class="w-full text-sm px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-primary/50 focus:ring-2 focus:ring-brand-primary/10 transition-all duration-150" />
                            </div>

                            <!-- Phone -->
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-700">Số điện thoại</label>
                                <input type="tel" placeholder="0901234567" class="w-full text-sm px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-primary/50 focus:ring-2 focus:ring-brand-primary/10 transition-all duration-150" />
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-700">Địa chỉ Email</label>
                            <input type="email" placeholder="email@example.com" class="w-full text-sm px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-primary/50 focus:ring-2 focus:ring-brand-primary/10 transition-all duration-150" />
                        </div>

                        <!-- Subject -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-700">Chủ đề hỗ trợ</label>
                            <select class="w-full text-sm px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-primary/50 focus:ring-2 focus:ring-brand-primary/10 bg-white transition-all duration-150">
                                <option value="" disabled selected>Chọn chủ đề bạn quan tâm</option>
                                <option value="1">Hỗ trợ đặt xe tự lái</option>
                                <option value="2">Hợp tác đăng ký chủ xe</option>
                                <option value="3">Đóng góp ý kiến & Khiếu nại</option>
                                <option value="4">Báo cáo sự cố kỹ thuật</option>
                                <option value="5">Yêu cầu khác</option>
                            </select>
                        </div>

                        <!-- Message -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-700">Nội dung chi tiết</label>
                            <textarea rows="4" placeholder="Nhập lời nhắn hoặc câu hỏi của bạn tại đây..." class="w-full text-sm px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-primary/50 focus:ring-2 focus:ring-brand-primary/10 transition-all duration-150 resize-none"></textarea>
                        </div>

                        <!-- Submit Button (Disabled/Mock Only) -->
                        <div class="pt-2">
                            <button type="button" class="w-full py-4 bg-brand-primary text-white rounded-xl font-bold shadow-md shadow-brand-primary/15 hover:bg-[#1e505a] hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                                Gửi thông tin liên hệ
                                <Icon name="heroicons:paper-airplane" class="w-4 h-4" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FAQ Accordion Section -->
            <div id="faq" class="mt-20 max-w-4xl mx-auto space-y-8">
                <div class="text-center space-y-2">
                    <span class="text-brand-primary font-bold text-xs uppercase tracking-widest block">Giải Đáp Thắc Mắc</span>
                    <h2 class="text-3xl font-black text-gray-950 tracking-tight">Câu hỏi thường gặp (FAQs)</h2>
                    <p class="text-sm text-gray-500 max-w-xl mx-auto">
                        Tìm câu trả lời nhanh chóng cho các thắc mắc phổ biến nhất về quy trình đặt xe, giấy tờ thủ tục và chính sách của DRIVIO.
                    </p>
                </div>

                <div class="space-y-4">
                    <div 
                        v-for="(faq, index) in faqs" 
                        :key="index"
                        class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm transition-all duration-200"
                    >
                        <button 
                            @click="toggleFaq(index)"
                            class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none group"
                        >
                            <span class="font-bold text-gray-900 group-hover:text-brand-primary transition-colors text-sm sm:text-base">
                                {{ faq.question }}
                            </span>
                            <div class="p-1 rounded-lg bg-slate-50 text-gray-500 group-hover:text-brand-primary transition-colors shrink-0 ml-4">
                                <Icon 
                                    name="heroicons:chevron-down" 
                                    :class="['w-5 h-5 transition-transform duration-300', activeFaq === index ? 'rotate-180 text-brand-primary' : '']" 
                                />
                            </div>
                        </button>

                        <div 
                            v-show="activeFaq === index"
                            class="px-6 pb-5 pt-0 text-sm text-gray-600 leading-relaxed border-t border-slate-50 animate-fade-in"
                        >
                            {{ faq.answer }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Mockup / Location Frame -->
            <div class="mt-20 space-y-6">
                <div class="text-center space-y-2">
                    <h2 class="text-2xl font-black text-gray-950 tracking-tight">Vị trí của chúng tôi</h2>
                    <p class="text-sm text-gray-500">
                        Ghé thăm văn phòng DRIVIO để được tư vấn trực tiếp và làm việc với đối tác của chúng tôi.
                    </p>
                </div>

                <!-- Styled Map container with Google Map Embed -->
                <div class="relative rounded-3xl overflow-hidden shadow-md border border-slate-100 h-[400px] md:h-[450px]">
                    <iframe 
                        src="https://maps.google.com/maps?q=Số%201%20Lý%20Tự%20Trọng,%20Ninh%20Kiều,%20Cần%20Thơ,%20Việt%20Nam&t=&z=16&ie=UTF8&iwloc=&output=embed"
                        class="w-full h-full border-0 absolute inset-0"
                        allowfullscreen="false"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                    
                    <!-- Floating Address Info (Desktop) -->
                    <div class="hidden md:block absolute bottom-6 left-6 bg-white/95 backdrop-blur-md p-6 rounded-2xl shadow-lg border border-slate-100 max-w-sm z-10">
                        <div class="flex gap-3 items-start">
                            <div class="p-2 bg-brand-primary/10 text-brand-primary rounded-xl shrink-0 mt-0.5">
                                <Icon name="heroicons:map-pin" class="w-5 h-5" />
                            </div>
                            <div class="space-y-1">
                                <h4 class="font-bold text-gray-950 text-sm">Văn phòng DRIVIO Cần Thơ</h4>
                                <p class="text-xs text-gray-600 leading-relaxed">
                                    Số 1 Lý Tự Trọng, Phường An Phú, Quận Ninh Kiều, Thành phố Cần Thơ, Việt Nam.
                                </p>
                                <span class="inline-block text-[10px] font-bold text-brand-primary uppercase tracking-wide pt-1">
                                    Mở cửa: 8:00 AM - 6:00 PM
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

useSeoMeta({
  title: 'Liên Hệ & Hỗ Trợ Khách Hàng | DRIVIO',
  description: 'Liên hệ DRIVIO — Hotline 1900 8888 hỗ trợ 24/7. Giải đáp thắc mắc thuê xe, tiếp nhận khiếu nại, tư vấn đặt xe nhanh chóng và chuyên nghiệp.',
  keywords: 'liên hệ drivio, hỗ trợ thuê xe, hotline drivio, tư vấn thuê xe, khiếu nại drivio',
  ogTitle: 'Liên Hệ DRIVIO — Hỗ Trợ 24/7',
  ogDescription: 'Hotline 1900 8888 — hỗ trợ đặt xe, giải đáp thắc mắc, tiếp nhận phản hồi.',
  ogImage: '/images/og/contact.jpg',
  twitterCard: 'summary_large_image',
})

useHead({
  link: [
    { rel: 'canonical', href: 'https://drivio.vn/contact' }
  ]
})

// FAQ Active Index
const activeFaq = ref<number | null>(0)

const toggleFaq = (index: number) => {
    if (activeFaq.value === index) {
        activeFaq.value = null
    } else {
        activeFaq.value = index
    }
}

// Mock FAQs Data
const faqs = ref([
    {
        question: 'Tôi cần những giấy tờ gì để thuê xe tại DRIVIO?',
        answer: 'Để thuê xe, bạn cần chuẩn bị đầy đủ các giấy tờ sau: Căn cước công dân (CCCD) có gắn chip hoặc Hộ chiếu gốc, Giấy phép lái xe (GPLX) hợp lệ hạng B1 trở lên, và Tài sản thế chấp (xe máy trị giá trên 15 triệu hoặc tiền mặt từ 15 triệu trở lên, tùy yêu cầu cụ thể của từng chủ xe được ghi chú trong phần chi tiết xe).'
    },
    {
        question: 'Quy trình đặt xe và thanh toán diễn ra như thế nào?',
        answer: 'Bước 1: Tìm kiếm xe và chọn thời gian di chuyển. Bước 2: Gửi yêu cầu đặt xe cho chủ xe. Bước 3: Sau khi chủ xe chấp nhận, bạn tiến hành đặt cọc giữ chỗ qua cổng thanh toán (VNPAY, chuyển khoản hoặc ví điện tử). Bước 4: Gặp chủ xe tại điểm hẹn để bàn giao xe, kiểm tra tình trạng xe và ký hợp đồng thuê xe.'
    },
    {
        question: 'Chính sách hủy chuyến của DRIVIO như thế nào?',
        answer: 'Bạn được phép hủy chuyến miễn phí nếu gửi yêu cầu hủy trước giờ khởi hành dự kiến ít nhất 24 giờ. Nếu hủy chuyến trong vòng 24 giờ trước thời gian nhận xe, bạn sẽ chịu một khoản phí hủy chuyến nhất định (thường từ 30% - 50% tiền cọc) để đền bù tổn thất thời gian cho chủ xe theo đúng quy định của nền tảng.'
    },
    {
        question: 'DRIVIO hỗ trợ thế nào nếu xe gặp sự cố hoặc tai nạn dọc đường?',
        answer: 'Khi có bất kỳ sự cố kỹ thuật hoặc va chạm nào xảy ra, vui lòng giữ bình tĩnh, đỗ xe vào nơi an toàn và gọi ngay hotline chăm sóc khách hàng 1900 8888. Đội ngũ kỹ thuật viên của DRIVIO sẽ phối hợp cùng chủ xe và các đơn vị bảo hiểm liên quan để hướng dẫn bạn xử lý chi tiết tại hiện trường hoặc điều xe cứu hộ hỗ trợ kịp thời.'
    },
    {
        question: 'Làm thế nào để trở thành chủ xe đăng ký cho thuê xe trên DRIVIO?',
        answer: 'Bạn chỉ cần truy cập mục "Trở thành chủ xe" trên thanh điều hướng hoặc tải ứng dụng DRIVIO dành cho chủ xe. Đăng tải hình ảnh thực tế của xe kèm các giấy tờ bắt buộc như Đăng ký xe (Cà vẹt), Đăng kiểm còn hiệu lực và Bảo hiểm trách nhiệm dân sự. Đội ngũ vận hành sẽ duyệt hồ sơ xe của bạn trong vòng 24 giờ làm việc.'
    }
])
</script>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-4px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in {
    animation: fadeIn 0.25s ease-out forwards;
}
</style>

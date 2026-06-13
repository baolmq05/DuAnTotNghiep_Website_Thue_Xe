<template>
    <div class="min-h-screen bg-[#f8f9fa] font-sans text-[#333333] pb-24 antialiased">

        <div class="bg-[#286874] pt-24 pb-12 text-center">
            <h2 class="text-[32px] font-bold text-white tracking-tight">
                Ví của tôi
            </h2>
        </div>

        <div class="max-w-4xl mx-auto px-4 -mt-6 relative z-10">
            <div class="bg-white rounded-xl border border-slate-200 p-6 relative shadow-sm text-center">
                <button
                    class="absolute left-6 top-6 flex items-center gap-1 text-slate-500 hover:text-black transition text-sm font-medium">
                    <Icon name="lucide:chevron-left" class="w-4 h-4" />
                    Quay lại
                </button>
                <div class="py-2">
                    <p class="text-slate-500 text-[13px] font-medium">
                        Số dư ví
                    </p>
                    <p class="text-4xl font-bold text-[#286874] mt-1.5 tracking-tight">
                        {{ formatCurrency(balance) }}
                    </p>
                </div>

            </div>
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mt-6 shadow-sm">
                <div class="bg-[#111111] text-white px-5 py-3.5 flex items-center justify-between">
                    <h3 class="font-bold text-xs tracking-wider uppercase">
                        Bảng tổng hợp giao dịch
                    </h3>

                    <div class="flex items-center gap-1 text-xs font-medium text-slate-300 cursor-pointer select-none">
                        <span>Tháng 06-2026</span>
                        <Icon name="lucide:chevron-down" class="w-3 h-3 text-slate-400" />
                    </div>
                </div>

                <div class="flex w-full text-center bg-white border-b border-slate-200 divide-x divide-slate-100">
                    <div class="flex-1 py-3.5 flex flex-col justify-center items-center">
                        <p
                            class="text-base font-bold text-slate-800 flex items-center justify-center gap-0.5 leading-none">
                            <span class="text-amber-400 text-sm">★</span> 0.0
                        </p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Đánh giá</p>
                    </div>

                    <div class="flex-1 py-3.5 flex flex-col justify-center items-center">
                        <p class="text-base font-bold text-slate-800 leading-none">0</p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Chuyến đi thành công</p>
                    </div>

                    <div class="flex-1 py-3.5 bg-[#fafafa] flex flex-col justify-center items-center">
                        <p class="text-base font-medium text-slate-400 leading-none">-</p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Tỉ lệ phản hồi</p>
                    </div>

                    <div class="flex-1 py-3.5 bg-[#fafafa] flex flex-col justify-center items-center">
                        <p class="text-base font-medium text-slate-400 leading-none">-</p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Phản hồi trong</p>
                    </div>

                    <div class="flex-1 py-3.5 bg-[#fafafa] flex flex-col justify-center items-center">
                        <p class="text-base font-medium text-slate-400 leading-none">-</p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Tỉ lệ đồng ý</p>
                    </div>
                </div>

                <div
                    class="flex w-full text-center bg-white py-3.5 px-1 border-b border-slate-200 text-[11px] font-bold text-slate-700 leading-tight">
                    <div class="flex-1 flex items-center justify-center">Mã chuyến đi</div>
                    <div class="flex-1 flex items-center justify-center">Hình thức</div>
                    <div class="flex-1 flex items-center justify-center">Ngày đi</div>
                    <div class="flex-1 flex items-center justify-center">Ngày về</div>
                    <div class="flex-1 flex items-center justify-center">Đơn giá thuê</div>
                    <div class="flex-1 flex items-center justify-center">Doanh thu chủ xe</div>
                    <div class="flex-1 flex items-center justify-center">Tiền đã nhận</div>
                    <div class="flex-1 flex items-center justify-center gap-0.5 whitespace-nowrap">
                        Thuế kinh doanh
                        <span
                            class="inline-flex items-center justify-center w-3 h-3 rounded-full border border-slate-400 text-[9px] text-slate-400 font-bold cursor-help shrink-0">?</span>
                    </div>
                    <div class="flex-1 flex items-center justify-center">Thay đổi số dư</div>
                </div>

                <div class="bg-white text-xs font-medium text-slate-700 divide-y divide-slate-100">
                    <div class="flex justify-between px-5 py-3 bg-[#fafafa]">
                        <span class="text-slate-500">Tổng thay đổi - Chuyến đi hoàn thành</span>
                        <span class="font-normal text-slate-800">0đ</span>
                    </div>
                    <div class="flex justify-between px-5 py-3 bg-[#fafafa]">
                        <span class="text-slate-500">Tổng thay đổi - Giao dịch rút/nộp tiền</span>
                        <span class="font-normal text-slate-800">0đ</span>
                    </div>
                    <div class="flex justify-between px-5 py-3 bg-[#fafafa]">
                        <span class="text-slate-500">Tổng thay đổi - Giao dịch hủy chuyến</span>
                        <span class="font-normal text-slate-800">0đ</span>
                    </div>

                    <div class="p-5 space-y-3 bg-white">
                        <div class="flex justify-between font-bold text-slate-400 text-[11px] tracking-wider">
                            <span>TỔNG CỘNG THAY ĐỔI TRONG KÌ</span>
                            <span class="text-slate-800">0đ</span>
                        </div>
                        <div class="flex justify-between font-normal text-slate-700">
                            <span>TIỀN ĐẦU KÌ</span>
                            <span class="text-slate-800">0đ</span>
                        </div>
                        <div class="flex justify-between font-bold text-[#286874]">
                            <span>TIỀN CUỐI KÌ</span>
                            <span>0đ</span>
                        </div>
                        <div class="flex justify-between font-semibold text-[#e05638]">
                            <span>THUẾ KINH DOANH ĐÃ KHẤU TRỪ</span>
                            <span>(0đ)</span>
                        </div>
                        <div class="flex justify-between font-bold text-[#2f80ed]">
                            <span>THU NHẬP CHỦ XE</span>
                            <span>0đ</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-6">
                <button
                    class="h-12 rounded-lg bg-[#286874] text-white font-bold text-sm hover:bg-[#1d4f59] transition-colors">
                    Gửi yêu cầu rút tiền
                </button>

                <button
                    class="h-12 rounded-lg border border-[#286874] text-[#286874] font-bold text-sm hover:bg-[#286874]/5 transition-colors text-center">
                    Xem Sao kê chi tiết giao dịch
                </button>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const balance = ref(0)

const formatCurrency = (value) => {
    return new Intl.NumberFormat('vi-VN').format(value) + 'đ'
}
</script>

<style scoped>
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
}
</style>
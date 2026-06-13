<template>
    <div class="min-h-screen bg-slate-50 font-sans text-[#333333] pb-12 antialiased">
        <section class="bg-gradient-to-r from-[#1e4e57] to-[#286874] text-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16 md:pt-28 md:pb-20 text-center">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight">
                    Sao kê chi tiết giao dịch
                </h1>

                <div class="flex items-center justify-center gap-2 text-sm text-cyan-50/90 mt-4 font-medium">
                    <span>Từ ngày</span>
                    <span
                        class="bg-white/10 border border-white/20 px-3 py-1 rounded font-mono text-white">01/06/2026</span>
                    <span class="ml-2">Đến ngày</span>
                    <span
                        class="bg-white/10 border border-white/20 px-3 py-1 rounded font-mono text-white">30/06/2026</span>
                </div>
            </div>
        </section>

        <section class="pb-6 mt-3 ">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-start w-full">                    <button
                        class="flex items-center gap-1 text-black hover:text-black transition text-sm font-medium focus:outline-none pt-2">
                        <Icon name="lucide:chevron-left" class="w-4 h-4" />
                        Quay lại
                    </button>

                    <!-- KHỐI THÔNG TIN CHỦ XE: Bị đẩy sát về góc bên phải -->
                    <div
                        class="rounded-2xl shadow-lg shadow-[#286874]/5 bg-white p-4 md:p-5 w-full max-w-sm border border-slate-100">
                        <table class="w-full border-separate border-spacing-y-1 text-sm">
                            <tbody>
                                <tr>
                                    <td class="w-20 text-slate-500 font-bold">Chủ xe</td>
                                    <td class="bg-slate-50 px-3 py-1.5 text-slate-800 font-semibold rounded-lg">xuanbac
                                        pham</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-500 font-bold">Mã số</td>
                                    <td
                                        class="bg-slate-50 px-3 py-1.5 text-slate-800 font-mono font-semibold rounded-lg">
                                        UK8U32</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </section>

        <section class="py-6 space-y-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

                <div class="space-y-3">
                    <h2 class="text-base font-bold text-slate-900 px-1">Chuyến đi hoàn thành trong kì</h2>
                    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-center text-[11px] border-collapse min-w-[1150px]">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200">
                                        <th colspan="2" class="py-2.5 border-r border-slate-200"></th>
                                        <th colspan="3" class="py-2.5 border-r border-slate-200">Thời gian</th>
                                        <th colspan="3" class="py-2.5 border-r border-slate-200">Thông tin chuyến đi
                                        </th>
                                        <th colspan="2" class="py-2.5 border-r border-slate-200">Thanh toán</th>
                                        <th colspan="4" class="py-2.5"></th>
                                    </tr>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold">
                                        <th class="py-3 px-1 border-r border-slate-200">Mã chuyến đi</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Biển số xe</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Ngày đi</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Ngày về</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Ngày đặt xe</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Khách hàng</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Xe thuê</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Đơn giá</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Thanh toán giữ chỗ tại Mioto</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Thanh toán chủ xe</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Mioto KM</th>
                                        <th class="py-3 px-1 border-r border-gray-200">Phí dịch vụ</th>
                                        <th
                                            class="py-3 px-1 border-r border-gray-200 flex items-center justify-center gap-0.5 whitespace-nowrap">
                                            Thuế kinh doanh
                                            <span
                                                class="inline-flex items-center justify-center w-3 h-3 rounded-full border border-slate-400 text-[9px] font-bold">?</span>
                                        </th>
                                        <th class="py-3 px-1">Thay đổi số dư</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-slate-200 bg-white">
                                        <td colspan="13"
                                            class="text-right py-3.5 font-bold text-slate-900 pr-12 text-xs">Tổng thay
                                            đổi - Chuyến đi hoàn thành</td>
                                        <td class="py-3.5 font-bold text-slate-900 text-xs text-center">0đ</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 max-w-xl">
                    <h2 class="text-base font-bold text-slate-900 px-1">Giao dịch rút/nộp tiền trong kì</h2>
                    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                        <table class="w-full text-center text-[11px] border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold">
                                    <th class="py-3 border-r border-slate-200">Ngày giao dịch</th>
                                    <th class="py-3 border-r border-slate-200">Nội dung</th>
                                    <th class="py-3">Thay đổi số dư</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-t border-slate-200 bg-white">
                                    <td colspan="2" class="text-left pl-6 py-3.5 font-bold text-slate-900 text-xs">Tổng
                                        thay đổi - Giao dịch rút/nộp tiền</td>
                                    <td class="py-3.5 font-bold text-slate-900 text-xs text-center">0đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-3">
                    <h2 class="text-base font-bold text-slate-900 px-1">Giao dịch hủy chuyến trong kì</h2>
                    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-center text-[11px] border-collapse min-w-[1100px]">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200">
                                        <th colspan="2" class="py-2.5 border-r border-slate-200"></th>
                                        <th colspan="3" class="py-2.5 border-r border-slate-200">Thời gian</th>
                                        <th colspan="3" class="py-2.5 border-r border-slate-200">Thông tin chuyến đi
                                        </th>
                                        <th colspan="2" class="py-2.5 border-r border-slate-200">Thanh toán</th>
                                        <th colspan="2" class="py-2.5"></th>
                                    </tr>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold">
                                        <th class="py-3 px-1 border-r border-slate-200">Mã chuyến đi</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Biển số xe</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Ngày đi</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Ngày về</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Ngày hủy chuyến</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Khách hàng</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Xe thuê</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Đơn giá</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Thanh toán giữ chỗ tại Mioto
                                        </th>
                                        <th class="py-3 px-1 border-r border-slate-200">Thanh toán chủ xe</th>
                                        <th class="py-3 px-1 border-r border-slate-200">Nội dung hủy chuyến</th>
                                        <th class="py-3 px-1">Thay đổi số dư</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-slate-200 bg-white">
                                        <td colspan="11"
                                            class="text-right py-3.5 font-bold text-slate-900 pr-12 text-xs">Tổng thay
                                            đổi - Giao dịch hủy chuyến</td>
                                        <td class="py-3.5 font-bold text-slate-900 text-xs text-center">0đ</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-2xl border border-slate-200/60 bg-white text-xs font-medium text-slate-700 divide-y divide-slate-100 shadow-sm overflow-hidden">
                    <div class="p-6 space-y-3 bg-white">
                        <div class="flex justify-between font-bold text-slate-400 text-[11px] tracking-wider uppercase">
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

                <p class="text-[11px] text-slate-400 italic leading-relaxed text-left px-1">
                    Ghi chú: Mọi vấn đề thắc mắc về thông tin ghi nhận trên bản sao kê chi tiết giao dịch, Quý đối tác
                    vui lòng liên hệ với bộ phận Chăm Sóc Khách Hàng của Drivio tại 1900 9217 để biết thêm chi tiết. Xin
                    cám ơn!
                </p>

            </div>
        </section>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
</script>

<style scoped>
table {
    border-spacing: 0;
}

th,
td {
    word-break: break-all;
}
</style>
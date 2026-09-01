<template>
    <div class="min-h-screen bg-slate-50/70 font-sans text-slate-800 pb-16 antialiased">
        <CommonLoadingOverlay :loading="isLoading" text="Đang tải dữ liệu..." />

        <!-- HEADER SECTION -->
        <section class="bg-gradient-to-r from-[#1e4e57] via-[#245f6a] to-[#286874] text-white relative shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-14 md:pt-24 md:pb-16">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <!-- Title & Subtitle -->
                    <div class="text-center md:text-left">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-cyan-100 text-xs font-semibold tracking-wide backdrop-blur-md mb-2 border border-white/10">
                            <Icon name="lucide:file-text" class="w-3.5 h-3.5" />
                            Báo cáo tài chính & Sao kê
                        </span>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white">
                            Sao kê chi tiết giao dịch
                        </h1>
                        <p class="text-xs sm:text-sm text-cyan-100/90 mt-1">
                            Kỳ sao kê từ {{ fromDate }} đến {{ toDate }}
                        </p>
                    </div>

                    <!-- Action Controls (Month Picker + Export Excel) -->
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <!-- Month selector -->
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/25 px-3.5 py-2 rounded-xl shadow-inner hover:bg-white/15 transition">
                            <Icon name="lucide:calendar" class="w-4 h-4 text-cyan-200" />
                            <span class="text-xs font-semibold text-cyan-100 uppercase tracking-wider hidden sm:inline">Tháng:</span>
                            <select
                                v-model="selectedMonthYear"
                                @change="onMonthChange"
                                class="bg-transparent text-white font-bold text-sm focus:outline-none cursor-pointer pr-2 border-none">
                                <option
                                    v-for="opt in monthOptions"
                                    :key="opt.value"
                                    :value="opt.value"
                                    class="text-slate-800 bg-white">
                                    {{ opt.label }}
                                </option>
                            </select>
                        </div>

                        <!-- Export Excel Button -->
                        <button
                            @click="exportToExcel"
                            :disabled="isExporting"
                            class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 text-white text-xs sm:text-sm font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-emerald-900/20 transition-all transform active:scale-95 border border-emerald-400/30 disabled:opacity-50 cursor-pointer">
                            <Icon v-if="!isExporting" name="lucide:file-spreadsheet" class="w-4 h-4" />
                            <Icon v-else name="lucide:loader-2" class="w-4 h-4 animate-spin" />
                            <span>{{ isExporting ? 'Đang xuất...' : 'Xuất Excel' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- TOP BAR & USER SUMMARY CARD -->
        <section class="mt-6 mb-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
                    <button
                        @click="goBack"
                        class="inline-flex items-center gap-1.5 text-slate-600 hover:text-slate-900 transition text-sm font-semibold focus:outline-none py-1 group">
                        <Icon name="lucide:chevron-left" class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" />
                        Quay lại
                    </button>

                    <!-- Khối thông tin đối tác/người dùng -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#1e4e57]/10 text-[#1e4e57] flex items-center justify-center font-bold">
                            <Icon :name="user?.role_id === 2 ? 'lucide:user' : 'lucide:car'" class="w-5 h-5" />
                        </div>
                        <div>
                            <div class="text-[11px] font-bold tracking-wide uppercase text-slate-400">
                                {{ user?.role_id === 2 ? 'Tài khoản Khách hàng' : 'Tài khoản Chủ xe' }}
                            </div>
                            <div class="text-sm font-bold text-slate-800">
                                {{ userName }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- MAIN TABLES SECTION -->
        <section class="py-4 space-y-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

                <!-- 1. CHUYẾN ĐI HOÀN THÀNH -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center gap-2">
                            <!-- <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> -->
                            <h2 class="text-base font-bold text-slate-900">
                                1. Chuyến đi hoàn thành trong tháng
                            </h2>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ completedTrips.length }} chuyến
                            </span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse min-w-[1350px]">
                                <thead>
                                    <!-- Categorized Grouping Header Row -->
                                    <tr class="bg-slate-100/90 text-slate-700 font-bold border-b border-slate-200 text-xs uppercase tracking-wider">
                                        <th colspan="3" class="py-3 px-3.5 border-r border-slate-200/80">Thông tin chung</th>
                                        <th colspan="3" class="py-3 px-3.5 border-r border-slate-200/80 text-center">Thời gian</th>
                                        <th colspan="1" class="py-3 px-3.5 border-r border-slate-200/80">Đối tác</th>
                                        <th colspan="7" class="py-3 px-3.5 text-right">Chi tiết Thanh toán & Số dư (VND)</th>
                                    </tr>
                                    <!-- Detailed Column Header Row -->
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-800 font-bold text-sm">
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 whitespace-nowrap">Mã chuyến</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 whitespace-nowrap">Biển số xe</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/80 whitespace-nowrap">Tên xe</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 text-center whitespace-nowrap">Ngày đi</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 text-center whitespace-nowrap">Ngày về</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/80 text-center whitespace-nowrap">Ngày đặt</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 whitespace-nowrap">
                                            {{ user?.role_id === 2 ? 'Chủ xe' : 'Khách hàng' }}
                                        </th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 text-right whitespace-nowrap">Đơn giá</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/80 text-right whitespace-nowrap">Thanh toán cọc</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 text-right whitespace-nowrap">Tiền thuê xe</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 text-right whitespace-nowrap">Giảm giá KM</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 text-right whitespace-nowrap">Giữ phạt nguội (2%)</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 text-right whitespace-nowrap">Thuế (25%)</th>
                                        <th class="py-3.5 px-3.5 text-right whitespace-nowrap">Thay đổi số dư</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm">
                                    <template v-if="completedTrips.length > 0">
                                        <tr v-for="item in completedTrips" :key="item.id" class="hover:bg-cyan-50/30 transition-colors bg-white">
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-mono font-bold text-[#1e4e57] text-sm whitespace-nowrap">
                                                {{ item.trip.trip_code || ('TRIP' + item.trip.id) }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-mono font-bold text-slate-800 text-sm whitespace-nowrap">
                                                <span class="bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200 text-slate-800">
                                                    {{ item.trip.car?.license_plate || 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-semibold text-slate-800 max-w-[180px] truncate">
                                                {{ item.trip.car?.name || 'N/A' }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 text-slate-700 font-mono text-center whitespace-nowrap">
                                                {{ item.trip.start_at }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 text-slate-700 font-mono text-center whitespace-nowrap">
                                                {{ item.trip.end_at }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 text-slate-600 font-mono text-center whitespace-nowrap">
                                                {{ item.trip.created_at }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-medium text-slate-800 whitespace-nowrap">
                                                {{ user?.role_id === 2 ? (item.trip.owner_name || 'N/A') : item.trip.customer_name }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-mono text-right text-slate-800 font-medium whitespace-nowrap">
                                                {{ formatCurrency(item.trip.car?.unit_price || 0) }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-mono text-right text-emerald-600 font-bold whitespace-nowrap">
                                                {{ formatCurrency(item.prepay) }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-mono text-right text-blue-600 font-bold whitespace-nowrap">
                                                {{ formatCurrency(item.trip.cost) }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-mono text-right text-rose-500 font-semibold whitespace-nowrap">
                                                {{ formatCurrency(item.trip.discount_amount) }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-mono text-right text-amber-600 font-semibold whitespace-nowrap">
                                                {{ formatCurrency(item.trip.penalty_deducted || (item.amount * 0.02)) }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-mono text-right text-orange-600 font-semibold whitespace-nowrap">
                                                {{ formatCurrency(item.trip.tax_deducted || (item.amount * 0.25)) }}
                                            </td>
                                            <td class="py-3.5 px-3.5 font-mono font-black text-right text-emerald-600 text-base whitespace-nowrap">
                                                +{{ formatCurrency(item.amount) }}
                                            </td>
                                        </tr>
                                    </template>
                                    <tr v-else>
                                        <td colspan="14" class="py-10 text-slate-400 text-center font-medium bg-slate-50/30">
                                            Không có chuyến đi hoàn thành trong tháng này.
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-slate-100/90 font-bold border-t-2 border-slate-200 text-sm">
                                        <td colspan="13" class="text-right py-4 px-4 text-slate-800 uppercase tracking-wider">
                                            Tổng thay đổi - Chuyến đi hoàn thành:
                                        </td>
                                        <td class="py-4 px-3.5 font-black text-emerald-700 text-base text-right font-mono whitespace-nowrap">
                                            +{{ formatCurrency(summary.completed_trips_change) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 2. RÚT NỘP TIỀN -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center gap-2">
                            <!-- <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> -->
                            <h2 class="text-base font-bold text-slate-900">
                                2. Giao dịch rút / nộp tiền trong tháng
                            </h2>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                {{ depositWithdrawals.length }} giao dịch
                            </span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden max-w-5xl">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead>
                                    <tr class="bg-slate-100/90 border-b border-slate-200 text-slate-800 font-bold text-sm uppercase tracking-wider">
                                        <th class="py-3.5 px-4 w-44 whitespace-nowrap">Ngày giao dịch</th>
                                        <th class="py-3.5 px-4 w-36 text-center whitespace-nowrap">Loại giao dịch</th>
                                        <th class="py-3.5 px-4">Nội dung diễn giải</th>
                                        <th class="py-3.5 px-4 w-36 text-center whitespace-nowrap">Trạng thái</th>
                                        <th class="py-3.5 px-4 text-right w-48 whitespace-nowrap">Thay đổi số dư</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm">
                                    <template v-if="depositWithdrawals.length > 0">
                                        <tr v-for="item in depositWithdrawals" :key="item.id" class="hover:bg-slate-50 transition-colors bg-white">
                                            <td class="py-4 px-4 font-mono text-slate-700 font-medium whitespace-nowrap">
                                                {{ item.created_at }}
                                            </td>
                                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                                <span
                                                    :class="item.amount > 0 ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-rose-100 text-rose-800 border-rose-200'"
                                                    class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 rounded-full border">
                                                    <Icon :name="item.amount > 0 ? 'lucide:arrow-down-left' : 'lucide:arrow-up-right'" class="w-3.5 h-3.5" />
                                                    {{ item.amount > 0 ? 'Nạp tiền' : 'Rút tiền' }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 font-semibold text-slate-800">
                                                {{ item.description || ('Giao dịch ' + (item.amount > 0 ? 'Nạp tiền vào ví' : 'Rút tiền từ ví')) }}
                                            </td>
                                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                                <span
                                                    :class="getStatusConfig(item).class"
                                                    class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 rounded-full border">
                                                    <Icon :name="getStatusConfig(item).icon" class="w-3.5 h-3.5" :class="{ 'animate-spin': Number(item.status) === 1 }" />
                                                    {{ getStatusConfig(item).label }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 font-mono font-black text-right text-base whitespace-nowrap" :class="item.amount > 0 ? 'text-emerald-600' : 'text-rose-600'">
                                                {{ item.amount > 0 ? '+' : '' }}{{ formatCurrency(item.amount) }}
                                            </td>
                                        </tr>
                                    </template>
                                    <tr v-else>
                                        <td colspan="5" class="py-8 text-slate-400 text-center font-medium bg-slate-50/30">
                                            Không có giao dịch rút hoặc nộp tiền trong tháng này.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 3. HỦY CHUYẾN -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center gap-2">
                            <!-- <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> -->
                            <h2 class="text-base font-bold text-slate-900">
                                3. Giao dịch hủy chuyến trong tháng
                            </h2>
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                {{ cancelledTrips.length }} chuyến
                            </span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-collapse min-w-[1200px]">
                                <thead>
                                    <tr class="bg-slate-100/90 text-slate-700 font-bold border-b border-slate-200 text-xs uppercase tracking-wider">
                                        <th colspan="2" class="py-3 px-3.5 border-r border-slate-200/80">Thông tin xe</th>
                                        <th colspan="3" class="py-3 px-3.5 border-r border-slate-200/80 text-center">Thời gian</th>
                                        <th colspan="1" class="py-3 px-3.5 border-r border-slate-200/80">Đối tác</th>
                                        <th colspan="3" class="py-3 px-3.5 border-r border-slate-200/80 text-right">Chi tiết chi phí</th>
                                        <th colspan="2" class="py-3 px-3.5 text-right">Kết quả hủy chuyến</th>
                                    </tr>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-800 font-bold text-sm">
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 whitespace-nowrap">Biển số xe</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/80 whitespace-nowrap">Tên xe</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 text-center whitespace-nowrap">Ngày đi</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 text-center whitespace-nowrap">Ngày về</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/80 text-center whitespace-nowrap">Ngày hủy</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 whitespace-nowrap">
                                            {{ user?.role_id === 2 ? 'Chủ xe' : 'Khách hàng' }}
                                        </th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/80 text-right whitespace-nowrap">Đơn giá</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/60 text-right whitespace-nowrap">Thanh toán cọc</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/80 text-right whitespace-nowrap">Thanh toán chủ xe</th>
                                        <th class="py-3.5 px-3.5 border-r border-slate-200/80 whitespace-nowrap">Lý do / Nội dung hủy</th>
                                        <th class="py-3.5 px-3.5 text-right whitespace-nowrap">Thay đổi số dư</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm">
                                    <template v-if="cancelledTrips.length > 0">
                                        <tr v-for="item in cancelledTrips" :key="item.id" class="hover:bg-rose-50/20 transition-colors bg-white">
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-mono font-bold text-slate-800 text-sm whitespace-nowrap">
                                                <span class="bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200 text-slate-800">
                                                    {{ item.trip.car?.license_plate || 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-semibold text-slate-800 max-w-[180px] truncate">
                                                {{ item.trip.car?.name || 'N/A' }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 text-slate-700 font-mono text-center whitespace-nowrap">
                                                {{ item.trip.start_at }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 text-slate-700 font-mono text-center whitespace-nowrap">
                                                {{ item.trip.end_at }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 text-slate-600 font-mono text-center whitespace-nowrap">
                                                {{ item.trip?.updated_at || item.created_at }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-medium text-slate-800 whitespace-nowrap">
                                                {{ user?.role_id === 2 ? (item.trip.owner_name || 'N/A') : item.trip.customer_name }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-mono text-right text-slate-800 font-medium whitespace-nowrap">
                                                {{ formatCurrency(item.trip.car?.unit_price || 0) }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-mono text-right text-slate-500 whitespace-nowrap">
                                                {{ formatCurrency(item.prepay) }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-mono text-right text-slate-500 whitespace-nowrap">
                                                {{ formatCurrency(item.trip.cost) }}
                                            </td>
                                            <td class="py-3.5 px-3.5 border-r border-slate-100 font-semibold text-rose-600 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 px-2.5 py-1 rounded-md border border-rose-200 text-xs font-bold">
                                                    {{ item.trip?.cancel_by_name || (Number(item.trip?.status) === TripStatus.UserCancel ? 'Người thuê hủy' : 'Chủ xe hủy') }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-3.5 font-mono font-black text-right text-rose-600 text-base whitespace-nowrap">
                                                {{ formatCurrency(item.amount) }}
                                            </td>
                                        </tr>
                                    </template>
                                    <tr v-else>
                                        <td colspan="11" class="py-8 text-slate-400 text-center font-medium bg-slate-50/30">
                                            Không có giao dịch hủy chuyến trong tháng này.
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-slate-100/90 font-bold border-t-2 border-slate-200 text-sm">
                                        <td colspan="10" class="text-right py-4 px-4 text-slate-800 uppercase tracking-wider">
                                            Tổng thay đổi - Giao dịch hủy chuyến:
                                        </td>
                                        <td class="py-4 px-3.5 font-black text-rose-600 text-base text-right font-mono whitespace-nowrap">
                                            {{ formatCurrency(summary.cancelled_trips_change) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- BẢNG TỔNG HỢP TIỀN & THU NHẬP -->
                <div v-if="user?.role_id !== 2" class="bg-white rounded-2xl border border-slate-200/80 shadow-md overflow-hidden max-w-2xl">
                    <div class="bg-gradient-to-r from-[#1e4e57] to-[#286874] px-6 py-4 text-white flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <Icon name="lucide:calculator" class="w-5 h-5 text-cyan-200" />
                            <h3 class="font-bold text-base">Tổng hợp doanh thu & Thu nhập chủ xe</h3>
                        </div>
                        <span class="text-xs text-cyan-100 bg-white/10 px-2.5 py-1 rounded-full border border-white/20">
                            Tháng {{ selectedMonth }}/{{ selectedYear }}
                        </span>
                    </div>

                    <div class="p-6 space-y-4 text-sm divide-y divide-slate-100">
                        <div class="flex justify-between items-center font-bold text-slate-800">
                            <span class="text-slate-600">Tổng doanh thu chuyến đi trong tháng:</span>
                            <span class="font-mono text-base text-[#1e4e57]">{{ formatCurrency(summary.completed_trips_change) }}</span>
                        </div>

                        <div class="flex justify-between items-center pt-3 font-semibold text-rose-600">
                            <div class="flex items-center gap-1.5">
                                <span>Thuế kinh doanh đã khấu trừ</span>
                                <span class="bg-rose-50 text-rose-700 text-[11px] px-2 py-0.5 rounded-full border border-rose-200">
                                    {{ summary.tax_rate || 25 }}%
                                </span>
                            </div>
                            <span class="font-mono">-{{ formatCurrency(summary.tax_deducted) }}</span>
                        </div>

                        <div class="flex justify-between items-center pt-3 font-semibold text-amber-600">
                            <div class="flex items-center gap-1.5">
                                <span>Tiền giữ phạt nguội</span>
                                <span class="bg-amber-50 text-amber-700 text-[11px] px-2 py-0.5 rounded-full border border-amber-200">
                                    {{ summary.penalty_rate || 2 }}%
                                </span>
                            </div>
                            <span class="font-mono">-{{ formatCurrency(summary.penalty_deducted || 0) }}</span>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t-2 border-slate-200 bg-emerald-50/60 p-4 rounded-xl">
                            <div class="flex items-center gap-2">
                                <Icon name="lucide:wallet" class="w-5 h-5 text-emerald-600" />
                                <span class="font-black text-slate-900 uppercase tracking-wide">THU NHẬP THỰC NHẬN CHỦ XE:</span>
                            </div>
                            <span class="font-mono font-black text-xl text-emerald-600">
                                {{ formatCurrency(summary.owner_income) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- FOOTER NOTE -->
                <div class="bg-amber-50/60 border border-amber-200/80 rounded-xl p-4 flex items-start gap-3 text-xs text-amber-900">
                    <Icon name="lucide:info" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                    <p class="leading-relaxed">
                        <strong>Ghi chú:</strong> Mọi thắc mắc về thông tin ghi nhận trên bản sao kê chi tiết giao dịch, Quý đối tác vui lòng liên hệ Bộ phận Chăm sóc Khách hàng của Drivio qua đường dây nóng <strong>1900 9217</strong> hoặc email hỗ trợ để được giải đáp kịp thời.
                    </p>
                </div>

            </div>
        </section>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { walletService } from '~/services/wallet.service'
import { useRouter } from 'vue-router'
import { TripStatus } from '~/config/trip-status'

// 1. Khai báo các biến lưu trữ dữ liệu (reactive state)
const { user } = useAuth()
const router = useRouter()

const transactions = ref<any[]>([])
const refunds = ref<any[]>([])
const userName = ref('')
const userCode = ref('')
const balance = ref(0)
const isLoading = ref(true)
const isExporting = ref(false)

const summary = ref({
    completed_trips_change: 0,
    deposit_withdrawal_change: 0,
    cancelled_trips_change: 0,
    total_change: 0,
    start_balance: 0,
    end_balance: 0,
    tax_rate: 25,
    penalty_rate: 2,
    tax_deducted: 0,
    penalty_deducted: 0,
    owner_income: 0
})

const now = new Date()
const selectedMonthYear = ref(`${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`)

const monthOptions = computed(() => {
    const options = []
    const currentDate = new Date()
    for (let i = 0; i < 12; i++) {
        const d = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1)
        const m = String(d.getMonth() + 1).padStart(2, '0')
        const y = d.getFullYear()
        options.push({
            label: `Tháng ${m}/${y}`,
            value: `${y}-${m}`,
            month: d.getMonth() + 1,
            year: y
        })
    }
    return options
})

const selectedMonth = computed(() => {
    const parts = selectedMonthYear.value.split('-')
    return parseInt(parts[1] || '0', 10)
})

const selectedYear = computed(() => {
    const parts = selectedMonthYear.value.split('-')
    return parseInt(parts[0] || '0', 10)
})

// 2. Tự động tính Từ ngày (ngày 01) và Đến ngày (ngày cuối cùng) của tháng được chọn
const fromDate = computed(() => {
    const day = '01'
    const month = String(selectedMonth.value).padStart(2, '0')
    const year = selectedYear.value
    return `${day}/${month}/${year}`
})

const toDate = computed(() => {
    const lastDayObj = new Date(selectedYear.value, selectedMonth.value, 0)
    const day = String(lastDayObj.getDate()).padStart(2, '0')
    const month = String(selectedMonth.value).padStart(2, '0')
    const year = selectedYear.value
    return `${day}/${month}/${year}`
})

const isSameMonthYear = (dateStr?: string | null): boolean => {
    if (!dateStr || typeof dateStr !== 'string') return false
    const trimmed = dateStr.trim()
    if (!trimmed) return false

    const parts = trimmed.split(' ')
    const cleanDate = parts[0] || ''
    if (!cleanDate) return false

    let m = 0, y = 0
    if (cleanDate.includes('/')) {
        const dateParts = cleanDate.split('/')
        if (dateParts.length === 3) {
            m = parseInt(dateParts[1] || '0', 10)
            y = parseInt(dateParts[2] || '0', 10)
        }
    } else if (cleanDate.includes('-')) {
        const dateParts = cleanDate.split('-')
        if (dateParts.length === 3) {
            y = parseInt(dateParts[0] || '0', 10)
            m = parseInt(dateParts[1] || '0', 10)
        }
    }
    return m === selectedMonth.value && y === selectedYear.value
}

// 3. Phân loại danh sách giao dịch theo Tháng/Năm được chọn (Computed Properties)
const completedTrips = computed(() => {
    return transactions.value.filter(t => {
        if (!t.trip || Number(t.trip.status) !== TripStatus.Complete) return false
        const dateToCheck = t.trip.end_at || t.trip.updated_at || t.trip.start_at || t.created_at
        return isSameMonthYear(dateToCheck)
    })
})

const depositWithdrawals = computed(() => {
    const list: any[] = []
    
    if (refunds.value && refunds.value.length > 0) {
        const filteredRefunds = refunds.value.filter(r => isSameMonthYear(r.created_at))
        list.push(...filteredRefunds)
    }
    
    if (transactions.value && transactions.value.length > 0) {
        const filteredTxns = transactions.value.filter(t => {
            if (t.trip) return false
            if (!isSameMonthYear(t.created_at)) return false
            if (t.transaction_code && t.transaction_code.startsWith('WD')) return false
            return true
        })
        list.push(...filteredTxns)
    }
    
    const getTimestamp = (dateStr?: string | null): number => {
        if (!dateStr || typeof dateStr !== 'string') return 0
        const parts = dateStr.trim().split(' ')
        const cleanDate = parts[0] || ''
        if (!cleanDate) return 0
        const dateParts = cleanDate.split('/')
        if (dateParts.length === 3) {
            const d = dateParts[0]
            const m = dateParts[1]
            const y = dateParts[2]
            const timePart = parts[1] || '00:00'
            return new Date(`${y}-${m}-${d}T${timePart}`).getTime()
        }
        return new Date(dateStr).getTime() || 0
    }
    
    return list.sort((a, b) => getTimestamp(b.created_at) - getTimestamp(a.created_at))
})

const cancelledTrips = computed(() => {
    return transactions.value.filter(t => {
        if (!t.trip) return false
        const isCancelled = Number(t.trip.status) === TripStatus.UserCancel || Number(t.trip.status) === TripStatus.OwnerCancel
        if (!isCancelled) return false
        const dateToCheck = t.trip.end_at || t.trip.updated_at || t.trip.start_at || t.created_at
        return isSameMonthYear(dateToCheck)
    })
})

// 3. Hàm trợ giúp định dạng tiền tệ & trạng thái
const formatCurrency = (amount: number = 0) => {
    return new Intl.NumberFormat('vi-VN').format(amount) + 'đ'
}

const getStatusConfig = (item: any) => {
    if (item.status !== undefined && item.status !== null) {
        const s = Number(item.status)
        switch (s) {
            case 0:
                return {
                    label: item.status_label || 'Chờ xử lý',
                    class: 'bg-amber-50 text-amber-700 border-amber-200',
                    icon: 'lucide:clock'
                }
            case 1:
                return {
                    label: item.status_label || 'Đang xử lý',
                    class: 'bg-blue-50 text-blue-700 border-blue-200',
                    icon: 'lucide:loader-2'
                }
            case 2:
                return {
                    label: item.status_label || 'Hoàn thành',
                    class: 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    icon: 'lucide:check-circle-2'
                }
            case 3:
                return {
                    label: item.status_label || 'Thất bại',
                    class: 'bg-rose-50 text-rose-700 border-rose-200',
                    icon: 'lucide:x-circle'
                }
            case 4:
                return {
                    label: item.status_label || 'Đã hủy',
                    class: 'bg-slate-100 text-slate-600 border-slate-200',
                    icon: 'lucide:ban'
                }
            default:
                return {
                    label: item.status_label || 'Chờ xử lý',
                    class: 'bg-slate-50 text-slate-700 border-slate-200',
                    icon: 'lucide:help-circle'
                }
        }
    }
    return {
        label: 'Hoàn thành',
        class: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        icon: 'lucide:check-circle-2'
    }
}

// 4. Hàm bất đồng bộ (async) gọi API lấy dữ liệu từ Service theo Tháng/Năm được chọn
const fetchReportData = async () => {
    isLoading.value = true
    try {
        if (user.value) {
            userName.value = user.value.name || 'Khách hàng'
            userCode.value = user.value.national_number || ('DRV' + String(user.value.id || '001').padStart(3, '0'))
        }

        // Gọi API lấy thông tin Ví & Sao kê giao dịch theo month & year
        const walletRes = await walletService.getWalletDetails({
            month: selectedMonth.value,
            year: selectedYear.value
        })

        if (walletRes && walletRes.success && walletRes.data) {
            const rawTxns = walletRes.data.transactions
            transactions.value = Array.isArray(rawTxns) ? rawTxns : Object.values(rawTxns || {})

            const rawRefunds = walletRes.data.refunds
            refunds.value = Array.isArray(rawRefunds) ? rawRefunds : Object.values(rawRefunds || {})

            balance.value = walletRes.data.balance || 0
            if (walletRes.data.summary) {
                summary.value = { ...summary.value, ...walletRes.data.summary }
            }
        }
    } catch (error) {
        console.error('Lỗi khi tải dữ liệu sao kê báo cáo tháng:', error)
    } finally {
        isLoading.value = false
    }
}

const onMonthChange = () => {
    fetchReportData()
}

// 5. Hàm xuất dữ liệu ra Excel (.xlsx)
const exportToExcel = async () => {
    isExporting.value = true
    try {
        let XLSXModule: any = null
        try {
            XLSXModule = await import('xlsx')
        } catch (e) {
            console.warn('XLSX module dynamic import failed, falling back to Excel spreadsheet export', e)
        }

        const monthStr = String(selectedMonth.value).padStart(2, '0')
        const fileName = `Sao_Ke_Chi_Tiet_Thang_${monthStr}_${selectedYear.value}.xlsx`

        if (XLSXModule && XLSXModule.utils) {
            const wb = XLSXModule.utils.book_new()

            // 1. Sheet Chuyến đi hoàn thành
            const completedData = completedTrips.value.map((item: any, idx: number) => ({
                'STT': idx + 1,
                'Mã chuyến đi': item.trip.trip_code || ('TRIP' + item.trip.id),
                'Biển số xe': item.trip.car?.license_plate || 'N/A',
                'Tên xe': item.trip.car?.name || 'N/A',
                'Ngày đi': item.trip.start_at,
                'Ngày về': item.trip.end_at,
                'Ngày đặt xe': item.trip.created_at,
                [user.value?.role_id === 2 ? 'Chủ xe' : 'Khách hàng']: user.value?.role_id === 2 ? (item.trip.owner_name || 'N/A') : item.trip.customer_name,
                'Đơn giá (VNĐ)': item.trip.car?.unit_price || 0,
                'Thanh toán cọc (VNĐ)': item.prepay || 0,
                'Thanh toán chủ xe (VNĐ)': item.trip.cost || 0,
                'Giảm giá KM (VNĐ)': item.trip.discount_amount || 0,
                'Giữ phạt nguội 2% (VNĐ)': item.trip.penalty_deducted || (item.amount * 0.02),
                'Thuế 25% (VNĐ)': item.trip.tax_deducted || (item.amount * 0.25),
                'Thay đổi số dư (VNĐ)': item.amount || 0
            }))
            const wsCompleted = XLSXModule.utils.json_to_sheet(completedData.length > 0 ? completedData : [{ 'Thông báo': 'Không có dữ liệu chuyến đi hoàn thành' }])
            XLSXModule.utils.book_append_sheet(wb, wsCompleted, 'Chuyến đi hoàn thành')

            // 2. Sheet Giao dịch nộp/rút tiền
            const depositData = depositWithdrawals.value.map((item: any, idx: number) => ({
                'STT': idx + 1,
                'Ngày giao dịch': item.created_at,
                'Loại giao dịch': item.amount > 0 ? 'Nạp tiền' : 'Rút tiền',
                'Nội dung diễn giải': item.description || ('Giao dịch ' + (item.amount > 0 ? 'Nạp tiền' : 'Rút tiền')),
                'Trạng thái': getStatusConfig(item).label,
                'Thay đổi số dư (VNĐ)': item.amount || 0
            }))
            const wsDeposit = XLSXModule.utils.json_to_sheet(depositData.length > 0 ? depositData : [{ 'Thông báo': 'Không có dữ liệu rút/nộp tiền' }])
            XLSXModule.utils.book_append_sheet(wb, wsDeposit, 'Giao dịch Nạp-Rút')

            // 3. Sheet Chuyến đi hủy
            const cancelledData = cancelledTrips.value.map((item: any, idx: number) => ({
                'STT': idx + 1,
                'Biển số xe': item.trip.car?.license_plate || 'N/A',
                'Tên xe': item.trip.car?.name || 'N/A',
                'Ngày đi': item.trip.start_at,
                'Ngày về': item.trip.end_at,
                'Ngày hủy': item.trip?.updated_at || item.created_at,
                [user.value?.role_id === 2 ? 'Chủ xe' : 'Khách hàng']: user.value?.role_id === 2 ? (item.trip.owner_name || 'N/A') : item.trip.customer_name,
                'Đơn giá (VNĐ)': item.trip.car?.unit_price || 0,
                'Cọc giữ chỗ (VNĐ)': item.prepay || 0,
                'Lý do / Nội dung hủy': item.trip?.cancel_by_name || (Number(item.trip?.status) === TripStatus.UserCancel ? 'Người thuê hủy' : 'Chủ xe hủy'),
                'Thay đổi số dư (VNĐ)': item.amount || 0
            }))
            const wsCancelled = XLSXModule.utils.json_to_sheet(cancelledData.length > 0 ? cancelledData : [{ 'Thông báo': 'Không có dữ liệu chuyến đi hủy' }])
            XLSXModule.utils.book_append_sheet(wb, wsCancelled, 'Chuyến đi hủy')

            // 4. Sheet Tổng hợp
            const summaryData = [
                { 'Chỉ tiêu': 'Họ và tên người dùng', 'Giá trị': userName.value },
                { 'Chỉ tiêu': 'Kỳ sao kê', 'Giá trị': `Tháng ${selectedMonth.value}/${selectedYear.value}` },
                { 'Chỉ tiêu': 'Tổng tiền chuyến đi hoàn thành', 'Giá trị': summary.value.completed_trips_change },
                { 'Chỉ tiêu': `Thuế kinh doanh (${summary.value.tax_rate || 25}%)`, 'Giá trị': summary.value.tax_deducted },
                { 'Chỉ tiêu': `Tiền giữ phạt nguội (${summary.value.penalty_rate || 2}%)`, 'Giá trị': summary.value.penalty_deducted },
                { 'Chỉ tiêu': 'THU NHẬP THỰC NHẬN CHỦ XE', 'Giá trị': summary.value.owner_income }
            ]
            const wsSummary = XLSXModule.utils.json_to_sheet(summaryData)
            XLSXModule.utils.book_append_sheet(wb, wsSummary, 'Tổng kết tài chính')

            XLSXModule.writeFile(wb, fileName)
        } else {
            // Fallback HTML Excel table with UTF-8 BOM
            let html = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head><meta charset="utf-8"><style>table { border-collapse: collapse; width: 100%; } td, th { border: 1px solid #ccc; padding: 6px 10px; text-align: left; } th { background-color: #f1f5f9; }</style></head>
            <body>
            <h2>SAO KÊ CHI TIẾT GIAO DỊCH THÁNG ${selectedMonth.value}/${selectedYear.value}</h2>
            <p><strong>Khách hàng/Chủ xe:</strong> ${userName.value} (${userCode.value})</p>

            <h3>1. Chuyến đi hoàn thành</h3>
            <table>
                <tr>
                    <th>STT</th><th>Mã chuyến</th><th>Biển số xe</th><th>Tên xe</th><th>Ngày đi</th><th>Ngày về</th>
                    <th>Ngày đặt</th><th>Đối tác</th><th>Đơn giá</th><th>Cọc</th><th>Thanh toán xe</th><th>Thay đổi số dư</th>
                </tr>`
            completedTrips.value.forEach((item: any, idx: number) => {
                html += `<tr>
                    <td>${idx + 1}</td>
                    <td>${item.trip.trip_code || ('TRIP' + item.trip.id)}</td>
                    <td>${item.trip.car?.license_plate || 'N/A'}</td>
                    <td>${item.trip.car?.name || 'N/A'}</td>
                    <td>${item.trip.start_at}</td>
                    <td>${item.trip.end_at}</td>
                    <td>${item.trip.created_at}</td>
                    <td>${user.value?.role_id === 2 ? (item.trip.owner_name || 'N/A') : item.trip.customer_name}</td>
                    <td>${item.trip.car?.unit_price || 0}</td>
                    <td>${item.prepay || 0}</td>
                    <td>${item.trip.cost || 0}</td>
                    <td>${item.amount || 0}</td>
                </tr>`
            })
            html += `</table>

            <h3>2. Giao dịch rút/nộp tiền</h3>
            <table>
                <tr><th>STT</th><th>Ngày giao dịch</th><th>Loại</th><th>Nội dung</th><th>Thay đổi số dư</th></tr>`
            depositWithdrawals.value.forEach((item: any, idx: number) => {
                html += `<tr>
                    <td>${idx + 1}</td>
                    <td>${item.created_at}</td>
                    <td>${item.amount > 0 ? 'Nạp tiền' : 'Rút tiền'}</td>
                    <td>${item.description || ''}</td>
                    <td>${item.amount || 0}</td>
                </tr>`
            })
            html += `</table>

            <h3>3. Giao dịch hủy chuyến</h3>
            <table>
                <tr><th>STT</th><th>Biển số xe</th><th>Tên xe</th><th>Ngày đi</th><th>Ngày về</th><th>Ngày hủy</th><th>Đối tác</th><th>Nội dung hủy</th><th>Thay đổi số dư</th></tr>`
            cancelledTrips.value.forEach((item: any, idx: number) => {
                html += `<tr>
                    <td>${idx + 1}</td>
                    <td>${item.trip.car?.license_plate || 'N/A'}</td>
                    <td>${item.trip.car?.name || 'N/A'}</td>
                    <td>${item.trip.start_at}</td>
                    <td>${item.trip.end_at}</td>
                    <td>${item.trip?.updated_at || item.created_at}</td>
                    <td>${user.value?.role_id === 2 ? (item.trip.owner_name || 'N/A') : item.trip.customer_name}</td>
                    <td>${item.trip?.cancel_by_name || ''}</td>
                    <td>${item.amount || 0}</td>
                </tr>`
            })
            html += `</table>
            </body></html>`

            const blob = new Blob(['\ufeff' + html], { type: 'application/vnd.ms-excel;charset=utf-8' })
            const url = URL.createObjectURL(blob)
            const a = document.createElement('a')
            a.href = url
            a.download = fileName.replace('.xlsx', '.xls')
            document.body.appendChild(a)
            a.click()
            document.body.removeChild(a)
            URL.revokeObjectURL(url)
        }
    } catch (err) {
        console.error('Lỗi xuất file Excel:', err)
        alert('Có lỗi xảy ra khi xuất file Excel. Vui lòng thử lại!')
    } finally {
        isExporting.value = false
    }
}

// 6. Tự động gọi API khi component được Mounted vào DOM
onMounted(() => {
    fetchReportData()
})

const goBack = () => {
    router.back()
}
</script>

<style scoped>
table {
    border-spacing: 0;
}

th,
td {
    word-break: normal;
}
</style>
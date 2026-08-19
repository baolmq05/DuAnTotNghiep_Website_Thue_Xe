<template>
    <div class="min-h-screen bg-[#f8f9fa] font-sans text-[#333333] pb-24 antialiased">
        <CommonLoadingOverlay :loading="isLoading" text="Đang tải dữ liệu..." />

        <div class="bg-[#286874] pt-24 pb-12 text-center">
            <h2 class="text-[32px] font-bold text-white tracking-tight">
                Ví của tôi
            </h2>
        </div>

        <div class="max-w-4xl mx-auto px-4 -mt-6 relative z-10">
            <div class="bg-white rounded-xl border border-slate-200 p-6 relative shadow-sm text-center">
                <button
                    @click="goBack"
                    class="absolute left-6 top-6 flex items-center gap-1 text-slate-500 hover:text-black transition text-sm font-medium focus:outline-none">
                    <Icon name="lucide:chevron-left" class="w-4 h-4" />
                    Quay lại
                </button>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 pt-6 sm:pt-2 pb-2">
                    <div class="py-2">
                        <p class="text-slate-500 text-[13px] font-medium">
                            Số dư ví (Khả dụng)
                        </p>
                        <p class="text-3xl sm:text-4xl font-bold text-[#286874] mt-1.5 tracking-tight">
                            {{ formatCurrency(balance) }}
                        </p>
                    </div>
                    <div class="pt-4 sm:pt-2">
                        <p class="text-slate-500 text-[13px] font-medium flex items-center justify-center gap-1">
                            <span>Tiền tạm giữ (Phạt nguội)</span>
                        </p>
                        <p class="text-3xl sm:text-4xl font-bold text-amber-600 mt-1.5 tracking-tight">
                            {{ formatCurrency(holdBalance) }}
                        </p>
                        <button
                            @click="openWithdrawHoldModal"
                            class="mt-2 text-xs font-bold text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100/80 px-3 py-1.5 rounded-full transition-colors inline-flex items-center gap-1">
                            <Icon name="lucide:arrow-down-left" class="w-3.5 h-3.5" />
                            Rút về ví khả dụng
                        </button>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mt-6 shadow-sm">
                <div class="bg-[#111111] text-white px-5 py-3.5">
                    <h3 class="font-bold text-xs tracking-wider uppercase">
                        Bảng tổng hợp giao dịch
                    </h3>
                </div>

                <!-- <div v-if="user?.role_id !== 2" class="flex w-full text-center bg-white border-b border-slate-200 divide-x divide-slate-100">
                    <div class="flex-1 py-3.5 flex flex-col justify-center items-center">
                        <p
                            class="text-base font-bold text-slate-800 flex items-center justify-center gap-0.5 leading-none">
                            <span class="text-amber-400 text-sm">★</span> {{ rating.toFixed(1) }}
                        </p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Đánh giá</p>
                    </div>

                    <div class="flex-1 py-3.5 flex flex-col justify-center items-center">
                        <p class="text-base font-bold text-slate-800 leading-none">{{ completedTripsCount }}</p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Chuyến đi thành công</p>
                    </div>

                    <div class="flex-1 py-3.5 bg-white flex flex-col justify-center items-center">
                        <p class="text-base font-bold text-slate-800 leading-none">{{ responseRate }}%</p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Tỉ lệ phản hồi</p>
                    </div>

                    <div class="flex-1 py-3.5 bg-white flex flex-col justify-center items-center">
                        <p class="text-base font-bold text-slate-800 leading-none">{{ responseTime }}</p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Phản hồi trong</p>
                    </div>

                    <div class="flex-1 py-3.5 bg-white flex flex-col justify-center items-center">
                        <p class="text-base font-bold text-slate-800 leading-none">{{ acceptRate }}%</p>
                        <p class="text-[11px] text-slate-400 mt-1.5 leading-none">Tỉ lệ đồng ý</p>
                    </div>
                </div> -->

                <div class="bg-white text-xs font-medium text-slate-700 divide-y divide-slate-100">
                    <!-- <div class="flex justify-between px-5 py-3 bg-[#fafafa]">
                        <span class="text-slate-500">Tổng thay đổi - Chuyến đi hoàn thành</span>
                        <span class="font-semibold text-slate-800">{{ formatCurrency(summary.completed_trips_change) }}</span>
                    </div>
                    <div class="flex justify-between px-5 py-3 bg-[#fafafa]">
                        <span class="text-slate-500">Tổng thay đổi - Giao dịch rút/nộp tiền</span>
                        <span class="font-semibold text-slate-800">{{ formatCurrency(summary.deposit_withdrawal_change) }}</span>
                    </div>
                    <div class="flex justify-between px-5 py-3 bg-[#fafafa]">
                        <span class="text-slate-500">Tổng thay đổi - Giao dịch hủy chuyến</span>
                        <span class="font-semibold text-slate-800">{{ formatCurrency(summary.cancelled_trips_change) }}</span>
                    </div> -->

                    <div class="p-5 space-y-3 bg-white">
                        <template v-if="user?.role_id !== 2">
                            <div class="flex justify-between font-bold text-slate-800">
                                <span>TỔNG TIỀN CHUYẾN ĐI TRONG THÁNG</span>
                                <span class="text-[#286874]">{{ formatCurrency(summary.completed_trips_change) }}</span>
                            </div>
                            <div class="flex justify-between font-semibold text-[#e05638]">
                                <span>THUẾ KINH DOANH ĐÃ KHẤU TRỪ ({{ summary.tax_rate || 25 }}%)</span>
                                <span>({{ formatCurrency(summary.tax_deducted) }})</span>
                            </div>
                            <div class="flex justify-between font-semibold text-amber-600">
                                <span>TIỀN GIỮ PHẠT NGUỘI ({{ summary.penalty_rate || 2 }}%)</span>
                                <span>({{ formatCurrency(summary.penalty_deducted || 0) }})</span>
                            </div>
                            <div class="flex justify-between font-bold text-[#2f80ed] pt-2 border-t border-slate-100">
                                <span>THU NHẬP CHỦ XE</span>
                                <span>{{ formatCurrency(summary.owner_income) }}</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 mt-6">
                <button
                    @click="navigateToStatement"
                    class="flex-1 h-12 rounded-lg border border-[#286874] text-[#286874] font-bold text-sm hover:bg-[#286874]/5 transition-colors text-center focus:outline-none shadow-sm flex items-center justify-center gap-1.5">
                    <Icon name="lucide:file-text" class="w-4 h-4" />
                    Xem Sao kê chi tiết giao dịch
                </button>
                <button
                    @click="openWithdrawModal"
                    class="flex-1 h-12 rounded-lg bg-[#286874] text-white font-bold text-sm hover:bg-[#1d4f59] transition-colors focus:outline-none shadow-sm flex items-center justify-center gap-1.5">
                    <Icon name="lucide:hand-coins" class="w-4 h-4" />
                    Yêu cầu rút tiền
                </button>
            </div>

        </div>
    </div>

    <!-- Modal Yêu cầu rút tiền -->
    <div v-if="isWithdrawModalOpen" class="fixed inset-0 z-[999] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeWithdrawModal"></div>

        <!-- Modal Content -->
        <div class="relative bg-white w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl z-10 p-8 border border-slate-100 flex flex-col animate-scale-in">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-black text-[#286874]">Yêu cầu rút tiền</h3>
                    <p class="text-xs text-slate-500 mt-1">Yêu cầu rút tiền từ số dư ví của bạn về tài khoản ngân hàng.</p>
                </div>
                <button @click="closeWithdrawModal" class="h-8 w-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-800 transition flex items-center justify-center focus:outline-none">
                    <Icon name="ic:outline-close" size="20" />
                </button>
            </div>

            <!-- Cảnh báo chưa liên kết ngân hàng -->
            <div v-if="!user?.bank_name" class="bg-amber-50 border border-amber-200 rounded-2xl p-5 text-center mb-6">
                <Icon name="lucide:alert-circle" size="44" class="text-amber-500 mx-auto mb-3" />
                <p class="text-sm font-bold text-slate-800">Chưa liên kết tài khoản ngân hàng</p>
                <p class="text-xs text-slate-500 mt-1.5 mb-4">Bạn cần liên kết tài khoản ngân hàng thụ hưởng trước khi thực hiện rút tiền.</p>
                <button @click="goToLinkBank" class="px-5 py-2.5 bg-[#286874] text-white text-xs font-bold rounded-xl hover:bg-[#1d4f59] transition shadow-sm w-full focus:outline-none">
                    Liên kết ngay
                </button>
            </div>

            <form v-else @submit.prevent="handleWithdraw" class="space-y-5">
                <!-- Thông tin ngân hàng nhận tiền -->
                <div class="bg-[#286874]/5 border border-[#286874]/10 rounded-2xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-[#286874]/10 text-[#286874] flex items-center justify-center">
                            <Icon name="lucide:landmark" size="22" />
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tài khoản nhận tiền</p>
                            <p class="text-sm font-bold text-slate-800 mt-0.5">{{ user.bank_name }}</p>
                            <p class="text-xs font-mono font-medium text-slate-500">{{ user.bank_account_number }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-[#286874] bg-[#286874]/10 px-2.5 py-1 rounded-full uppercase tracking-wider">
                        Thụ hưởng
                    </span>
                </div>

                <!-- Số dư khả dụng -->
                <div class="flex justify-between items-center text-xs font-semibold text-slate-500 px-1">
                    <span>Số dư khả dụng:</span>
                    <span class="font-bold text-[#286874] text-sm">{{ formatCurrency(balance) }}</span>
                </div>

                <!-- Nhập số tiền rút -->
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-gray-700 uppercase tracking-wider block">Số tiền muốn rút (đ)</label>
                        <span class="text-[11px] text-slate-400 font-medium">Tối đa 10.000.000đ/lần</span>
                    </div>
                    <div class="relative">
                        <input :value="displayWithdrawAmount" @input="handleWithdrawAmountInput" type="text" inputmode="numeric" required placeholder="Nhập số tiền muốn rút"
                            class="w-full pl-4 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-lg font-bold outline-none focus:border-[#286874] focus:bg-white transition-all" />
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">đ</span>
                    </div>
                </div>

                <!-- Nút chọn nhanh -->
                <div class="grid grid-cols-4 gap-2">
                    <button type="button" @click="setAmount(100000)" class="py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition focus:outline-none">
                        100k
                    </button>
                    <button type="button" @click="setAmount(200000)" class="py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition focus:outline-none">
                        200k
                    </button>
                    <button type="button" @click="setAmount(500000)" class="py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition focus:outline-none">
                        500k
                    </button>
                    <button type="button" @click="setAllAmount" class="py-2 border border-[#286874] text-[#286874] bg-[#286874]/5 rounded-xl text-xs font-bold hover:bg-[#286874]/10 transition focus:outline-none">
                        Tất cả
                    </button>
                </div>

                <!-- Nhập mô tả -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider block">Mô tả (Không bắt buộc)</label>
                    <input v-model="withdrawForm.description" type="text" placeholder="Yêu cầu rút tiền..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-[#286874] focus:bg-white transition-all font-medium" />
                </div>

                <!-- Nút hành động -->
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <button type="button" @click="closeWithdrawModal"
                        class="py-3 px-4 border border-slate-200 text-slate-500 font-bold rounded-xl hover:bg-slate-50 hover:text-slate-700 transition focus:outline-none text-sm">
                        Đóng
                    </button>
                    <button type="submit" :disabled="submittingWithdraw"
                        class="py-3 px-4 bg-[#286874] hover:bg-[#1d4f59] text-white font-bold rounded-xl transition focus:outline-none text-sm shadow-md shadow-[#286874]/10 flex items-center justify-center gap-2">
                        <Icon v-if="submittingWithdraw" name="svg-spinners:ring-resize" class="w-4 h-4" />
                        <span>Xác nhận rút</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Rút tiền dự trù -->
    <div v-if="isWithdrawHoldModalOpen" class="fixed inset-0 z-[999] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeWithdrawHoldModal"></div>

        <!-- Modal Content -->
        <div class="relative bg-white w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl z-10 p-8 border border-slate-100 flex flex-col animate-scale-in">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-black text-[#286874]">Rút tiền dự trù về ví</h3>
                    <p class="text-xs text-slate-500 mt-1">Chuyển tiền tạm giữ dự trù phạt nguội sang số dư ví khả dụng.</p>
                </div>
                <button @click="closeWithdrawHoldModal" class="h-8 w-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-800 transition flex items-center justify-center focus:outline-none">
                    <Icon name="ic:outline-close" size="20" />
                </button>
            </div>

            <!-- Hộp cảnh báo chính sách -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-5 text-left">
                <div class="flex gap-2.5 items-start">
                    <Icon name="lucide:shield-alert" size="20" class="text-amber-600 shrink-0 mt-0.5" />
                    <div>
                        <p class="text-xs font-bold text-amber-800">Chính sách tiền giữ dự trù (2%)</p>
                        <p class="text-[11px] text-amber-700/90 mt-1 leading-relaxed">
                            Khoản tiền dự trù 2% được hệ thống tạm giữ để xử lý các vấn đề phát sinh sau chuyến đi (ví dụ: phạt nguội). 
                            Bạn có thật sự muốn rút số tiền này về ví khả dụng không? 
                            <strong class="block mt-1">Số tiền rút tối thiểu phải từ 20.000đ trở lên.</strong>
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleWithdrawHold" class="space-y-5">
                <!-- Số dư dự trù khả dụng -->
                <div class="flex justify-between items-center text-xs font-semibold text-slate-500 px-1">
                    <span>Số dư tạm giữ khả dụng:</span>
                    <span class="font-bold text-amber-600 text-sm">{{ formatCurrency(holdBalance) }}</span>
                </div>

                <!-- Nhập số tiền rút -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider block">Số tiền muốn chuyển (đ)</label>
                    <div class="relative">
                        <input :value="displayWithdrawHoldAmount" @input="handleWithdrawHoldAmountInput" type="text" inputmode="numeric" required placeholder="Nhập số tiền muốn chuyển"
                            class="w-full pl-4 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-lg font-bold outline-none focus:border-[#286874] focus:bg-white transition-all" />
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">đ</span>
                    </div>
                </div>

                <!-- Nút chọn nhanh -->
                <div class="grid grid-cols-4 gap-2">
                    <button type="button" :disabled="holdBalance < 50000" @click="setHoldAmount(50000)" class="py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition focus:outline-none">
                        50k
                    </button>
                    <button type="button" :disabled="holdBalance < 100000" @click="setHoldAmount(100000)" class="py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition focus:outline-none">
                        100k
                    </button>
                    <button type="button" :disabled="holdBalance < 200000" @click="setHoldAmount(200000)" class="py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition focus:outline-none">
                        200k
                    </button>
                    <button type="button" @click="setAllHoldAmount" class="py-2 border border-[#286874] text-[#286874] bg-[#286874]/5 rounded-xl text-xs font-bold hover:bg-[#286874]/10 transition focus:outline-none">
                        Tất cả
                    </button>
                </div>

                <!-- Nút hành động -->
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <button type="button" @click="closeWithdrawHoldModal"
                        class="py-3 px-4 border border-slate-200 text-slate-500 font-bold rounded-xl hover:bg-slate-50 hover:text-slate-700 transition focus:outline-none text-sm">
                        Đóng
                    </button>
                    <button type="submit" :disabled="submittingWithdrawHold"
                        class="py-3 px-4 bg-[#286874] hover:bg-[#1d4f59] text-white font-bold rounded-xl transition focus:outline-none text-sm shadow-md shadow-[#286874]/10 flex items-center justify-center gap-2">
                        <Icon v-if="submittingWithdrawHold" name="svg-spinners:ring-resize" class="w-4 h-4" />
                        <span>Xác nhận</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { walletService } from '~/services/wallet.service'
import { useRouter } from 'vue-router'

const { user } = useAuth()

const router = useRouter()
const isLoading = ref(false)

const balance = ref(0)
const holdBalance = ref(0)
const rating = ref(5.0)
const completedTripsCount = ref(0)
const responseRate = ref(100)
const responseTime = ref('5 phút')
const acceptRate = ref(100)

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

const formatCurrency = (value) => {
    return new Intl.NumberFormat('vi-VN').format(value) + 'đ'
}

const formatInputNumber = (val) => {
    if (val === null || val === undefined || val === '') return ''
    return new Intl.NumberFormat('vi-VN').format(val)
}

const loadWalletDetails = async () => {
    isLoading.value = true
    try {
        const response = await walletService.getWalletDetails()
        if (response.success && response.data) {
            balance.value = response.data.balance
            holdBalance.value = response.data.hold_balance || 0
            rating.value = response.data.rating
            completedTripsCount.value = response.data.completed_trips_count
            responseRate.value = response.data.response_rate
            responseTime.value = response.data.response_time
            acceptRate.value = response.data.accept_rate
            summary.value = response.data.summary
        }
    } catch (error) {
        console.error('Error loading wallet details:', error)
    } finally {
        isLoading.value = false
    }
}

onMounted(() => {
    loadWalletDetails()
})

const navigateToStatement = () => {
    router.push('/mymonthlyreport')
}

const goBack = () => {
    router.back()
}

// ==========================================
// WITHDRAWAL MANAGEMENT
// ==========================================
const { showToast } = useToast()
const isWithdrawModalOpen = ref(false)
const submittingWithdraw = ref(false)
const withdrawForm = reactive({
    amount: null,
    description: ''
})

const displayWithdrawAmount = computed(() => {
    return formatInputNumber(withdrawForm.amount)
})

const handleWithdrawAmountInput = (e) => {
    const raw = e.target.value.replace(/\D/g, '')
    const num = raw ? parseInt(raw, 10) : null
    withdrawForm.amount = num
    e.target.value = num !== null ? new Intl.NumberFormat('vi-VN').format(num) : ''
}

const openWithdrawModal = () => {
    withdrawForm.amount = null
    withdrawForm.description = ''
    isWithdrawModalOpen.value = true
}

const closeWithdrawModal = () => {
    isWithdrawModalOpen.value = false
}

const setAmount = (val) => {
    withdrawForm.amount = val
}

const setAllAmount = () => {
    withdrawForm.amount = Math.min(balance.value, 10000000)
}

const goToLinkBank = () => {
    closeWithdrawModal()
    router.push('/profile')
}

const handleWithdraw = async () => {
    if (!withdrawForm.amount || withdrawForm.amount <= 0) {
        showToast('Vui lòng nhập số tiền hợp lệ.', 'error')
        return
    }
    if (withdrawForm.amount < 20000) {
        showToast('Số tiền rút tối thiểu là 20.000đ.', 'error')
        return
    }
    if (withdrawForm.amount > 10000000) {
        showToast('Số tiền rút tối đa cho một lần là 10.000.000đ.', 'error')
        return
    }
    if (withdrawForm.amount > balance.value) {
        showToast('Số dư tài khoản không đủ.', 'error')
        return
    }

    submittingWithdraw.value = true
    try {
        const response = await walletService.withdraw(withdrawForm.amount, withdrawForm.description)
        if (response.success) {
            showToast('Gửi yêu cầu rút tiền thành công!', 'success')
            closeWithdrawModal()
            // Tải lại dữ liệu ví
            await loadWalletDetails()
        } else {
            showToast(response.message || 'Rút tiền thất bại.', 'error')
        }
    } catch (error) {
        console.error('Error withdrawing:', error)
        const errMsg = error.response?._data?.message || 'Có lỗi xảy ra khi thực hiện rút tiền. Vui lòng thử lại sau.'
        showToast(errMsg, 'error')
    } finally {
        submittingWithdraw.value = false
    }
}

// ==========================================
// WITHDRAW HOLD BALANCE MANAGEMENT
// ==========================================
const isWithdrawHoldModalOpen = ref(false)
const submittingWithdrawHold = ref(false)
const withdrawHoldForm = reactive({
    amount: null
})

const displayWithdrawHoldAmount = computed(() => {
    return formatInputNumber(withdrawHoldForm.amount)
})

const handleWithdrawHoldAmountInput = (e) => {
    const raw = e.target.value.replace(/\D/g, '')
    const num = raw ? parseInt(raw, 10) : null
    withdrawHoldForm.amount = num
    e.target.value = num !== null ? new Intl.NumberFormat('vi-VN').format(num) : ''
}

const openWithdrawHoldModal = () => {
    if (holdBalance.value < 20000) {
        showToast('Số dư tiền tạm giữ (dự trù) tối thiểu từ 20.000đ trở lên mới có thể rút.', 'error')
        return
    }
    withdrawHoldForm.amount = null
    isWithdrawHoldModalOpen.value = true
}

const closeWithdrawHoldModal = () => {
    isWithdrawHoldModalOpen.value = false
}

const setHoldAmount = (val) => {
    withdrawHoldForm.amount = val
}

const setAllHoldAmount = () => {
    withdrawHoldForm.amount = holdBalance.value
}

const handleWithdrawHold = async () => {
    if (!withdrawHoldForm.amount || withdrawHoldForm.amount <= 0) {
        showToast('Vui lòng nhập số tiền hợp lệ.', 'error')
        return
    }
    if (withdrawHoldForm.amount < 20000) {
        showToast('Số tiền chuyển tối thiểu là 20.000đ.', 'error')
        return
    }
    if (withdrawHoldForm.amount > holdBalance.value) {
        showToast('Số dư tiền tạm giữ không đủ.', 'error')
        return
    }

    submittingWithdrawHold.value = true
    try {
        const response = await walletService.withdrawHold(withdrawHoldForm.amount)
        if (response.success) {
            showToast('Chuyển tiền dự trù về ví khả dụng thành công!', 'success')
            closeWithdrawHoldModal()
            // Tải lại dữ liệu ví
            await loadWalletDetails()
        } else {
            showToast(response.message || 'Thao tác thất bại.', 'error')
        }
    } catch (error) {
        console.error('Error claiming hold balance:', error)
        const errMsg = error.response?._data?.message || 'Có lỗi xảy ra khi rút tiền dự trù. Vui lòng thử lại sau.'
        showToast(errMsg, 'error')
    } finally {
        submittingWithdrawHold.value = false
    }
}
</script>

<style scoped>
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
}
</style>
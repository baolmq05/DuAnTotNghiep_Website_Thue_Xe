<template>
  <div class="min-h-screen bg-slate-50 py-10 px-4 sm:px-6 lg:px-8 font-sans antialiased text-slate-800">
    <div class="max-w-5xl mx-auto">
      
      <!-- Back button -->
      <div class="mb-6">
        <button 
          @click="goBack" 
          class="flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-800 transition"
        >
          <Icon name="lucide:arrow-left" class="w-4 h-4" />
          Quay lại chuyến đi
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-slate-200/60 shadow-sm">
        <Icon name="lucide:loader-2" class="w-12 h-12 text-[#1e4e57] animate-spin mb-4" />
        <p class="text-sm text-slate-500 font-medium">Đang tải thông tin thanh toán...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-16 bg-white rounded-3xl border border-slate-200/60 shadow-sm p-6">
        <Icon name="lucide:alert-circle" class="w-16 h-16 text-rose-500 mx-auto mb-4" />
        <h2 class="text-xl font-bold text-slate-800 mb-2">Đã xảy ra lỗi</h2>
        <p class="text-slate-500 mb-6 max-w-md mx-auto">{{ error }}</p>
        <button @click="goHome" class="px-6 py-2.5 bg-[#1e4e57] text-white text-xs font-bold rounded-xl transition hover:bg-[#286874]">
          Về trang chủ
        </button>
      </div>

      <!-- Main Layout -->
      <div v-else-if="trip" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left column: Payment options (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
          
          <!-- Step 1: Ratio Selection -->
          <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm space-y-5">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2.5">
              <span class="w-7 h-7 rounded-lg bg-[#1e4e57]/10 text-[#1e4e57] flex items-center justify-center font-bold text-sm">1</span>
              Chọn mức thanh toán
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Option A: Deposit 40% -->
              <div 
                @click="paymentRatio = 0.4"
                class="relative border-2 rounded-2xl p-5 cursor-pointer transition-all duration-200 hover:border-[#1e4e57]/50"
                :class="paymentRatio === 0.4 ? 'border-[#1e4e57] bg-[#1e4e57]/5 shadow-sm' : 'border-slate-200 bg-white'"
              >
                <div class="absolute top-4 right-4 w-5 h-5 rounded-full border flex items-center justify-center"
                     :class="paymentRatio === 0.4 ? 'border-[#1e4e57] bg-[#1e4e57]' : 'border-slate-300 bg-white'">
                  <div v-if="paymentRatio === 0.4" class="w-2 h-2 rounded-full bg-white"></div>
                </div>
                <div class="space-y-1 pr-6">
                  <h4 class="text-sm font-bold text-slate-800">Đặt cọc 40%</h4>
                  <p class="text-xs text-slate-400">Trả trước một phần để giữ xe</p>
                  <div class="text-lg font-black text-[#1e4e57] pt-2">
                    {{ formatCurrency(amountToPay) }}
                  </div>
                  <p class="text-[11px] text-slate-500 pt-2 border-t border-dashed border-slate-200/60 mt-3 leading-normal">
                    Còn lại: <strong>{{ formatCurrency((trip.cost - trip.discount_amount) * 0.6) }}</strong> (thanh toán trực tiếp cho chủ xe khi nhận xe)
                  </p>
                </div>
              </div>

              <!-- Option B: Full Payment 100% -->
              <div 
                @click="paymentRatio = 1"
                class="relative border-2 rounded-2xl p-5 cursor-pointer transition-all duration-200 hover:border-[#1e4e57]/50"
                :class="paymentRatio === 1 ? 'border-[#1e4e57] bg-[#1e4e57]/5 shadow-sm' : 'border-slate-200 bg-white'"
              >
                <div class="absolute top-4 right-4 w-5 h-5 rounded-full border flex items-center justify-center"
                     :class="paymentRatio === 1 ? 'border-[#1e4e57] bg-[#1e4e57]' : 'border-slate-300 bg-white'">
                  <div v-if="paymentRatio === 1" class="w-2 h-2 rounded-full bg-white"></div>
                </div>
                <div class="space-y-1 pr-6">
                  <h4 class="text-sm font-bold text-slate-800">Thanh toán 100%</h4>
                  <p class="text-xs text-slate-400">Thanh toán toàn bộ hóa đơn</p>
                  <div class="text-lg font-black text-[#1e4e57] pt-2">
                    {{ formatCurrency(trip.cost - trip.discount_amount) }}
                  </div>
                  <p class="text-[11px] text-slate-500 pt-2 border-t border-dashed border-slate-200/60 mt-3 leading-normal">
                    Bạn không cần phải thanh toán thêm tại quầy khi nhận xe
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Step 2: Gateway Selection -->
          <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm space-y-5">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2.5">
              <span class="w-7 h-7 rounded-lg bg-[#1e4e57]/10 text-[#1e4e57] flex items-center justify-center font-bold text-sm">2</span>
              Chọn phương thức thanh toán
            </h3>

            <div class="space-y-4">
              <!-- VNPay Selector Card -->
              <div 
                @click="paymentGateway = 'vnpay'"
                class="flex items-center justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200 hover:border-[#1e4e57]/50 group"
                :class="paymentGateway === 'vnpay' ? 'border-[#1e4e57] bg-[#1e4e57]/5 shadow-sm' : 'border-slate-200 bg-white'"
              >
                <div class="flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl bg-white border border-slate-100 flex items-center justify-center shadow-sm">
                    <span class="flex items-center gap-0.5 font-extrabold tracking-wider text-sm text-[#286874] uppercase">
                      VN<span class="text-[#e05638]">Pay</span>
                    </span>
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-slate-800 group-hover:text-[#1e4e57]">Cổng thanh toán VNPay</h4>
                    <p class="text-[11px] text-slate-400">Hỗ trợ ATM nội địa, QR Code, Thẻ quốc tế</p>
                  </div>
                </div>
                <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                     :class="paymentGateway === 'vnpay' ? 'border-[#1e4e57] bg-[#1e4e57]' : 'border-slate-300 bg-white'">
                  <div v-if="paymentGateway === 'vnpay'" class="w-2 h-2 rounded-full bg-white"></div>
                </div>
              </div>

              <!-- ZaloPay Selector Card -->
              <div 
                @click="paymentGateway = 'zalopay'"
                class="flex items-center justify-between p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200 hover:border-[#007aff]/50 group"
                :class="paymentGateway === 'zalopay' ? 'border-[#007aff] bg-[#007aff]/5 shadow-sm' : 'border-slate-200 bg-white'"
              >
                <div class="flex items-center gap-4">
                  <div class="w-12 h-12 rounded-xl bg-white border border-slate-100 flex items-center justify-center shadow-sm">
                    <span class="font-extrabold tracking-tight text-sm text-[#007aff]">ZaloPay</span>
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-slate-800 group-hover:text-[#007aff]">Ví điện tử ZaloPay</h4>
                    <p class="text-[11px] text-slate-400">Hỗ trợ ví ZaloPay, QR Code, thẻ ngân hàng</p>
                  </div>
                </div>
                <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                     :class="paymentGateway === 'zalopay' ? 'border-[#007aff] bg-[#007aff]' : 'border-slate-300 bg-white'">
                  <div v-if="paymentGateway === 'zalopay'" class="w-2 h-2 rounded-full bg-white"></div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Right column: Trip summary & Pay button (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
          <div class="bg-white rounded-3xl border border-slate-200/60 overflow-hidden shadow-sm sticky top-6">
            
            <!-- Car Image Header -->
            <div class="relative h-44 bg-slate-100">
              <img 
                :src="carThumbnail(trip.car)" 
                :alt="trip.car?.name" 
                class="w-full h-full object-cover"
              />
              <span class="absolute top-4 right-4 bg-slate-900/75 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg border border-white/10 backdrop-blur-sm shadow-sm">
                {{ trip.car?.license_plate }}
              </span>
            </div>

            <!-- Car Info Body -->
            <div class="p-6 space-y-5">
              <div>
                <span class="text-[10px] font-black uppercase text-[#1e4e57] tracking-wider">
                  {{ trip.car?.car_brand?.brand_name }} • {{ trip.car?.car_type?.type_name }}
                </span>
                <h3 class="text-base font-black text-slate-900 mt-0.5">{{ trip.car?.name }}</h3>
              </div>

              <!-- Date details -->
              <div class="space-y-2.5 bg-slate-50 border border-slate-100 rounded-2xl p-4 text-xs font-semibold text-slate-600">
                <div class="flex justify-between items-center">
                  <span class="text-slate-400">Nhận xe:</span>
                  <span class="text-slate-700 text-right">{{ formatDate(trip.start_at) }}</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-slate-400">Trả xe:</span>
                  <span class="text-slate-700 text-right">{{ formatDate(trip.end_at) }}</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-dashed border-slate-200/60">
                  <span class="text-slate-400">Tổng thời gian:</span>
                  <span class="text-slate-700 font-bold">{{ duration(trip.start_at, trip.end_at) }}</span>
                </div>
              </div>

              <!-- Price Breakdown -->
              <div class="space-y-2 text-xs font-semibold text-slate-600 pt-2">
                <div class="flex justify-between">
                  <span class="text-slate-400">Đơn giá thuê:</span>
                  <span>{{ formatCurrency(trip.cost) }}</span>
                </div>
                <div v-if="trip.discount_amount" class="flex justify-between text-rose-500">
                  <span>Khuyến mãi:</span>
                  <span>-{{ formatCurrency(trip.discount_amount) }}</span>
                </div>
                
                <div class="border-t border-slate-100 pt-3 flex justify-between items-baseline">
                  <span class="text-xs font-bold text-slate-800">Thành tiền:</span>
                  <span class="text-base font-extrabold text-slate-800">{{ formatCurrency(trip.cost - trip.discount_amount) }}</span>
                </div>

                <div class="bg-[#1e4e57]/5 rounded-2xl p-4 space-y-2 mt-4 border border-[#1e4e57]/10">
                  <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-700">Số tiền cần trả ngay:</span>
                    <span class="text-lg font-black text-[#1e4e57]">{{ formatCurrency(amountToPay) }}</span>
                  </div>
                  <div v-if="paymentRatio < 1" class="flex justify-between text-[11px] text-slate-500">
                    <span>Còn lại (trả khi nhận xe):</span>
                    <span class="font-bold">{{ formatCurrency((trip.cost - trip.discount_amount) * 0.6) }}</span>
                  </div>
                </div>
              </div>

              <!-- Pay Action Button -->
              <div class="pt-2">
                <button
                  @click="handlePaymentSubmit"
                  :disabled="initiating || loading"
                  class="w-full h-12 flex items-center justify-center gap-2 rounded-xl text-white font-bold text-sm transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-md shadow-[#1e4e57]/10 relative active:scale-[0.98]"
                  :class="paymentGateway === 'zalopay' ? 'bg-[#007aff] hover:bg-[#0062cc]' : 'bg-[#1e4e57] hover:bg-[#286874]'"
                >
                  <Icon v-if="initiating" name="lucide:loader-2" class="w-5 h-5 animate-spin" />
                  <span v-else class="flex items-center gap-1.5">
                    <Icon name="lucide:credit-card" class="w-4 h-4" />
                    Thanh toán bằng {{ paymentGateway === 'zalopay' ? 'ZaloPay' : 'VNPay' }}
                  </span>
                </button>
              </div>

            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { carService } from '~/services/car.service'
import { useVNPay } from '~/composables/useVNPay'
import { useZaloPay } from '~/composables/useZaloPay'
import { useToast } from '~/composables/useToast'

const route = useRoute()
const router = useRouter()
const { showToast } = useToast()

const trip = ref<any>(null)
const loading = ref(true)
const error = ref<string | null>(null)

// Payment State
const paymentRatio = ref(0.4) // 40% or 100%
const paymentGateway = ref<'vnpay' | 'zalopay'>('vnpay')
const initiating = ref(false)

const { initiatePayment: callVNPay } = useVNPay()
const { initiatePayment: callZaloPay } = useZaloPay()

const amountToPay = computed(() => {
  if (!trip.value) return 0
  const total = trip.value.cost - trip.value.discount_amount
  return Math.round(total * paymentRatio.value)
})

// Normalizes car thumbnail
function carThumbnail(car: any) {
  if (!car) return 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=600'
  const thumbnailImg = car.images?.find((img: any) => img.is_thumbnail === 1)?.image_url
    || car.images?.[0]?.image_url
    || 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=600';
  return thumbnailImg
}

const fetchTripDetails = async (tripId: string) => {
  loading.value = true
  error.value = null
  try {
    const res = await carService.getTripById(tripId)
    if (res && res.success && res.data) {
      const data = res.data
      // Validate if the trip requires payment (status === 1)
      if (data.status !== 1) {
        error.value = 'Chuyến đi này hiện tại không ở trạng thái cần thanh toán.'
        return
      }
      trip.value = data
    } else {
      error.value = 'Không tìm thấy thông tin chuyến đi này.'
    }
  } catch (err: any) {
    console.error('Lỗi khi tải chi tiết chuyến đi:', err)
    error.value = err.response?._data?.message || 'Không thể tải thông tin chuyến đi.'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  const tripId = route.query.trip_id as string
  if (!tripId) {
    error.value = 'Thiếu thông tin chuyến đi (trip_id).'
    loading.value = false
    return
  }
  fetchTripDetails(tripId)
})

const handlePaymentSubmit = async () => {
  if (!trip.value) return
  initiating.value = true
  try {
    const amt = amountToPay.value
    const id = trip.value.id
    if (paymentGateway.value === 'vnpay') {
      await callVNPay(amt, 'rental', id)
    } else {
      await callZaloPay(amt, 'rental', id)
    }
  } catch (err: any) {
    console.error('Lỗi thanh toán:', err)
    showToast(err.message || 'Thanh toán thất bại, vui lòng thử lại.', 'error')
  } finally {
    initiating.value = false
  }
}

const goBack = () => {
  if (trip.value) {
    router.push(`/trips/${trip.value.id}`)
  } else {
    router.go(-1)
  }
}

const goHome = () => {
  router.push('/')
}

function formatDate(dt: string) {
  if (!dt) return '—'
  return new Date(dt).toLocaleString('vi-VN', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit',
  })
}

function formatCurrency(amount: number) {
  if (amount === undefined || amount === null) return '—'
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount)
}

function duration(start: string, end: string) {
  if (!start || !end) return '—'
  const diff = new Date(end).getTime() - new Date(start).getTime()
  const days = Math.floor(diff / 86400000)
  const hours = Math.floor((diff % 86400000) / 3600000)
  return days > 0 ? `${days} ngày${hours > 0 ? ` ${hours} giờ` : ''}` : `${hours} giờ`
}
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(4px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fadeIn 0.25s ease-out forwards;
}
</style>

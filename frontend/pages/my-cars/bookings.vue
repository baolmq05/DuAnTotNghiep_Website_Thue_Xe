<template>
  <div class="space-y-6">
    <!-- Header Page Description -->
    <div class="flex flex-col gap-1.5 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
      <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
          class="text-[#1e4e57]">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" y1="13" x2="8" y2="13"></line>
          <line x1="16" y1="17" x2="8" y2="17"></line>
          <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        Duyệt yêu cầu thuê xe
      </h2>
      <p class="text-xs text-slate-400 font-medium">
        Danh sách các xe đang có khách hàng gửi yêu cầu thuê đến. Vui lòng xác nhận hoặc từ chối yêu cầu.
      </p>
    </div>

    <!-- Segmented Filter Tabs for Owner Bookings -->
    <div class="flex flex-wrap gap-2">
      <button v-for="tab in filterTabs" :key="tab.value" @click="activeFilter = tab.value"
        class="px-4 py-2.5 rounded-2xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 border shadow-sm hover:scale-[1.02] transform active:scale-[0.98] duration-200"
        :class="activeFilter === tab.value
          ? 'bg-[#1e4e57] border-[#1e4e57] text-white'
          : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'">
        <span>{{ tab.label }}</span>
        <span class="text-[10px] px-1.5 py-0.5 rounded-lg ml-0.5"
          :class="activeFilter === tab.value ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">
          {{ tab.count }}
        </span>
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div v-for="i in 2" :key="i"
        class="animate-pulse bg-white rounded-3xl h-[320px] border border-slate-100 p-5 flex flex-col justify-between shadow-sm">
        <div class="flex gap-4">
          <div class="bg-slate-100 h-28 w-40 rounded-2xl shrink-0"></div>
          <div class="space-y-3 flex-grow py-2">
            <div class="h-4 bg-slate-100 rounded w-1/3"></div>
            <div class="h-6 bg-slate-100 rounded w-3/4"></div>
            <div class="h-4 bg-slate-100 rounded w-1/2"></div>
          </div>
        </div>
        <div class="h-1 bg-slate-100 rounded-full w-full my-4"></div>
        <div class="flex gap-3">
          <div class="h-10 bg-slate-100 rounded-xl flex-1"></div>
          <div class="h-10 bg-slate-100 rounded-xl flex-1"></div>
        </div>
      </div>
    </div>

    <!-- Loaded Trips -->
    <div v-else-if="filteredTrips.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div v-for="trip in filteredTrips" :key="trip.id"
        class="group bg-white rounded-3xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
        <!-- Card Core Info -->
        <div class="p-5 flex flex-col flex-grow">
          <!-- Car details & Image -->
          <div class="flex gap-4 items-start pb-4 border-b border-slate-100">
            <!-- Thumbnail Image -->
            <div 
              @click="navigateTo('/trips/' + trip.id)"
              class="relative w-36 h-24 rounded-2xl overflow-hidden shrink-0 border border-slate-100 bg-slate-50 cursor-pointer"
            >
              <img :src="trip.car.image" :alt="trip.car.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              <span
                class="absolute bottom-1.5 left-1.5 bg-slate-900/70 backdrop-blur-sm text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow-sm">
                {{ trip.trip_type === 0 ? 'Theo ngày' : 'Theo km' }}
              </span>
            </div>

            <!-- Basic Text Info -->
            <div class="flex-grow min-w-0">
              <div class="flex flex-wrap items-center gap-1.5">
                <span
                  class="bg-slate-100 text-slate-800 text-[10px] font-mono font-bold px-2 py-0.5 rounded border border-slate-200">
                  {{ trip.car.license_plate }}
                </span>
                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[9px] font-bold"
                  :class="statusClass(trip.status)">
                  {{ statusLabel(trip.status) }}
                </span>
                <!-- Tag hiển thị đã đóng băng/tạm giữ tiền thuê cho chủ xe -->
                <span v-if="trip.payment_held"
                  class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[9px] font-bold bg-blue-50 text-[#1e4e57] border border-blue-200/50">
                  <Icon name="lucide:shield-check" class="w-3 h-3 text-[#1e4e57]" />
                  Đang giữ tiền
                </span>
                <!-- Tag hiển thị khi đã giải ngân về ví chủ xe -->
                <span v-if="!trip.payment_held && trip.status === 4"
                  class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[9px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200/50">
                  <Icon name="lucide:wallet" class="w-3 h-3 text-emerald-600" />
                  Đã về ví
                </span>
                <!-- Song song status gia hạn nếu có -->
                <span v-if="trip.latest_extension && trip.latest_extension.status !== 0"
                  class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[9px] font-bold border shadow-2xs"
                  :class="extensionStatusClass(trip.latest_extension.status)">
                  {{ extensionStatusLabel(trip.latest_extension.status) }}
                </span>
              </div>
              <h3
                @click="navigateTo('/trips/' + trip.id)"
                class="font-extrabold text-base text-slate-800 mt-2 line-clamp-1 group-hover:text-[#1e4e57] transition-colors cursor-pointer"
              >
                {{ trip.car.name }}
              </h3>
              <p class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="shrink-0 text-slate-400">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span class="truncate font-medium">{{ trip.car.location }}</span>
              </p>
            </div>
          </div>

          <!-- Trip schedules & details -->
          <div class="mt-4 space-y-3 flex-grow">
            <!-- Renter details -->
            <div class="bg-slate-50/60 p-3 rounded-2xl border border-slate-100 flex items-center justify-between">
              <div class="min-w-0">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Khách thuê</p>
                <p class="text-xs font-bold text-slate-800 mt-0.5 truncate">{{ trip.renter.name }}</p>
                <p class="text-[11px] text-slate-500 font-medium mt-0.5">{{ trip.renter.phone }}</p>
              </div>
              <button v-if="trip.status !== TripStatus.Pending" @click="startConversation(trip)"
                class="p-2.5 bg-white hover:bg-slate-100 text-[#1e4e57] rounded-xl border border-slate-200 shadow-sm transition-all flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
              </button>
            </div>

            <!-- Time breakdown -->
            <div class="grid grid-cols-2 gap-3 text-xs">
              <div class="bg-slate-50/40 p-2.5 rounded-xl border border-slate-100">
                <p class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-[#1e4e57]"></span> Nhận xe
                </p>
                <p class="font-bold text-slate-700 mt-1">{{ formatDate(trip.start_at) }}</p>
              </div>
              <div class="bg-slate-50/40 p-2.5 rounded-xl border border-slate-100">
                <p class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Trả xe
                </p>
                <p class="font-bold text-slate-700 mt-1">{{ formatDate(trip.end_at) }}</p>
              </div>
            </div>

            <!-- Duration & Cost row -->
            <div class="flex items-center justify-between pt-2">
              <div>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Thời gian thuê</p>
                <p class="text-xs font-bold text-slate-700 mt-0.5">{{ duration(trip.start_at, trip.end_at) }}</p>
              </div>
              <div class="text-right">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Tổng doanh thu nhận</p>
                <div class="flex items-center gap-1.5 justify-end mt-0.5">
                  <span class="text-base font-black text-[#1e4e57]">{{ formatCurrency(trip.cost - trip.discount_amount)
                  }}</span>
                  <span v-if="trip.discount_amount > 0" class="text-[10px] text-slate-400 line-through">
                    {{ formatCurrency(trip.cost) }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Extension Info Block -->
            <div v-if="trip.status === TripStatus.WaitingExtension || (trip.latest_extension && (trip.latest_extension.status === 1 || trip.latest_extension.status === 2))" class="bg-indigo-50 border border-indigo-100 rounded-2xl p-3.5 mt-3 text-xs text-indigo-900 space-y-2">
              <p class="font-bold flex items-center gap-1.5 text-indigo-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-650 shrink-0">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                  <line x1="12" y1="14" x2="12" y2="20"></line>
                  <line x1="9" y1="17" x2="15" y2="17"></line>
                </svg>
                <span v-if="trip.latest_extension?.status === 2">Đã đồng ý - Chờ khách thanh toán phí gia hạn</span>
                <span v-else>Yêu cầu gia hạn thêm ngày:</span>
              </p>
               <div class="flex flex-col gap-1.5 mt-1 font-medium">
                <div class="flex justify-between items-center">
                  <span class="text-slate-500 font-semibold">Ngày trả xe đề xuất mới:</span>
                  <span class="font-bold text-indigo-950 text-sm mt-0.5">{{ formatDate(trip.latest_extension?.end_date) }}</span>
                </div>
                <div v-if="trip.latest_extension?.extension_amount" class="flex justify-between items-center">
                  <span class="text-slate-500 font-semibold">Phí gia hạn đề xuất:</span>
                  <span class="font-bold text-[#1e4e57] text-sm">{{ formatCurrency(trip.latest_extension.extension_amount) }}</span>
                </div>
                <div class="text-[10px] text-indigo-600 bg-indigo-100/50 px-2.5 py-1 rounded-lg border border-indigo-200/40 w-fit mt-1">
                  Thời gian gia hạn thêm: {{ duration(trip.end_at, trip.latest_extension?.end_date) }}
                </div>
              </div>
            </div>

            <!-- Previous Extension status -->
            <div v-if="trip.latest_extension?.status === 3" class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 mt-3 text-emerald-900 flex items-center gap-2 text-xs font-medium">
              <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
              <span>Khách đã gia hạn thành công tới <strong>{{ formatDate(trip.end_at) }}</strong></span>
            </div>
            <div v-if="trip.latest_extension?.status === 4" class="bg-rose-50 border border-rose-200 rounded-xl p-3 mt-3 text-rose-900 flex items-center gap-2 text-xs font-medium">
              <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
              <span>Yêu cầu gia hạn trước đó đã bị từ chối/hủy</span>
            </div>
          </div>
        </div>

        <!-- Confirm / Reject Action buttons (ONLY for Pending) -->
        <div v-if="trip.status === TripStatus.Pending" class="p-5 pt-0 flex gap-3 border-t border-slate-100 bg-slate-50/20">
          <button @click="openRejectDialog(trip)"
            class="flex-1 py-3 px-4 border border-rose-200 bg-rose-50 hover:bg-rose-100 active:scale-[0.98] transition-all text-xs font-bold text-rose-600 rounded-xl flex items-center justify-center gap-1.5 shadow-sm transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
            Từ chối cho thuê
          </button>

          <button @click="confirmTrip(trip)"
            class="flex-1 py-3 px-4 bg-[#1e4e57] hover:bg-[#163a41] active:scale-[0.98] transition-all text-xs font-bold text-white rounded-xl flex items-center justify-center gap-1.5 shadow-sm shadow-[#1e4e57]/15 transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            Xác nhận cho thuê
          </button>
        </div>

        <!-- Confirm / Reject Action buttons for Extension -->
        <div v-else-if="trip.latest_extension?.status === 1 || (trip.status === TripStatus.WaitingExtension && (!trip.latest_extension || trip.latest_extension.status === 1))" class="p-5 pt-0 flex gap-3 border-t border-slate-100 bg-slate-50/20">
          <button @click="openRejectExtensionDialog(trip)"
            class="flex-1 py-3 px-4 border border-rose-200 bg-rose-50 hover:bg-rose-100 active:scale-[0.98] transition-all text-xs font-bold text-rose-600 rounded-xl flex items-center justify-center gap-1.5 shadow-sm transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
            Từ chối gia hạn
          </button>

          <button @click="confirmExtension(trip)"
            class="flex-1 py-3 px-4 bg-[#1e4e57] hover:bg-[#163a41] active:scale-[0.98] transition-all text-xs font-bold text-white rounded-xl flex items-center justify-center gap-1.5 shadow-sm shadow-[#1e4e57]/15 transform">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            Đồng ý gia hạn
          </button>
        </div>
        
        <div v-else-if="trip.latest_extension?.status === 2 || (trip.status === TripStatus.WaitingExtension && trip.latest_extension?.status === 2)" class="p-4 border-t border-slate-100 bg-amber-50/60 text-center text-xs font-bold text-amber-800">
          Chủ xe đã đồng ý gia hạn - Đang chờ khách hàng thanh toán
        </div>

        <!-- Complete Action button for WaitingReturn trips -->
        <div v-else-if="trip.status === TripStatus.WaitingReturn" class="p-5 pt-0 flex gap-3 border-t border-slate-100 bg-slate-50/20 pt-4">
          <button @click="openCompleteTripModal(trip)"
            class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] transition-all text-xs font-bold text-white rounded-xl flex items-center justify-center gap-1.5 shadow-sm shadow-emerald-600/15 transform cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            Hoàn thành chuyến đi
          </button>
        </div>

        <!-- Review / Completed State actions for Completed trips -->
        <div v-else-if="trip.status === TripStatus.Complete" class="p-5 pt-0 border-t border-slate-100 bg-slate-50/20 pt-4">
          <div v-if="getOwnerReview(trip)" class="bg-emerald-50/60 border border-emerald-100 rounded-2xl p-3 text-xs text-emerald-800 space-y-1 w-full">
            <div class="flex items-center gap-1.5 font-bold">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              Bạn đã đánh giá khách thuê: 
              <span class="text-amber-500 font-bold flex items-center gap-0.5 ml-1">
                {{ getOwnerReview(trip).rating }} ★
              </span>
            </div>
            <p class="text-[11px] text-slate-650 italic font-medium mt-0.5" v-if="getOwnerReview(trip).comment">
              "{{ getOwnerReview(trip).comment }}"
            </p>
          </div>
          <button v-else @click="openOwnerReviewModal(trip)"
            class="w-full py-3 px-4 bg-[#1e4e57] hover:bg-[#163a41] active:scale-[0.98] transition-all text-xs font-bold text-white rounded-xl flex items-center justify-center gap-1.5 shadow-sm shadow-[#1e4e57]/15 transform cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-amber-300">
              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
            Đánh giá khách thuê
          </button>
        </div>

        <!-- Start Trip Action button for Confirmed trips -->
        <div v-else-if="trip.status === TripStatus.Confirmed" class="p-5 pt-0 flex gap-3 border-t border-slate-100 bg-slate-50/20 pt-4">
          <button @click="openStartTripModal(trip)"
            class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] transition-all text-xs font-bold text-white rounded-xl flex items-center justify-center gap-1.5 shadow-sm shadow-emerald-600/15 transform cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="5 3 19 12 5 21 5 3"></polygon>
            </svg>
            Bắt đầu chuyến đi
          </button>
        </div>

        <!-- Action button for other statuses -->
        <div v-else class="p-5 pt-0 flex gap-3 border-t border-slate-100 bg-slate-50/20">
          <button @click="navigateTo('/trips/' + trip.id)"
            class="flex-1 py-3 px-4 bg-[#1e4e57] hover:bg-[#163a41] active:scale-[0.98] transition-all text-xs font-bold text-white rounded-xl flex items-center justify-center gap-1.5 shadow-sm shadow-[#1e4e57]/15 transform cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
            Xem chi tiết chuyến đi
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else
      class="min-h-[350px] flex flex-col items-center justify-center bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
      <div
        class="w-28 h-28 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300">
          <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
          <line x1="16" y1="2" x2="16" y2="6"></line>
          <line x1="8" y1="2" x2="8" y2="6"></line>
          <line x1="3" y1="10" x2="21" y2="10"></line>
        </svg>
      </div>

      <h3 class="mt-5 text-lg font-bold text-slate-800">Không tìm thấy yêu cầu nào</h3>
      <p class="text-slate-400 mt-1.5 text-xs text-center max-w-sm">Không có chuyến đi nào thuộc bộ lọc này.</p>
    </div>

    <!-- Reject Reason Dialog Modal -->
    <Transition name="fade">
      <div v-if="showRejectModal"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div
          class="bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all p-6 space-y-4">
          <!-- Header -->
          <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
              Lý do từ chối
            </h3>
            <button @click="closeRejectDialog" class="p-1 hover:bg-slate-100 rounded-full transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="text-slate-400">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>

          <!-- Body Description -->
          <div v-if="selectedTripForReject" class="space-y-2">
            <p class="text-xs text-slate-500 font-medium">
              Vui lòng nhập lý do từ chối yêu cầu thuê xe <span class="font-bold text-slate-800">{{
                selectedTripForReject.car.name }}</span> của khách hàng <span class="font-bold text-slate-800">{{
                  selectedTripForReject.renter.name }}</span>.
            </p>

            <textarea v-model="rejectReason" rows="4"
              placeholder="Nhập lý do chi tiết (ví dụ: Xe đang bảo dưỡng, bận lịch đột xuất...)"
              class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-700 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 placeholder:text-slate-400"></textarea>
          </div>

          <!-- Footer Actions -->
          <div class="flex gap-3 pt-2">
            <button @click="closeRejectDialog"
              class="flex-1 py-2.5 border border-slate-200 bg-white hover:bg-slate-50 active:scale-[0.98] transition-all text-xs font-bold text-slate-600 rounded-xl">
              Hủy
            </button>
            <button @click="submitRejection"
              class="flex-1 py-2.5 bg-rose-500 hover:bg-rose-600 active:scale-[0.98] transition-all text-xs font-bold text-white rounded-xl shadow-sm shadow-rose-500/20">
              Xác nhận từ chối
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Start Trip Modal -->
    <Transition name="fade">
      <div v-if="showStartModal"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div
          class="bg-white rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all p-6 space-y-4 max-h-[90vh] overflow-y-auto">
          <!-- Header -->
          <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600">
                <polygon points="5 3 19 12 5 21 5 3"></polygon>
              </svg>
              Bắt đầu chuyến đi
            </h3>
            <button @click="closeStartModal" class="p-1 hover:bg-slate-100 rounded-full transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="text-slate-400">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>

          <!-- Body Description -->
          <div v-if="selectedTripForStart" class="space-y-3">
            <p class="text-xs text-slate-500 leading-relaxed font-medium">
              Vui lòng chụp và tải lên hình ảnh hiện trạng của xe <span class="font-bold text-slate-800">{{ selectedTripForStart.car.name }}</span> trước khi bàn giao cho khách hàng <span class="font-bold text-slate-800">{{ selectedTripForStart.renter.name }}</span>. Đây là cơ sở đối chiếu tình trạng xe khi hoàn thành chuyến đi.
            </p>

            <!-- ImageUpload component -->
            <ImageUpload ref="startTripImageUploadRef" v-model="startTripUploadedImages" :max-files="5" />
          </div>

          <!-- Footer Actions -->
          <div class="flex gap-3 pt-2">
            <button @click="closeStartModal"
              class="flex-1 py-2.5 border border-slate-200 bg-white hover:bg-slate-50 active:scale-[0.98] transition-all text-xs font-bold text-slate-600 rounded-xl">
              Hủy
            </button>
            <button @click="submitStartTrip"
              :disabled="startTripUploadedImages.length === 0 || starting"
              class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-350 disabled:cursor-not-allowed active:scale-[0.98] transition-all text-xs font-bold text-white rounded-xl shadow-sm shadow-emerald-500/20 flex items-center justify-center gap-1.5 cursor-pointer">
              <span v-if="starting" class="flex items-center gap-1.5">
                <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Đang xử lý...
              </span>
              <span v-else>Xác nhận bắt đầu</span>
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Complete Trip Modal -->
    <Transition name="fade">
      <div v-if="showCompleteModal"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div
          class="bg-white rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all p-6 space-y-4 max-h-[90vh] overflow-y-auto">
          <!-- Header -->
          <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              Hoàn thành chuyến đi
            </h3>
            <button @click="closeCompleteModal" class="p-1 hover:bg-slate-100 rounded-full transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="text-slate-400">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>

          <!-- Body Description -->
          <div v-if="selectedTripForComplete" class="space-y-3">
            <p class="text-xs text-slate-500 leading-relaxed font-medium">
              Vui lòng chụp và tải lên hình ảnh hiện trạng của xe <span class="font-bold text-slate-800">{{ selectedTripForComplete.car.name }}</span> khi khách hàng <span class="font-bold text-slate-800">{{ selectedTripForComplete.renter.name }}</span> hoàn trả xe. Đây là cơ sở đối chiếu tình trạng xe sau khi sử dụng.
            </p>

            <!-- ImageUpload component -->
            <ImageUpload ref="imageUploadRef" v-model="uploadedImages" :max-files="5" />
          </div>

          <!-- Footer Actions -->
          <div class="flex gap-3 pt-2">
            <button @click="closeCompleteModal"
              class="flex-1 py-2.5 border border-slate-200 bg-white hover:bg-slate-50 active:scale-[0.98] transition-all text-xs font-bold text-slate-600 rounded-xl">
              Hủy
            </button>
            <button @click="submitCompletion"
              :disabled="uploadedImages.length === 0 || completing"
              class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-350 disabled:cursor-not-allowed active:scale-[0.98] transition-all text-xs font-bold text-white rounded-xl shadow-sm shadow-emerald-500/20 flex items-center justify-center gap-1.5 cursor-pointer">
              <span v-if="completing" class="flex items-center gap-1.5">
                <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Đang xử lý...
              </span>
              <span v-else>Xác nhận hoàn thành</span>
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Owner Review Modal -->
    <Transition name="fade">
      <div v-if="showReviewModal"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div
          class="bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-100 transform transition-all p-6 space-y-4" @click.stop>
          <!-- Header -->
          <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500 fill-amber-500">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
              </svg>
              Đánh giá khách thuê
            </h3>
            <button @click="closeReviewModal" class="p-1 hover:bg-slate-100 rounded-full transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="text-slate-400">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>

          <!-- Content -->
          <div class="space-y-4 text-xs">
            <div v-if="selectedTripForReview" class="text-slate-500 font-medium">
              Đánh giá trải nghiệm của bạn với khách hàng <span class="font-bold text-slate-800">{{ selectedTripForReview.renter.name }}</span> cho chuyến đi #{{ selectedTripForReview.id }}.
            </div>

            <!-- Star selection -->
            <div class="space-y-2 flex flex-col items-center py-2">
              <label class="block font-bold text-slate-700 text-center">Số sao đánh giá:</label>
              <div class="flex items-center gap-2">
                <button 
                  v-for="star in 5" 
                  :key="star"
                  type="button" 
                  @click="reviewRating = star"
                  class="p-1 rounded-full hover:scale-110 active:scale-95 transition-all text-slate-300 hover:text-amber-400 cursor-pointer border-0 bg-transparent outline-none"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    :class="star <= reviewRating ? 'text-amber-400 fill-amber-400' : 'text-slate-350'">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                  </svg>
                </button>
              </div>
              <span class="font-bold text-[#1e4e57] text-[11px] mt-1">
                {{ ratingLabel(reviewRating) }}
              </span>
            </div>

            <!-- Comment input -->
            <div class="space-y-1.5">
              <label class="block font-bold text-slate-700">Ý kiến đóng góp (tùy chọn):</label>
              <textarea 
                v-model="reviewComment" 
                rows="4"
                placeholder="Khách hàng đi xe giữ gìn sạch sẽ, trả xe đúng giờ, lịch sự..."
                class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-700 outline-none transition focus:border-[#1e4e57] focus:bg-white focus:ring-4 focus:ring-[#1e4e57]/10 placeholder:text-slate-400"
              ></textarea>
            </div>

            <!-- Submit button -->
            <button 
              @click="submitOwnerReview"
              :disabled="reviewRating === 0 || submittingReview"
              class="w-full py-3 px-4 rounded-xl text-xs font-bold text-white transition-all bg-[#1e4e57] hover:bg-[#286874] disabled:bg-slate-300 disabled:cursor-not-allowed flex items-center justify-center gap-1.5 active:scale-[0.98] cursor-pointer"
            >
              <span v-if="submittingReview" class="flex items-center gap-1.5">
                <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Đang gửi...
              </span>
              <span v-else>Gửi đánh giá</span>
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { carService } from '~/services/car.service';
import { useToast } from '~/composables/useToast';
import { ChatService } from '~/services/chat.service';
import { TripStatus, TripStatusLabel, TripStatusBadgeClass } from '~/config/trip-status';

definePageMeta({
  layout: "my-cars",
});

const chatService = new ChatService();
const { showToast } = useToast();
const loading = ref(true);
const ownerTrips = ref<any[]>([]);
const activeFilter = ref<'pending' | 'waiting_payment' | 'confirmed' | 'active' | 'waiting_extension' | 'waiting_return' | 'completed' | 'cancelled_renter' | 'cancelled_owner'>('pending');

// Modal States
const showRejectModal = ref(false);
const selectedTripForReject = ref<any | null>(null);
const rejectReason = ref('');

// Computed filter tabs with counts
const filterTabs = computed(() => {
  return [
    {
      value: 'pending' as const,
      label: 'Chờ duyệt',
      count: ownerTrips.value.filter(t => t.status === TripStatus.Pending).length,
    },
    {
      value: 'waiting_payment' as const,
      label: 'Chờ thanh toán',
      count: ownerTrips.value.filter(t => t.status === TripStatus.WaitingPayment).length,
    },
    {
      value: 'confirmed' as const,
      label: 'Đã xác nhận',
      count: ownerTrips.value.filter(t => t.status === TripStatus.Confirmed).length,
    },
    {
      value: 'active' as const,
      label: 'Đang diễn ra',
      count: ownerTrips.value.filter(t => t.status === TripStatus.Ongoing).length,
    },
    {
      value: 'waiting_extension' as const,
      label: 'Chờ gia hạn',
      count: ownerTrips.value.filter(t => t.status === TripStatus.WaitingExtension || (t.latest_extension && (t.latest_extension.status === 1 || t.latest_extension.status === 2))).length,
    },
    {
      value: 'waiting_return' as const,
      label: 'Chờ trả xe',
      count: ownerTrips.value.filter(t => t.status === TripStatus.WaitingReturn).length,
    },
    {
      value: 'completed' as const,
      label: 'Đã hoàn thành',
      count: ownerTrips.value.filter(t => t.status === TripStatus.Complete).length,
    },
    {
      value: 'cancelled_renter' as const,
      label: 'Khách hủy chuyến',
      count: ownerTrips.value.filter(t => t.status === TripStatus.UserCancel).length,
    },
    {
      value: 'cancelled_owner' as const,
      label: 'Chủ xe từ chối',
      count: ownerTrips.value.filter(t => t.status === TripStatus.OwnerCancel).length,
    },
  ];
});

// Load owner trips data
const loadOwnerTrips = async () => {
  loading.value = true;
  try {
    const res = await carService.getTrips();
    console.log('[API getTrips Response]:', res);

    let apiTrips = [];
    if (res && res.success && res.data && res.data.owner) {
      apiTrips = res.data.owner.map((trip: any) => {
        const thumbnailImg = trip.car?.images?.find((img: any) => img.is_thumbnail === 1)?.image_url
          || trip.car?.images?.[0]?.image_url
          || 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=600';

        return {
          ...trip,
          car: {
            ...trip.car,
            image: thumbnailImg,
            location: trip.car?.car_location?.address || 'Chưa cập nhật'
          },
          renter: {
            name: trip.user?.name || 'Khách hàng',
            phone: trip.user?.phone || 'Chưa cập nhật SĐT'
          }
        };
      });
    }
    ownerTrips.value = apiTrips;
  } catch (err) {
    console.error('Không tải được danh sách yêu cầu thuê xe:', err);
    ownerTrips.value = [];
    showToast('Kết nối máy chủ thất bại.', 'error');
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadOwnerTrips();
});

// Filtering based on current active tab
const filteredTrips = computed(() => {
  if (activeFilter.value === 'pending') {
    return ownerTrips.value.filter(t => t.status === TripStatus.Pending);
  } else if (activeFilter.value === 'waiting_payment') {
    return ownerTrips.value.filter(t => t.status === TripStatus.WaitingPayment);
  } else if (activeFilter.value === 'confirmed') {
    return ownerTrips.value.filter(t => t.status === TripStatus.Confirmed);
  } else if (activeFilter.value === 'active') {
    return ownerTrips.value.filter(t => t.status === TripStatus.Ongoing);
  } else if (activeFilter.value === 'waiting_extension') {
    return ownerTrips.value.filter(t => t.status === TripStatus.WaitingExtension || (t.latest_extension && (t.latest_extension.status === 1 || t.latest_extension.status === 2)));
  } else if (activeFilter.value === 'waiting_return') {
    return ownerTrips.value.filter(t => t.status === TripStatus.WaitingReturn);
  } else if (activeFilter.value === 'completed') {
    return ownerTrips.value.filter(t => t.status === TripStatus.Complete);
  } else if (activeFilter.value === 'cancelled_renter') {
    return ownerTrips.value.filter(t => t.status === TripStatus.UserCancel);
  } else {
    return ownerTrips.value.filter(t => t.status === TripStatus.OwnerCancel);
  }
});

// Helper labels and classes
function statusLabel(status: number) {
  return TripStatusLabel[status as TripStatus] ?? '—';
}

function statusClass(status: number) {
  return TripStatusBadgeClass[status as TripStatus] ?? 'bg-slate-100 text-slate-500';
}

function extensionStatusLabel(status?: number) {
  switch (status) {
    case 1: return 'Gia hạn: Chờ duyệt';
    case 2: return 'Gia hạn: Chờ thanh toán';
    case 3: return 'Gia hạn: Thành công';
    case 4: return 'Gia hạn: Bị từ chối';
    default: return '';
  }
}

function extensionStatusClass(status?: number) {
  switch (status) {
    case 1: return 'bg-indigo-50 border-indigo-200 text-indigo-700';
    case 2: return 'bg-amber-50 border-amber-200 text-amber-700';
    case 3: return 'bg-emerald-50 border-emerald-200 text-emerald-700';
    case 4: return 'bg-rose-50 border-rose-200 text-rose-700';
    default: return 'bg-slate-100 border-slate-200 text-slate-600';
  }
}

function formatDate(dt: string) {
  if (!dt) return '—';
  return new Date(dt).toLocaleString('vi-VN', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit',
  });
}

function duration(start: string, end: string) {
  if (!start || !end) return '—';
  const diff = new Date(end).getTime() - new Date(start).getTime();
  const days = Math.floor(diff / 86400000);
  const hours = Math.floor((diff % 86400000) / 3600000);
  return days > 0 ? `${days} ngày${hours > 0 ? ` ${hours} giờ` : ''}` : `${hours} giờ`;
}

function formatCurrency(amount: number) {
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
}

// Phê duyệt yêu cầu thuê xe lên API backend
const confirmTrip = async (trip: any) => {
  try {
    const res = await carService.confirmTrip(trip.id);
    if (res && res.success) {
      showToast(`Đã xác nhận cho thuê xe đối với yêu cầu của ${trip.renter.name} thành công.`, 'success');
      await loadOwnerTrips(); // Tải lại danh sách từ server
    } else {
      showToast(res.message || 'Duyệt yêu cầu thất bại.', 'error');
    }
  } catch (err: any) {
    console.error('Lỗi khi phê duyệt chuyến đi:', err);
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi phê duyệt.', 'error');
  }
};

const isRejectingExtension = ref(false);

const openRejectDialog = (trip: any) => {
  selectedTripForReject.value = trip;
  rejectReason.value = '';
  isRejectingExtension.value = false;
  showRejectModal.value = true;
};

const openRejectExtensionDialog = (trip: any) => {
  selectedTripForReject.value = trip;
  rejectReason.value = '';
  isRejectingExtension.value = true;
  showRejectModal.value = true;
};

const closeRejectDialog = () => {
  showRejectModal.value = false;
  selectedTripForReject.value = null;
  rejectReason.value = '';
  isRejectingExtension.value = false;
};

// Từ chối yêu cầu thuê xe hoặc gia hạn lên API backend
const submitRejection = async () => {
  if (!rejectReason.value.trim()) {
    showToast('Vui lòng nhập lý do từ chối.', 'error');
    return;
  }

  if (selectedTripForReject.value) {
    const tripId = selectedTripForReject.value.id;
    const renterName = selectedTripForReject.value.renter.name;

    try {
      let res;
      if (isRejectingExtension.value) {
        res = await carService.rejectExtension(tripId, rejectReason.value);
      } else {
        res = await carService.rejectTrip(tripId, rejectReason.value);
      }

      if (res && res.success) {
        showToast('Đã từ chối yêu cầu thành công.', 'success');
        await loadOwnerTrips(); // Tải lại danh sách từ server
      } else {
        showToast(res.message || 'Từ chối yêu cầu thất bại.', 'error');
      }
    } catch (err: any) {
      console.error('Lỗi khi từ chối:', err);
      showToast(err.response?._data?.message || 'Có lỗi xảy ra khi từ chối.', 'error');
    }
  }

  closeRejectDialog();
};

// Phê duyệt yêu cầu gia hạn
const confirmExtension = async (trip: any) => {
  try {
    const res = await carService.approveExtension(trip.id);
    if (res && res.success) {
      showToast(`Đã duyệt yêu cầu gia hạn cho chuyến đi của ${trip.renter.name} thành công.`, 'success');
      await loadOwnerTrips(); // Tải lại danh sách từ server
    } else {
      showToast(res.message || 'Duyệt gia hạn thất bại.', 'error');
    }
  } catch (err: any) {
    console.error('Lỗi khi phê duyệt gia hạn:', err);
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi duyệt gia hạn.', 'error');
  }
};

// Khởi tạo cuộc hội thoại chat và chuyển hướng sang trang chats
const startConversation = async (trip: any) => {
  try {
    const res = await chatService.storeConversation({ trip_id: trip.id });
    if (res && res.success) {
      navigateTo('/chats');
    } else {
      showToast('Không thể tạo cuộc hội thoại chat.', 'error');
    }
  } catch (err) {
    console.error('Lỗi khi tạo cuộc hội thoại chat:', err);
    showToast('Lỗi hệ thống khi mở chat.', 'error');
  }
};

// Complete Trip State
const showCompleteModal = ref(false);
const selectedTripForComplete = ref<any | null>(null);
const completing = ref(false);
const uploadedImages = ref<string[]>([]);
const imageUploadRef = ref<any>(null);

// Start Trip State
const showStartModal = ref(false);
const selectedTripForStart = ref<any | null>(null);
const starting = ref(false);
const startTripUploadedImages = ref<string[]>([]);
const startTripImageUploadRef = ref<any>(null);

const openStartTripModal = (trip: any) => {
  selectedTripForStart.value = trip;
  startTripUploadedImages.value = [];
  starting.value = false;
  showStartModal.value = true;
};

const closeStartModal = () => {
  showStartModal.value = false;
  selectedTripForStart.value = null;
  startTripUploadedImages.value = [];
};

const submitStartTrip = async () => {
  if (startTripUploadedImages.value.length === 0) {
    showToast('Vui lòng tải lên ít nhất 1 ảnh xe trước khi bắt đầu chuyến đi.', 'error');
    return;
  }
  if (!selectedTripForStart.value) return;

  starting.value = true;
  try {
    const urls = await startTripImageUploadRef.value.upload();
    if (urls.length === 0) {
      showToast('Vui lòng tải lên ít nhất 1 ảnh xe để bắt đầu.', 'error');
      starting.value = false;
      return;
    }
    const res = await carService.startTrip(selectedTripForStart.value.id, {
      images: urls,
    });
    if (res && res.success) {
      showToast('Khởi hành chuyến đi thành công!', 'success');
      closeStartModal();
      await loadOwnerTrips();
    } else {
      showToast(res.message || 'Lỗi khi bắt đầu chuyến đi.', 'error');
    }
  } catch (err: any) {
    console.error('Lỗi khi bắt đầu chuyến đi:', err);
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi bắt đầu chuyến đi.', 'error');
  } finally {
    starting.value = false;
  }
};

// Owner Review State
const showReviewModal = ref(false);
const selectedTripForReview = ref<any | null>(null);
const reviewRating = ref(5);
const reviewComment = ref('');
const submittingReview = ref(false);

const openCompleteTripModal = (trip: any) => {
  selectedTripForComplete.value = trip;
  uploadedImages.value = [];
  completing.value = false;
  showCompleteModal.value = true;
};

const closeCompleteModal = () => {
  showCompleteModal.value = false;
  selectedTripForComplete.value = null;
  uploadedImages.value = [];
};

const submitCompletion = async () => {
  if (uploadedImages.value.length === 0) {
    showToast('Vui lòng tải lên ít nhất 1 ảnh xe khi trả xe.', 'error');
    return;
  }
  if (!selectedTripForComplete.value) return;

  completing.value = true;
  try {
    const urls = await imageUploadRef.value.upload();
    if (urls.length === 0) {
      showToast('Vui lòng tải lên ít nhất 1 ảnh xe sau chuyến đi để hoàn thành.', 'error');
      completing.value = false;
      return;
    }

    const res = await carService.completeTrip(selectedTripForComplete.value.id, {
      images: urls,
    });
    if (res && res.success) {
      showToast('Hoàn thành chuyến đi thành công!', 'success');
      closeCompleteModal();
      await loadOwnerTrips();
    } else {
      showToast(res.message || 'Lỗi khi hoàn thành chuyến đi.', 'error');
    }
  } catch (err: any) {
    console.error('Lỗi khi hoàn thành chuyến đi:', err);
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi hoàn thành chuyến đi.', 'error');
  } finally {
    completing.value = false;
  }
};

const openOwnerReviewModal = (trip: any) => {
  selectedTripForReview.value = trip;
  reviewRating.value = 5;
  reviewComment.value = '';
  submittingReview.value = false;
  showReviewModal.value = true;
};

const closeReviewModal = () => {
  showReviewModal.value = false;
  selectedTripForReview.value = null;
};

const ratingLabel = (rating: number) => {
  switch (rating) {
    case 1: return 'Rất kém 😠';
    case 2: return 'Kém 🙁';
    case 3: return 'Bình thường 😐';
    case 4: return 'Tốt 🙂';
    case 5: return 'Tuyệt vời 🥰';
    default: return 'Chọn số sao';
  }
};

const submitOwnerReview = async () => {
  if (reviewRating.value === 0) {
    showToast('Vui lòng chọn số sao đánh giá.', 'error');
    return;
  }
  if (!selectedTripForReview.value) return;

  submittingReview.value = true;
  try {
    const res = await carService.submitReview(selectedTripForReview.value.id, {
      rating: reviewRating.value,
      comment: reviewComment.value,
    });
    if (res && res.success) {
      showToast('Đã gửi đánh giá khách thuê thành công!', 'success');
      closeReviewModal();
      await loadOwnerTrips();
    } else {
      showToast(res.message || 'Gửi đánh giá thất bại.', 'error');
    }
  } catch (err: any) {
    console.error('Lỗi khi gửi đánh giá:', err);
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi gửi đánh giá.', 'error');
  } finally {
    submittingReview.value = false;
  }
};

const getOwnerReview = (trip: any) => {
  if (!trip || !trip.reviews) return null;
  return trip.reviews.find((r: any) => r.review_type === 0);
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

<template>
  <div class="min-h-screen bg-slate-50/50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

      <!-- Back Button & Breadcrumbs -->
      <div class="mb-6 flex items-center justify-between">
        <button @click="handleBack"
          class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-[#1e4e57] transition-colors bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
          <Icon name="lucide:arrow-left" class="w-4 h-4" />
          Quay lại danh sách
        </button>
        <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
          Mã chuyến đi: #{{ route.params.id }}
        </span>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="space-y-6">
        <div class="animate-pulse bg-white rounded-3xl h-[120px] border border-slate-100 p-6 shadow-sm"></div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div class="lg:col-span-2 space-y-6">
            <div class="animate-pulse bg-white rounded-3xl h-[300px] border border-slate-100 shadow-sm"></div>
          </div>
          <div class="space-y-6">
            <div class="animate-pulse bg-white rounded-3xl h-[400px] border border-slate-100 shadow-sm"></div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="error"
        class="bg-white rounded-3xl border border-rose-100 p-12 text-center shadow-sm max-w-lg mx-auto">
        <div
          class="w-16 h-16 rounded-full bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-500 mx-auto mb-4">
          <Icon name="lucide:triangle-alert" class="w-6 h-6" />
        </div>
        <h3 class="text-lg font-bold text-slate-800">Đã xảy ra lỗi</h3>
        <p class="text-slate-500 mt-2 text-sm">{{ error }}</p>
        <button @click="fetchTripDetails"
          class="mt-6 px-6 py-2.5 bg-[#1e4e57] hover:bg-[#286874] text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-[#1e4e57]/10">
          Thử lại
        </button>
      </div>

      <!-- Content -->
      <div v-else-if="trip" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left 2 columns: Trip Details -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Top summary banner -->
          <div
            class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
              <div
                class="w-14 h-14 rounded-2xl bg-[#1e4e57]/10 text-[#1e4e57] flex items-center justify-center shrink-0">
                <Icon name="lucide:route" class="w-6 h-6" />
              </div>
              <div>
                <h1 class="text-lg font-black text-slate-900">Chi tiết hành trình</h1>
                <p class="text-xs text-slate-400 font-medium">Khám phá thông tin lịch trình và thanh toán</p>
              </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
              <span class="px-4 py-1.5 rounded-full text-xs font-bold border shadow-sm"
                :class="statusClass(trip.status)">
                {{ statusLabel(trip.status) }}
              </span>
              <!-- Tag hiển thị trạng thái tạm giữ tiền cho chủ xe -->
              <span v-if="isOwner && trip.payment_held"
                class="px-4 py-1.5 rounded-full text-xs font-bold border shadow-sm bg-blue-50 text-[#1e4e57] border-blue-200/60 flex items-center gap-1.5">
                <Icon name="lucide:shield-check" class="w-3.5 h-3.5 text-[#1e4e57]" />
                Đang tạm giữ tiền
              </span>
              <!-- Tag hiển thị khi đã giải ngân về ví chủ xe -->
              <span v-if="isOwner && !trip.payment_held && trip.status === 4"
                class="px-4 py-1.5 rounded-full text-xs font-bold border shadow-sm bg-emerald-50 text-emerald-600 border-emerald-200/60 flex items-center gap-1.5">
                <Icon name="lucide:wallet" class="w-3.5 h-3.5 text-emerald-600" />
                Đã về ví chủ xe
              </span>
              <!-- Song song status gia hạn nếu có -->
              <span v-if="trip.latest_extension && trip.latest_extension.status !== 0"
                class="px-4 py-1.5 rounded-full text-xs font-bold border shadow-sm flex items-center gap-1.5"
                :class="extensionStatusClass(trip.latest_extension.status)">
                <Icon name="lucide:calendar-clock" class="w-3.5 h-3.5" />
                {{ extensionStatusLabel(trip.latest_extension.status) }}
              </span>
              <span
                class="px-3 py-1.5 rounded-full text-xs font-bold bg-[#1e4e57]/10 text-[#1e4e57] flex items-center gap-1.5">
                <Icon :name="trip.trip_type === 0 ? 'lucide:calendar' : 'lucide:route'" class="w-3.5 h-3.5" />
                {{ trip.trip_type === 0 ? 'Thuê theo ngày' : 'Thuê theo km' }}
              </span>
            </div>
          </div>

          <!-- Time & Locations -->
          <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm space-y-6">
            <h2 class="text-base font-bold text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
              <Icon name="lucide:clock" class="text-[#1e4e57] w-5 h-5" />
              Thời gian & Địa điểm
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                <p
                  class="text-[10px] text-slate-400 font-bold uppercase tracking-wider flex items-center gap-1.5 mb-1.5">
                  <span class="w-2 h-2 rounded-full bg-[#1e4e57]"></span> Nhận xe
                </p>
                <p class="text-base font-bold text-slate-800">{{ formatDate(trip.start_at) }}</p>
              </div>
              <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                <p
                  class="text-[10px] text-slate-400 font-bold uppercase tracking-wider flex items-center gap-1.5 mb-1.5">
                  <span class="w-2 h-2 rounded-full bg-rose-500"></span> Trả xe
                </p>
                <p class="text-base font-bold text-slate-800">{{ formatDate(trip.end_at) }}</p>
              </div>
            </div>

            <div
              class="flex items-center justify-between rounded-2xl bg-[#1e4e57]/5 px-4 py-3.5 border border-[#1e4e57]/10">
              <span class="text-sm font-semibold text-slate-600">Tổng thời gian thuê</span>
              <span class="text-sm font-extrabold text-[#1e4e57]">{{ duration(trip.start_at, trip.end_at) }}</span>
            </div>

            <!-- Locations -->
            <div class="space-y-4 pt-2">
              <div class="flex gap-3">
                <div class="flex flex-col items-center shrink-0">
                  <div
                    class="w-7 h-7 rounded-full bg-[#1e4e57]/10 text-[#1e4e57] flex items-center justify-center text-xs border border-[#1e4e57]/20 font-bold">
                    1</div>
                  <div class="w-0.5 flex-grow border-l-2 border-dashed border-slate-200 my-1"></div>
                </div>
                <div>
                  <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Địa điểm nhận xe</h4>
                  <p class="text-sm font-semibold text-slate-800 mt-0.5">
                    {{ trip.car?.car_location?.address || 'Chưa cập nhật' }}
                  </p>
                </div>
              </div>
              <div class="flex gap-3">
                <div class="flex flex-col items-center shrink-0">
                  <div
                    class="w-7 h-7 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-xs border border-rose-100 font-bold">
                    2</div>
                </div>
                <div>
                  <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Địa chỉ giao xe (nếu có)</h4>
                  <p class="text-sm font-semibold text-slate-800 mt-0.5">
                    {{ trip.delivery_address || 'Nhận xe tại địa điểm đăng ký của xe' }}
                  </p>
                  <p v-if="trip.delivery_location" class="text-xs text-slate-400 font-medium mt-0.5">{{
                    trip.delivery_location }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Billing Info -->
          <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-slate-800 flex items-center gap-2 border-b border-slate-100 pb-3">
              <Icon name="lucide:receipt" class="text-[#1e4e57] w-5 h-5" />
              Thông tin thanh toán
            </h2>

            <div class="space-y-2.5 text-sm">
              <div class="flex justify-between text-slate-500 font-medium">
                <span>Đơn giá thuê</span>
                <span>{{ formatCurrency(trip.car?.unit_price) }} / {{ trip.trip_type === 0 ? 'ngày' : 'km' }}</span>
              </div>
              <div class="flex justify-between text-slate-500 font-medium">
                <span>Tổng chi phí ban đầu</span>
                <span>{{ formatCurrency(trip.cost - totalExtensionPaid) }}</span>
              </div>
              <div v-if="totalExtensionPaid > 0" class="flex justify-between text-slate-500 font-medium">
                <span>Phí gia hạn xe</span>
                <span class="text-[#1e4e57] font-bold">+{{ formatCurrency(totalExtensionPaid) }}</span>
              </div>
              <div v-if="trip.discount_amount > 0" class="flex justify-between text-rose-500 font-bold">
                <span>Số tiền được giảm giá</span>
                <span>-{{ formatCurrency(trip.discount_amount) }}</span>
              </div>
              <div class="border-t border-slate-100 pt-3 flex justify-between items-baseline">
                <span class="text-base font-bold text-slate-800">Thành tiền</span>
                <span class="text-2xl font-black text-[#1e4e57]">{{ formatCurrency(trip.cost - trip.discount_amount)
                }}</span>
              </div>

              <!-- Payment status and deposit details -->
              <!-- When trip is ongoing/complete: always show 100% paid -->
              <div v-if="trip.status === 3 || trip.status === 4 || trip.status === 7 || trip.status === 8"
                class="pt-3 border-t border-dashed border-slate-100 space-y-2 animate-fade-in">
                <div class="flex justify-between items-center text-sm font-medium">
                  <span class="text-slate-500">Đã thanh toán:</span>
                  <span
                    class="px-2.5 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-xs font-bold flex items-center gap-1">
                    <Icon name="lucide:check-circle" class="w-3.5 h-3.5" />
                    100% ({{ formatCurrency(trip.cost - trip.discount_amount) }})
                  </span>
                </div>
                <div
                  class="flex items-center gap-1.5 text-[11px] text-emerald-600 font-semibold mt-1 bg-emerald-50/60 border border-emerald-100 rounded-xl px-2.5 py-1.5">
                  <Icon name="lucide:shield-check" class="w-3.5 h-3.5 shrink-0" />
                  Thanh toán đầy đủ, chuyến đi đã được xác nhận
                </div>
              </div>

              <!-- When trip is cancelled -->
              <div v-else-if="trip.status === 5 || trip.status === 6"
                class="pt-3 border-t border-dashed border-slate-100 space-y-2.5 animate-fade-in">
                <div class="flex justify-between items-center text-sm font-medium">
                  <span class="text-slate-500 font-semibold text-rose-600">Trạng thái:</span>
                  <span
                    class="px-2.5 py-0.5 bg-rose-50 text-rose-600 border border-rose-200 rounded-lg text-xs font-bold flex items-center gap-1">
                    <Icon name="lucide:ban" class="w-3.5 h-3.5" />
                    Chuyến đi đã bị hủy
                  </span>
                </div>

                <div v-if="cancellationDetails"
                  class="bg-rose-50/40 border border-rose-100/60 rounded-2xl p-3.5 space-y-2 text-xs">
                  <div class="flex justify-between items-center text-slate-500 font-medium">
                    <span>Số tiền đã thanh toán:</span>
                    <span class="font-bold text-slate-800">{{ formatCurrency(cancellationDetails.totalPaid) }}</span>
                  </div>
                  <div class="flex justify-between items-center text-slate-500 font-medium">
                    <span>Chính sách áp dụng:</span>
                    <span class="font-semibold text-slate-700 text-right max-w-[180px] leading-relaxed">{{
                      cancellationDetails.policyDesc }}</span>
                  </div>
                  <div class="flex justify-between items-center text-slate-500 font-medium">
                    <span>Phí hủy chuyến ({{ cancellationDetails.feePercent }}%):</span>
                    <span class="font-bold text-rose-600">{{ formatCurrency(cancellationDetails.cancellationFee)
                    }}</span>
                  </div>

                  <div
                    class="border-t border-dashed border-rose-200/50 pt-2 flex justify-between items-center text-xs font-medium">
                    <span class="text-slate-500">Hoàn trả khách thuê:</span>
                    <span class="font-black text-emerald-600 text-sm">{{
                      formatCurrency(cancellationDetails.refundAmount) }} (Vào ví)</span>
                  </div>
                  <div class="flex justify-between items-center text-[10px] font-medium text-slate-400">
                    <span>Đền bù chủ xe:</span>
                    <span>{{ formatCurrency(cancellationDetails.compensationFee) }} (Vào ví)</span>
                  </div>
                </div>
              </div>

              <!-- When trip has not started yet but has partial payment -->
              <div v-else-if="trip.transactions && trip.transactions.length > 0"
                class="pt-3 border-t border-dashed border-slate-100 space-y-2 animate-fade-in">
                <div class="flex justify-between items-center text-sm font-medium">
                  <span class="text-slate-500">Đã đặt cọc / thanh toán:</span>
                  <span
                    class="px-2.5 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-xs font-bold flex items-center gap-1">
                    <Icon name="lucide:check-circle" class="w-3.5 h-3.5" />
                    {{ paidPercent }}% ({{ formatCurrency(totalPaid) }})
                  </span>
                </div>
                <div v-if="paidPercent < 95"
                  class="flex justify-between text-xs font-semibold leading-normal bg-amber-50/50 border border-amber-100 p-2.5 rounded-xl mt-1 text-slate-500">
                  <span class="text-amber-800">Còn lại cần trả chủ xe:</span>
                  <span class="text-amber-900 font-bold">{{ formatCurrency((trip.cost - trip.discount_amount) -
                    totalPaid) }}</span>
                </div>
              </div>

              <!-- Thông báo về tạm giữ/giải ngân tiền dành cho chủ xe -->
              <div v-if="isOwner && trip.owner_payment_note"
                class="mt-4 pt-3 border-t border-dashed border-slate-100 animate-fade-in">
                <div
                  class="flex items-start gap-2.5 text-xs font-medium bg-[#1e4e57]/5 border border-[#1e4e57]/15 rounded-2xl p-4 text-[#1e4e57] leading-relaxed">
                  <Icon name="lucide:info" class="w-4.5 h-4.5 shrink-0 mt-0.5 text-[#1e4e57]" />
                  <div>
                    <span class="font-bold block mb-0.5">Thông tin tiền thuê:</span>
                    <span>{{ trip.owner_payment_note }}</span>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- Right 1 column: Car Details & Action Control -->
        <div class="space-y-6">

          <!-- Car Card -->
          <div class="bg-white rounded-3xl border border-slate-200/60 overflow-hidden shadow-sm">
            <NuxtLink :to="`/vehicles/${trip.car?.id || trip.car_id}`" class="block relative h-48 bg-slate-100 group">
              <img :src="carThumbnail(trip.car)" :alt="trip.car?.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
              <span
                class="absolute top-4 right-4 bg-slate-900/75 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm border border-white/10 backdrop-blur-sm z-10">
                {{ trip.car?.license_plate }}
              </span>
              <div
                class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <span
                  class="text-white text-[10px] bg-black/60 px-3 py-1.5 rounded-xl font-bold flex items-center gap-1 border border-white/10 backdrop-blur-sm">
                  <Icon name="lucide:eye" class="w-3.5 h-3.5" />
                  Xem chi tiết xe
                </span>
              </div>
            </NuxtLink>

            <div class="p-6 space-y-4">
              <div>
                <span class="text-[10px] font-black uppercase text-[#1e4e57] tracking-wider">
                  {{ trip.car?.car_brand?.brand_name || 'Hãng xe' }} • {{ trip.car?.car_type?.type_name || 'Dòng xe' }}
                </span>
                <NuxtLink :to="`/vehicles/${trip.car?.id || trip.car_id}`" class="block group mt-0.5">
                  <h3
                    class="text-lg font-black text-slate-900 group-hover:text-[#1e4e57] transition-colors leading-snug">
                    {{ trip.car?.name }}
                  </h3>
                </NuxtLink>
              </div>

              <!-- Specs Grid -->
              <div
                class="grid grid-cols-2 gap-3.5 text-xs font-semibold text-slate-600 border-t border-b border-slate-100 py-4">
                <div class="flex items-center gap-2">
                  <Icon name="lucide:armchair" class="text-slate-400 w-4 h-4" />
                  <span>{{ trip.car?.seat_count || 4 }} Ghế</span>
                </div>
                <div class="flex items-center gap-2">
                  <Icon name="lucide:cog" class="text-slate-400 w-4 h-4" />
                  <span>{{ transmissionLabel }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <Icon name="lucide:fuel" class="text-slate-400 w-4 h-4" />
                  <span>{{ fuelTypeLabel }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <Icon name="lucide:calendar" class="text-slate-400 w-4 h-4" />
                  <span>Năm {{ trip.car?.manufacture_year || '—' }}</span>
                </div>
              </div>

              <!-- Rental Terms -->
              <div v-if="trip.car?.rental_terms" class="space-y-1.5">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Quy định thuê xe</h4>
                <p
                  class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 leading-relaxed max-h-32 overflow-y-auto">
                  {{ trip.car.rental_terms }}
                </p>
              </div>
            </div>
          </div>

          <!-- Owner/Renter Info Card -->
          <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-black text-slate-800 border-b border-slate-100 pb-2.5 uppercase tracking-wider">
              {{ isOwner ? 'Thông tin khách thuê' : 'Thông tin chủ xe' }}
            </h3>
            <div class="flex items-center gap-3">
              <NuxtLink
                :to="'/profile/' + (isOwner ? trip.user?.id : trip.car?.owner?.id) + (isOwner ? '?role=renter' : '?role=owner')"
                class="flex items-center gap-3 w-full group">
                <img
                  :src="(isOwner ? trip.user?.avatar : trip.car?.owner?.avatar) || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=150'"
                  alt="Avatar" class="w-12 h-12 rounded-2xl object-cover border border-slate-100 shadow-sm shrink-0" />
                <div class="min-w-0 flex-1">
                  <p class="text-sm font-bold text-slate-800 truncate group-hover:text-brand-primary transition-colors">
                    {{ isOwner ? (trip.user?.name || 'Khách hàng') : (trip.car?.owner?.name || 'Chưa cập nhật') }}
                  </p>
                  <p class="text-xs text-slate-400 font-medium">
                    {{ isOwner ? 'Khách thuê xe' : 'Chủ sở hữu xe' }}
                  </p>
                </div>
              </NuxtLink>
            </div>
            <div class="space-y-1.5 text-xs text-slate-500 font-semibold pt-1">
              <p class="flex items-center gap-2">
                <Icon name="lucide:phone" class="text-slate-400 w-4 h-4" />
                {{
                  isOwner ? (trip.user?.phone || 'Chưa cập nhật SĐT') : (trip.car?.owner?.phone || 'Chưa cập nhật SĐT')
                }}
              </p>
              <p class="flex items-center gap-2">
                <Icon name="lucide:mail" class="text-slate-400 w-4 h-4" />
                {{
                  isOwner ? (trip.user?.email || 'Chưa cập nhật Email') :
                    (trip.car?.owner?.email || 'Chưa cập nhật Email')
                }}
              </p>
            </div>

            <!-- Chat Button with Nuxt Icon (Chỉ hiển thị khi chuyến đi ĐÃ ĐƯỢC XÁC NHẬN trở lên: status !== 0 - Pending, !== 5,6 - Cancelled) -->
            <NuxtLink
              v-if="trip.status !== 0 && trip.status !== 5 && trip.status !== 6"
              :to="'/chats?trip_id=' + trip.id + '&partner_id=' + (isOwner ? trip.user?.id : trip.car?.owner?.id)"
              class="mt-3 flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-2xl text-xs font-bold text-[#1e4e57] bg-[#1e4e57]/10 hover:bg-[#1e4e57] hover:text-white transition-all duration-200 shadow-xs border border-[#1e4e57]/20 group/chatbtn"
              title="Tạo cuộc trò chuyện"
            >
              <Icon name="lucide:message-square-more" class="w-4 h-4 text-[#1e4e57] group-hover/chatbtn:text-white transition-colors" />
              <span>Nhắn tin với {{ isOwner ? 'khách thuê' : 'chủ xe' }}</span>
            </NuxtLink>
          </div>

        </div>
      </div>

      <!-- Trip Status Transition Card (Full-width underneath the two-column grid) -->
      <div v-if="trip" class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm space-y-4 mt-8">
        <h3
          class="text-sm font-black text-slate-800 border-b border-slate-100 pb-2.5 uppercase tracking-wider flex items-center gap-2">
          <Icon name="lucide:camera" class="text-[#1e4e57] w-4 h-4" />
          Trạng thái & Hình ảnh xe
        </h3>

        <!-- CASE 1: Trip is Confirmed (status = 2) - Allow uploading and starting (Owner only) or show message (Renter) -->
        <div v-if="trip.status === 2" class="space-y-4">
          <!-- If owner of the car -->
          <div v-if="isOwner" class="space-y-4 animate-fade-in">
            <p class="text-xs text-slate-500 leading-relaxed font-medium">
              Vui lòng chụp và tải lên hình ảnh hiện trạng của xe trước khi bàn bàn giao xe cho khách thuê và khởi hành.
              Đây là cơ sở để đối chiếu khi nhận lại xe.
            </p>

            <!-- Premium Cloud ImageUpload Component -->
            <ImageUpload ref="imageUploadRef" v-model="uploadedImages" :max-files="5" />

            <div class="flex flex-col gap-3">
              <!-- Start Trip Button -->
              <button @click="handleStartTrip" :disabled="uploadedImages.length === 0 || uploading"
                class="w-full py-3 px-4 rounded-xl text-xs font-bold text-white transition-all flex items-center justify-center gap-2 shadow-md transform"
                :class="uploadedImages.length > 0 && !uploading
                  ? 'bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] shadow-emerald-600/10 cursor-pointer'
                  : 'bg-slate-300 shadow-none cursor-not-allowed'">
                <span v-if="uploading" class="flex items-center gap-1.5">
                  <Icon name="lucide:loader-2" class="animate-spin w-4 h-4" />
                  Đang khởi hành...
                </span>
                <span v-else class="flex items-center gap-1.5">
                  <Icon name="lucide:play" class="w-4 h-4" />
                  Xác nhận bắt đầu chuyến đi
                </span>
              </button>

              <!-- Cancel Trip Button -->
              <button @click="handleCancelTrip" :disabled="processingAction"
                class="w-full py-3 px-4 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-rose-600/10">
                <Icon v-if="processingAction" name="lucide:loader-2" class="animate-spin w-4 h-4" />
                <Icon v-else name="lucide:ban" class="w-4 h-4" />
                <span>Hủy chuyến đi</span>
              </button>
            </div>
          </div>

          <!-- If renter of the car -->
          <div v-else class="space-y-4 animate-fade-in">
            <div class="bg-sky-50 border border-sky-100 rounded-2xl p-4 text-xs text-sky-850 flex items-start gap-2.5">
              <Icon name="lucide:info" class="mt-0.5 w-4 h-4 shrink-0 text-sky-600" />
              <div class="leading-relaxed">
                <p class="font-bold">Đang chờ chủ xe bàn giao xe và bắt đầu chuyến đi</p>
                <p class="mt-0.5 font-medium opacity-90">
                  Chủ xe sẽ kiểm tra hiện trạng xe và chụp ảnh tải lên hệ thống trước khi bắt đầu chuyến đi của bạn. Vui
                  lòng liên hệ với chủ xe nếu cần thêm thông tin.
                </p>
              </div>
            </div>

            <!-- Cancel Trip Button -->
            <button @click="handleCancelTrip" :disabled="processingAction"
              class="w-full py-3 px-4 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-rose-600/10">
              <Icon v-if="processingAction" name="lucide:loader-2" class="animate-spin w-4 h-4" />
              <Icon v-else name="lucide:ban" class="w-4 h-4" />
              <span>Hủy chuyến đi</span>
            </button>
          </div>
        </div>

        <!-- CASE 2: Trip is Ongoing (status = 3), Completed (status = 4), Waiting Extension (status = 7) or Waiting Return (status = 8) - Display uploaded photos -->
        <div v-else-if="trip.status === 3 || trip.status === 4 || trip.status === 7 || trip.status === 8"
          class="space-y-4">
          <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider text-[10px] flex items-center gap-1">
            <Icon name="lucide:check-circle" class="text-emerald-500 w-4 h-4" />
            Ảnh xe trước chuyến đi đã tải lên
          </p>

          <div v-if="beforeTripImages.length > 0"
            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <div v-for="(imgUrl, index) in beforeTripImages" :key="index"
              class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm aspect-video bg-slate-50 group relative cursor-pointer"
              @click="openImageModal(imgUrl)">
              <img :src="imgUrl"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                alt="Car state before trip" />
              <div
                class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <span class="text-white text-[10px] bg-black/60 px-2 py-1 rounded-md font-bold">Xem ảnh lớn</span>
              </div>
            </div>
          </div>
          <div v-else
            class="text-center py-6 text-xs text-slate-400 border border-dashed rounded-2xl bg-slate-50 font-medium">
            Không tìm thấy ảnh xe trước chuyến đi
          </div>

          <!-- Display after trip photos if completed or available -->
          <div v-if="trip.status === 4 || afterTripImages.length > 0" class="space-y-4 pt-4 border-t border-slate-100">
            <p
              class="text-xs text-slate-500 font-semibold uppercase tracking-wider text-[10px] flex items-center gap-1">
              <Icon name="lucide:check-circle" class="text-emerald-500 w-4 h-4" />
              Ảnh xe sau chuyến đi đã tải lên
            </p>

            <div v-if="afterTripImages.length > 0"
              class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
              <div v-for="(imgUrl, index) in afterTripImages" :key="index"
                class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm aspect-video bg-slate-50 group relative cursor-pointer"
                @click="openImageModal(imgUrl)">
                <img :src="imgUrl"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  alt="Car state after trip" />
                <div
                  class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                  <span class="text-white text-[10px] bg-black/60 px-2 py-1 rounded-md font-bold">Xem ảnh lớn</span>
                </div>
              </div>
            </div>
            <div v-else
              class="text-center py-6 text-xs text-slate-400 border border-dashed rounded-2xl bg-slate-50 font-medium">
              Không tìm thấy ảnh xe sau chuyến đi
            </div>
          </div>

          <!-- Trip is ongoing (status = 3) -->
          <div v-if="trip.status === 3" class="space-y-3">
            <!-- Box thông tin chuyến đi đang diễn ra -->
            <div
              class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-xs text-amber-800 flex items-start gap-2.5">
              <Icon name="lucide:info" class="mt-0.5 w-4 h-4 shrink-0 text-amber-600" />
              <div class="leading-relaxed">
                <p class="font-bold">Đang trong chuyến hành trình</p>
                <p class="mt-0.5 font-medium opacity-90">
                  Chuyến đi đang diễn ra an toàn. Vui lòng liên hệ với chủ xe nếu có bất kỳ sự cố hay phát sinh nào
                  trong quá trình di chuyển.
                </p>
              </div>
            </div>

            <div v-if="!isOwner" class="flex flex-col sm:flex-row gap-3">
              <!-- Nếu có yêu cầu gia hạn chờ thanh toán -->
              <button v-if="trip.latest_extension && trip.latest_extension.status === 2" @click="openPayExtensionModal"
                class="flex-1 py-3 px-4 rounded-xl text-xs font-bold text-white transition-all bg-amber-500 hover:bg-amber-600 active:scale-[0.98] cursor-pointer shadow-md shadow-amber-500/10 flex items-center justify-center gap-1.5">
                <Icon name="lucide:credit-card" class="w-4 h-4" />
                Thanh toán phí gia hạn
              </button>

              <button v-else-if="!trip.latest_extension || trip.latest_extension.status === 0"
                @click="openExtensionModal"
                class="flex-1 py-3 px-4 rounded-xl text-xs font-bold text-white transition-all bg-[#1e4e57] hover:bg-[#286874] active:scale-[0.98] cursor-pointer shadow-md shadow-[#1e4e57]/10 flex items-center justify-center gap-1.5">
                <Icon name="lucide:calendar-plus" class="w-4 h-4" />
                Gia hạn chuyến đi
              </button>

              <button @click="handleReturnRequest" :disabled="returningTrip"
                class="flex-1 py-3 px-4 rounded-xl text-xs font-bold text-white transition-all bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] cursor-pointer shadow-md shadow-emerald-600/10 flex items-center justify-center gap-1.5">
                <span v-if="returningTrip" class="flex items-center gap-1.5">
                  <Icon name="lucide:loader-2" class="animate-spin w-4 h-4" />
                  Đang xử lý...
                </span>
                <span v-else class="flex items-center gap-1.5">
                  <Icon name="lucide:log-out" class="w-4 h-4" />
                  Trả xe
                </span>
              </button>
            </div>

            <!-- Thông báo yêu cầu gia hạn đã được duyệt và cần thanh toán -->
            <div v-if="!isOwner && trip.latest_extension && trip.latest_extension.status === 2"
              class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-xs text-amber-800 flex items-start gap-2.5 mt-3 animate-fade-in">
              <Icon name="lucide:info" class="mt-0.5 w-4 h-4 shrink-0 text-amber-650" />
              <div class="leading-relaxed">
                <p class="font-bold">Yêu cầu gia hạn đã được phê duyệt</p>
                <p class="mt-0.5 font-medium opacity-90">
                  Chủ xe đã đồng ý yêu cầu gia hạn của bạn. Vui lòng thanh toán phí gia hạn để hoàn tất quá trình này.
                </p>
              </div>
            </div>

            <!-- Yêu cầu gia hạn đang chờ duyệt (Hiển thị cho chủ xe) -->
            <div v-if="isOwner && trip.latest_extension && trip.latest_extension.status === 1"
              class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 text-xs text-indigo-900 space-y-3 mt-3 animate-fade-in">
              <div class="flex items-start gap-2.5">
                <Icon name="lucide:calendar-clock" class="mt-0.5 w-4.5 h-4.5 shrink-0 text-indigo-650" />
                <div class="leading-relaxed flex-grow">
                  <p class="font-bold">Khách hàng yêu cầu gia hạn chuyến đi</p>
                  <p class="mt-0.5 font-medium opacity-90">
                    Khách hàng đề xuất gia hạn ngày trả xe mới và đang chờ bạn phê duyệt.
                  </p>
                  <div class="mt-2.5 pt-2 border-t border-indigo-200/60 flex flex-col sm:flex-row gap-4 text-[11px]">
                    <div>
                      <span class="text-slate-500 font-semibold block uppercase tracking-wider text-[9px]">Ngày trả xe
                        mới:</span>
                      <span class="font-bold text-indigo-950 text-xs">{{ formatDate(trip.latest_extension.end_date)
                      }}</span>
                    </div>
                    <div>
                      <span class="text-slate-500 font-semibold block uppercase tracking-wider text-[9px]">Phí gia hạn
                        dự kiến:</span>
                      <span class="font-bold text-[#1e4e57] text-xs">{{
                        formatCurrency(trip.latest_extension.extension_amount) }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flex gap-3 pt-2">
                <button @click="handleApproveExtension" :disabled="processingAction"
                  class="flex-1 py-2 px-3 rounded-xl text-xs font-bold text-white bg-[#1e4e57] hover:bg-[#286874] active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-[#1e4e57]/10">
                  <Icon v-if="processingAction" name="lucide:loader-2" class="animate-spin w-3.5 h-3.5" />
                  <span>Đồng ý gia hạn</span>
                </button>
                <button @click="openRejectExtensionDialog" :disabled="processingAction"
                  class="flex-1 py-2 px-3 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-rose-600/10">
                  Từ chối gia hạn
                </button>
              </div>
            </div>
          </div>

          <!-- Trip is completed (status = 4) -->
          <div v-else-if="trip.status === 4"
            class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 text-xs text-emerald-800 flex flex-col gap-3 w-full">
            <div class="flex items-start gap-2.5">
              <Icon name="lucide:check-circle" class="mt-0.5 w-4 h-4 shrink-0 text-emerald-600" />
              <div class="leading-relaxed">
                <p class="font-bold">Chuyến đi đã kết thúc</p>
                <p class="mt-0.5 font-medium opacity-90">
                  Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi! Chuyến đi đã được hoàn thành thành công.
                </p>
              </div>
            </div>

            <!-- Review section for renter -->
            <div class="mt-1 pt-3 border-t border-emerald-250/30">
              <div v-if="renterReview"
                class="bg-white border border-emerald-100 rounded-xl p-3 text-slate-700 space-y-1.5 shadow-sm">
                <div class="flex items-center gap-1.5">
                  <span class="font-bold text-xs text-slate-800">Đánh giá của bạn về chủ xe:</span>
                  <div class="flex items-center gap-0.5">
                    <Icon v-for="star in 5" :key="star" name="heroicons:star-solid" class="w-3.5 h-3.5"
                      :class="star <= renterReview.rating ? 'text-amber-400' : 'text-slate-200'" />
                  </div>
                </div>
                <p class="text-slate-600 italic text-[11px] font-medium" v-if="renterReview.comment">
                  "{{ renterReview.comment }}"
                </p>
                <p class="text-slate-400 text-[10px]" v-else>Không có bình luận.</p>
              </div>

              <button v-else-if="!isOwner" @click="openReviewModal"
                class="py-2 px-4 rounded-xl text-xs font-bold text-white transition-all bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] cursor-pointer shadow-md shadow-emerald-600/10 flex items-center gap-1.5 w-fit">
                <Icon name="lucide:star" class="w-4 h-4" />
                Đánh giá chủ xe & chuyến đi
              </button>
            </div>
          </div>

          <!-- Trip is waiting for extension approval (status = 7) -->
          <div v-else-if="trip.status === 7"
            class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 text-xs text-indigo-900 flex flex-col gap-3">
            <div class="flex items-start gap-2.5">
              <Icon name="lucide:clock" class="mt-0.5 w-4 h-4 shrink-0 text-indigo-650" />
              <div class="leading-relaxed flex-grow">
                <p class="font-bold">Đang chờ chủ xe duyệt gia hạn</p>
                <p class="mt-0.5 font-medium opacity-90">
                  Yêu cầu gia hạn thêm ngày đang chờ chủ xe phê duyệt.
                </p>
                <div v-if="trip.latest_extension?.end_date"
                  class="mt-2.5 pt-2 border-t border-indigo-200/60 flex flex-col sm:flex-row gap-4">
                  <div>
                    <span class="text-slate-500 font-semibold block text-[10px] uppercase tracking-wider">Ngày trả xe đề
                      xuất:</span>
                    <span class="font-bold text-sm text-indigo-950">{{ formatDate(trip.latest_extension.end_date)
                    }}</span>
                  </div>
                  <div v-if="trip.latest_extension?.extension_amount">
                    <span class="text-slate-500 font-semibold block text-[10px] uppercase tracking-wider">Phí gia hạn dự
                      kiến:</span>
                    <span class="font-bold text-sm text-[#1e4e57]">{{
                      formatCurrency(trip.latest_extension.extension_amount) }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="isOwner" class="flex gap-3 pt-2">
              <button @click="handleApproveExtension" :disabled="processingAction"
                class="flex-1 py-2 px-3 rounded-xl text-xs font-bold text-white bg-[#1e4e57] hover:bg-[#286874] active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-[#1e4e57]/10">
                <Icon v-if="processingAction" name="lucide:loader-2" class="animate-spin w-3.5 h-3.5" />
                <span>Đồng ý gia hạn</span>
              </button>
              <button @click="openRejectExtensionDialog" :disabled="processingAction"
                class="flex-1 py-2 px-3 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-rose-600/10">
                Từ chối gia hạn
              </button>
            </div>
          </div>

          <!-- Trip is waiting for return (status = 8) -->
          <div v-else-if="trip.status === 8" class="space-y-4">
            <!-- If owner of the car -->
            <div v-if="isOwner" class="space-y-4">
              <div
                class="bg-sky-50 border border-sky-100 rounded-2xl p-4 text-xs text-sky-850 flex items-start gap-2.5">
                <Icon name="lucide:info" class="mt-0.5 w-4 h-4 shrink-0 text-sky-600" />
                <div class="leading-relaxed">
                  <p class="font-bold">Đang chờ bạn xác nhận hoàn thành và nhận lại xe</p>
                  <p class="mt-0.5 font-medium opacity-90">
                    Vui lòng chụp và tải lên hình ảnh hiện trạng của xe sau khi nhận lại xe, sau đó bấm Xác nhận hoàn
                    thành chuyến xe.
                  </p>
                </div>
              </div>

              <!-- Premium Cloud ImageUpload Component for Post-trip -->
              <ImageUpload ref="postTripImageUploadRef" v-model="postTripUploadedImages" :max-files="5" />

              <!-- Complete Trip Button -->
              <button @click="handleCompleteTrip" :disabled="postTripUploadedImages.length === 0 || completingTrip"
                class="w-full py-3 px-4 rounded-xl text-xs font-bold text-white transition-all flex items-center justify-center gap-2 shadow-md transform"
                :class="postTripUploadedImages.length > 0 && !completingTrip
                  ? 'bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] shadow-emerald-600/10 cursor-pointer'
                  : 'bg-slate-300 shadow-none cursor-not-allowed'">
                <span v-if="completingTrip" class="flex items-center gap-1.5">
                  <Icon name="lucide:loader-2" class="animate-spin w-4 h-4" />
                  Đang xử lý hoàn thành...
                </span>
                <span v-else class="flex items-center gap-1.5">
                  <Icon name="lucide:check" class="w-4 h-4" />
                  Xác nhận hoàn thành chuyến xe
                </span>
              </button>
            </div>

            <!-- If renter of the car -->
            <div v-else
              class="bg-sky-50 border border-sky-100 rounded-2xl p-4 text-xs text-sky-850 flex items-start gap-2.5">
              <Icon name="lucide:clock" class="mt-0.5 w-4 h-4 shrink-0 text-sky-650" />
              <div class="leading-relaxed flex-grow">
                <p class="font-bold">Đang chờ chủ xe xác nhận trả xe</p>
                <p class="mt-0.5 font-medium opacity-90">
                  Chuyến đi đã kết thúc. Vui lòng chờ chủ xe kiểm tra hiện trạng xe và bấm xác nhận hoàn thành chuyến
                  xe.
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- CASE 3: Trip is pending, waiting payment or cancelled -->
        <div v-else
          class="text-center py-6 text-xs text-slate-400 border border-dashed rounded-2xl bg-slate-50 font-semibold p-4">
          <template v-if="trip.status === 0">
            <Icon name="lucide:hourglass" class="text-amber-500 mb-2 mx-auto block w-6 h-6" />
            <p class="mb-3 text-slate-600 font-medium">Chuyến đi đang chờ chủ xe duyệt.</p>
            <div v-if="isOwner" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto pt-2">
              <button @click="handleConfirmTrip" :disabled="processingAction"
                class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-[#1e4e57] hover:bg-[#286874] active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-[#1e4e57]/10">
                <Icon v-if="processingAction" name="lucide:loader-2" class="animate-spin w-4 h-4" />
                <span v-else>Đồng ý cho thuê</span>
              </button>
              <button @click="openRejectTripDialog" :disabled="processingAction"
                class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-rose-600/10">
                Từ chối cho thuê
              </button>
            </div>
            <div v-else class="flex justify-center pt-2">
              <button @click="handleCancelTrip" :disabled="processingAction"
                class="py-2 px-6 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-rose-600/10">
                <Icon v-if="processingAction" name="lucide:loader-2" class="animate-spin w-4 h-4" />
                <Icon v-else name="lucide:ban" class="w-4 h-4" />
                <span>Hủy chuyến đi</span>
              </button>
            </div>
          </template>
          <template v-else-if="trip.status === 1">
            <Icon name="lucide:credit-card" class="text-sky-500 mb-2 mx-auto block w-6 h-6" />
            <p class="mb-3 text-slate-600 font-medium">Chuyến đi đang chờ thanh toán đặt cọc.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-3 max-w-md mx-auto pt-1">
              <nuxt-link v-if="!isOwner" :to="`/payment?trip_id=${trip.id}`"
                class="flex-1 py-2.5 px-6 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-sky-600/10 cursor-pointer flex items-center justify-center gap-1.5 active:scale-[0.98]">
                <Icon name="lucide:wallet" class="w-4 h-4" />
                Thanh toán ngay
              </nuxt-link>
              <button @click="handleCancelTrip" :disabled="processingAction"
                class="flex-1 py-2.5 px-6 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-rose-600/10">
                <Icon v-if="processingAction" name="lucide:loader-2" class="animate-spin w-4 h-4" />
                <Icon v-else name="lucide:ban" class="w-4 h-4" />
                <span>Hủy chuyến đi</span>
              </button>
            </div>
          </template>
          <template v-else-if="trip.status === 5 || trip.status === 6">
            <Icon name="lucide:ban" class="text-rose-500 mb-2 mx-auto block w-6 h-6" />
            Chuyến đi đã bị hủy.
          </template>
          <template v-else>
            Chuyến đi không thể bắt đầu lúc này.
          </template>
        </div>
      </div>

    </div>

    <!-- Image Preview Modal -->
    <Teleport to="body">
      <Transition name="fade">
        <div v-if="showPreviewModal"
          class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[150] flex items-center justify-center p-4">
          <div @click="showPreviewModal = false" class="absolute inset-0 cursor-zoom-out"></div>
          <div
            class="relative max-w-4xl w-full bg-transparent rounded-3xl overflow-hidden shadow-2xl flex flex-col items-center">
            <button @click="showPreviewModal = false"
              class="absolute top-4 right-4 bg-black/50 hover:bg-black/80 text-white p-2.5 rounded-full transition-colors z-10 shadow-md flex items-center justify-center">
              <Icon name="lucide:x" class="w-5 h-5" />
            </button>
            <img :src="previewImageUrl"
              class="max-h-[85vh] max-w-full object-contain rounded-2xl border border-white/10 shadow-2xl" />
          </div>
        </div>
      </Transition>
    </Teleport>



    <!-- Extension Modal (Calendar UI & Effects matching Homepage Filter) -->
    <Teleport to="body">
      <Transition name="fade">
        <div v-if="showExtensionModal"
          class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[140] flex items-center justify-center p-4">
          <div @click="showExtensionModal = false" class="absolute inset-0 cursor-pointer"></div>
          <div
            class="relative w-full max-w-lg bg-white rounded-3xl overflow-hidden shadow-2xl p-6 border border-slate-100 animate-scale-up max-h-[90vh] overflow-y-auto"
            @click.stop>

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
              <div>
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                  <Icon name="lucide:calendar-plus" class="w-5 h-5 text-[#1e4e57]" />
                  Gia hạn thời gian thuê xe
                </h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Chọn ngày và giờ trả xe mới trên lịch bên dưới</p>
              </div>
              <button @click="showExtensionModal = false"
                class="p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition">
                <Icon name="lucide:x" class="w-4 h-4" />
              </button>
            </div>

            <!-- Content -->
            <div class="space-y-5 text-xs">
              <!-- Current End Date Info -->
              <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 flex items-center justify-between">
                <div>
                  <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Thời gian trả xe
                    hiện
                    tại</span>
                  <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ formatDate(trip?.end_at) }}</span>
                </div>
                <div class="text-right">
                  <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Đơn giá thuê</span>
                  <span class="font-bold text-[#1e4e57] text-sm mt-0.5 block">{{ formatCurrency(trip?.car?.unit_price)
                  }} /
                    ngày</span>
                </div>
              </div>

              <!-- Calendar Selection (Matching Homepage Filter DatePickerModal style) -->
              <div
                class="border border-slate-200 rounded-2xl p-4 bg-slate-50/50 flex flex-col items-center justify-center transition-all duration-300">
                <ClientOnly>
                  <VDatePicker v-model="extensionEndDate" :columns="1" :step="1" color="green"
                    :min-date="minExtensionDate" :disabled-dates="disabledDatesFormatted" borderless expanded
                    class="custom-calendar w-full" />
                </ClientOnly>
              </div>

              <!-- Time Selector & New End Date Summary -->
              <div class="flex items-center gap-4">
                <div
                  class="flex-1 bg-white border border-slate-200 rounded-xl p-3 focus-within:border-[#1e4e57] focus-within:ring-1 focus-within:ring-[#1e4e57] transition-all relative cursor-pointer">
                  <div class="min-w-0">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Giờ trả xe
                      mới</label>
                    <div class="text-base font-bold text-slate-800">{{ extensionEndTime }}</div>
                  </div>
                  <select v-model="extensionEndTime"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <option v-for="time in timeOptions" :key="time" :value="time">{{ time }}</option>
                  </select>
                </div>

                <div class="flex-1 bg-[#1e4e57]/5 border border-[#1e4e57]/20 rounded-xl p-3">
                  <span class="block text-[10px] font-bold text-[#1e4e57] uppercase tracking-wider mb-1">Thời gian gia
                    hạn
                    thêm</span>
                  <div class="text-base font-black text-[#1e4e57]">
                    {{ calculatedExtensionDays }} ngày
                  </div>
                </div>
              </div>

              <!-- Cost Summary Box -->
              <div class=" rounded-xl">
                <div class="flex justify-between items-center text-xs font-semibold text-slate-600">
                  <span>Thời gian trả xe mới:</span>
                  <span class="font-bold text-slate-800 text-sm">
                    {{ formatNewEndDateTime }}
                  </span>
                </div>
                <div class="pt-3 flex justify-between items-center text-xs font-semibold text-slate-600">
                  <span class="font-bold text-slate-700">Phí gia hạn dự kiến:</span>
                  <span class="text-base font-extrabold text-[#1e4e57]">
                    {{ formatCurrency(calculatedExtensionAmount) }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex gap-3 mt-6">
              <button @click="showExtensionModal = false"
                class="flex-1 py-3 border border-slate-200 bg-white hover:bg-slate-50 active:scale-[0.98] transition-all text-xs font-bold text-slate-600 rounded-xl">
                Hủy
              </button>
              <button @click="submitExtensionRequest" :disabled="submittingExtension"
                class="flex-1 py-3 bg-[#1e4e57] hover:bg-[#286874] active:scale-[0.98] transition-all text-xs font-bold text-white rounded-xl shadow-md shadow-[#1e4e57]/10 flex items-center justify-center gap-1.5">
                <Icon v-if="submittingExtension" name="lucide:loader-2" class="animate-spin w-4 h-4" />
                <span v-else>Xác nhận gia hạn</span>
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Review Modal -->
    <Teleport to="body">
      <Transition name="fade">
        <div v-if="showReviewModal"
          class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[140] flex items-center justify-center p-4">
          <div @click="showReviewModal = false" class="absolute inset-0 cursor-pointer"></div>
          <div
            class="relative w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl p-6 border border-slate-100 animate-scale-up"
            @click.stop>

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
              <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <Icon name="lucide:star" class="w-5 h-5 text-amber-500 fill-amber-500" />
                Đánh giá chủ xe & Chuyến đi
              </h3>
              <button @click="showReviewModal = false"
                class="p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition">
                <Icon name="lucide:x" class="w-4 h-4" />
              </button>
            </div>

            <!-- Content -->
            <div class="space-y-4 text-xs">
              <p class="text-slate-500 font-medium">Chia sẻ trải nghiệm chuyến đi của bạn để giúp cải thiện dịch vụ của
                chúng tôi.</p>

              <!-- Star selection -->
              <div class="space-y-2 flex flex-col items-center py-2">
                <label class="block font-bold text-slate-700 text-center">Số sao đánh giá:</label>
                <div class="flex items-center gap-2">
                  <button v-for="star in 5" :key="star" type="button" @click="reviewRating = star"
                    class="p-1 rounded-full hover:scale-110 active:scale-95 transition-all text-slate-350 hover:text-amber-400 cursor-pointer border-0 bg-transparent outline-none">
                    <Icon name="heroicons:star-solid" class="w-8 h-8"
                      :class="star <= reviewRating ? 'text-amber-400' : 'text-slate-200'" />
                  </button>
                </div>
                <span class="font-bold text-[#1e4e57] text-[11px] mt-1">
                  {{ ratingLabel(reviewRating) }}
                </span>
              </div>

              <!-- Comment input -->
              <div class="space-y-1.5">
                <label class="block font-bold text-slate-700">Ý kiến đóng góp (tùy chọn):</label>
                <textarea v-model="reviewComment" rows="4"
                  placeholder="Chia sẻ thêm thông tin về tình trạng xe, độ nhiệt tình của chủ xe..."
                  class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-700 outline-none transition focus:border-[#1e4e57] focus:bg-white focus:ring-4 focus:ring-[#1e4e57]/10 placeholder:text-slate-400"></textarea>
              </div>

              <!-- Submit button -->
              <button @click="submitTripReview" :disabled="reviewRating === 0 || submittingReview"
                class="w-full py-3 px-4 rounded-xl text-xs font-bold text-white transition-all bg-[#1e4e57] hover:bg-[#286874] disabled:bg-slate-300 disabled:cursor-not-allowed flex items-center justify-center gap-1.5 active:scale-[0.98]">
                <Icon v-if="submittingReview" name="lucide:loader-2" class="animate-spin w-4 h-4" />
                <span v-else>Gửi đánh giá</span>
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Beautiful Cancel Confirmation Modal -->
    <Teleport to="body">
      <Transition name="fade">
        <div v-if="showCancelModal"
          class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[140] flex items-center justify-center p-4">
          <div @click="showCancelModal = false" class="absolute inset-0 cursor-pointer"></div>
          <div
            class="relative w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl p-6 border border-slate-100 animate-scale-up"
            @click.stop>

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
              <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <Icon name="lucide:alert-triangle" class="w-5 h-5 text-rose-500" />
                Xác nhận hủy chuyến đi
              </h3>
              <button @click="showCancelModal = false"
                class="p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition">
                <Icon name="lucide:x" class="w-4 h-4" />
              </button>
            </div>

            <!-- Content -->
            <div class="space-y-4 text-xs">
              <p class="text-slate-500 font-semibold leading-relaxed">
                Bạn có chắc chắn muốn hủy chuyến đi này không? Quyết định này không thể hoàn tác sau khi đã thực hiện.
              </p>

              <!-- Policy Summary Grid -->
              <div class="bg-rose-50/45 border border-rose-100/60 rounded-2xl p-4 space-y-2.5">
                <div class="flex justify-between items-center text-slate-500 font-medium">
                  <span>Chính sách áp dụng:</span>
                  <span class="font-bold text-slate-850 text-right max-w-[200px] leading-relaxed">
                    {{ cancelModalPolicyDesc }}
                  </span>
                </div>

                <div class="flex justify-between items-center text-slate-500 font-medium">
                  <span>Giá trị chuyến đi:</span>
                  <span class="font-bold text-slate-800">{{ formatCurrency(cancelModalTripValue) }}</span>
                </div>

                <div v-if="!cancelModalIsOwner" class="flex justify-between items-center text-slate-500 font-medium">
                  <span>Phí hủy chuyến:</span>
                  <span class="font-bold text-rose-600">{{ formatCurrency(cancelModalFeeAmount) }}</span>
                </div>

                <div
                  class="border-t border-dashed border-rose-200/50 pt-2 flex justify-between items-center font-medium">
                  <span class="text-slate-600">Tiền hoàn khách thuê:</span>
                  <span class="font-black text-emerald-600 text-sm">
                    {{ formatCurrency(cancelModalRefundAmount) }}
                  </span>
                </div>

                <div v-if="cancelModalCompensationAmount > 0"
                  class="flex justify-between items-center text-[16px] text-rose-600 font-medium">
                  <span>Bồi thường chủ xe:</span>
                  <span>{{ formatCurrency(cancelModalCompensationAmount) }}</span>
                </div>
              </div>

              <!-- Note -->
              <p class="italic text-[13px] leading-normal">
                * Tiền hoàn sẽ được chuyển trực tiếp vào ví điện tử trên hệ thống ngay sau khi lệnh hủy được xác nhận.
              </p>

              <!-- Action buttons -->
              <div class="flex gap-3 pt-2">
                <button @click="showCancelModal = false"
                  class="flex-1 py-3 px-4 rounded-xl text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 active:scale-[0.98] transition-all cursor-pointer text-center">
                  Quay lại
                </button>
                <button @click="executeCancelTrip"
                  class="flex-1 py-3 px-4 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-red-600/10">
                  <Icon name="lucide:check" class="w-4 h-4" />
                  Xác nhận hủy
                </button>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

    <!-- Modal confirm return trip early -->
    <CommonConfirmModal :show="showReturnConfirm" title="Xác nhận trả xe sớm"
      message="Bạn có chắc chắn muốn gửi yêu cầu trả xe sớm không? Yêu cầu này sẽ cần được chủ xe phê duyệt để hoàn tất."
      confirm-text="Đồng ý trả xe" cancel-text="Hủy" type="warning" @confirm="executeReturnRequest"
      @close="showReturnConfirm = false" />

    <!-- Beautiful Tailwind Reject Dialog Modal -->
    <Teleport to="body">
      <Transition name="fade">
        <div v-if="showRejectModal"
          class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[140] flex items-center justify-center p-4">
          <div @click="showRejectModal = false" class="absolute inset-0 cursor-pointer"></div>
          <div
            class="relative w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl p-6 border border-slate-100 animate-scale-up space-y-4"
            @click.stop>

            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <Icon name="lucide:x-circle" class="w-5 h-5 text-rose-500" />
                {{ rejectType === 'trip' ? 'Từ chối cho thuê' : 'Từ chối gia hạn' }}
              </h3>
              <button @click="showRejectModal = false"
                class="p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                <Icon name="lucide:x" class="w-4 h-4" />
              </button>
            </div>

            <!-- Body Description & Input -->
            <div class="space-y-3 text-xs">
              <p class="text-slate-500 font-medium leading-relaxed">
                Vui lòng nhập lý do từ chối {{ rejectType === 'trip' ? 'yêu cầu thuê xe này' : 'yêu cầu gia hạn chuyến đi này' }}:
              </p>

              <textarea v-model="rejectReason" rows="3"
                placeholder="Nhập lý do chi tiết (ví dụ: Xe đang bảo dưỡng, bận lịch đột xuất...)"
                class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-3.5 text-xs text-slate-700 outline-none transition focus:border-rose-500 focus:bg-white focus:ring-4 focus:ring-rose-500/10 placeholder:text-slate-400"></textarea>
            </div>

            <!-- Action buttons -->
            <div class="flex gap-3 pt-2">
              <button @click="showRejectModal = false" :disabled="processingAction"
                class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold text-slate-600 border border-slate-200 bg-white hover:bg-slate-50 active:scale-[0.98] transition-all cursor-pointer text-center">
                Hủy
              </button>
              <button @click="executeRejectAction" :disabled="processingAction"
                class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-rose-600/10 disabled:opacity-50">
                <Icon v-if="processingAction" name="lucide:loader-2" class="animate-spin w-4 h-4" />
                <span v-else>Xác nhận từ chối</span>
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { carService } from '~/services/car.service'
import { useToast } from '~/composables/useToast'
import { TripStatusLabel, TripStatusBadgeClass } from '~/config/trip-status'
import { useAuth } from '~/composables/useAuth'
import ImageUpload from '~/components/ImageUpload/ImageUpload.vue'
import { useVNPay } from '~/composables/useVNPay'
import { useZaloPay } from '~/composables/useZaloPay'

definePageMeta({
  layout: 'profile-no-sidebar'
})

const route = useRoute()
const router = useRouter()
const { showToast } = useToast()
const { initiatePayment: callVNPay } = useVNPay()
const { initiatePayment: callZaloPay } = useZaloPay()
const { user } = useAuth()

const trip = ref<any>(null)
const loading = ref(true)
const error = ref<string | null>(null)
const uploading = ref(false)
const showReturnConfirm = ref(false)

const showRejectModal = ref(false)
const rejectReason = ref('')
const rejectType = ref<'trip' | 'extension'>('trip')

const isOwner = computed(() => {
  if (!user.value || !trip.value || !trip.value.car) return false
  return user.value.id === trip.value.car.user_id
})

const handleBack = () => {
  if (isOwner.value) {
    navigateTo('/my-cars/bookings')
  } else {
    navigateTo('/profile/my-trips')
  }
}

const processingAction = ref(false)

const handleConfirmTrip = async () => {
  processingAction.value = true
  try {
    const res = await carService.confirmTrip(trip.value.id)
    if (res && res.success) {
      showToast('Đã phê duyệt yêu cầu thuê xe thành công!', 'success')
      await fetchTripDetails()
    } else {
      showToast(res.message || 'Phê duyệt thất bại.', 'error')
    }
  } catch (err: any) {
    console.error('Lỗi khi phê duyệt chuyến đi:', err)
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi phê duyệt.', 'error')
  } finally {
    processingAction.value = false
  }
}

const openRejectTripDialog = () => {
  rejectType.value = 'trip'
  rejectReason.value = 'Chủ xe bận lịch đột xuất'
  showRejectModal.value = true
}

const openRejectExtensionDialog = () => {
  rejectType.value = 'extension'
  rejectReason.value = 'Không bận lịch trống xe'
  showRejectModal.value = true
}

const executeRejectAction = async () => {
  if (!rejectReason.value || !rejectReason.value.trim()) {
    showToast('Vui lòng nhập lý do từ chối.', 'error')
    return
  }

  processingAction.value = true
  try {
    if (rejectType.value === 'trip') {
      const res = await carService.rejectTrip(trip.value.id, rejectReason.value.trim())
      if (res && res.success) {
        showToast('Đã từ chối yêu cầu thuê xe.', 'success')
        showRejectModal.value = false
        await fetchTripDetails()
      } else {
        showToast(res.message || 'Từ chối thất bại.', 'error')
      }
    } else {
      const res = await carService.rejectExtension(trip.value.id, rejectReason.value.trim())
      if (res && res.success) {
        showToast('Đã từ chối yêu cầu gia hạn.', 'success')
        showRejectModal.value = false
        await fetchTripDetails()
      } else {
        showToast(res.message || 'Từ chối gia hạn thất bại.', 'error')
      }
    }
  } catch (err: any) {
    console.error('Lỗi khi từ chối:', err)
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi từ chối.', 'error')
  } finally {
    processingAction.value = false
  }
}

const handleApproveExtension = async () => {
  processingAction.value = true
  try {
    const res = await carService.approveExtension(trip.value.id)
    if (res && res.success) {
      showToast('Đã đồng ý gia hạn chuyến đi thành công!', 'success')
      await fetchTripDetails()
    } else {
      showToast(res.message || 'Phê duyệt gia hạn thất bại.', 'error')
    }
  } catch (err: any) {
    console.error('Lỗi khi phê duyệt gia hạn:', err)
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi phê duyệt gia hạn.', 'error')
  } finally {
    processingAction.value = false
  }
}

// Complete / Return Trip States
const postTripUploadedImages = ref<string[]>([])
const postTripImageUploadRef = ref<any>(null)
const completingTrip = ref(false)
const returningTrip = ref(false)

// Cloudinary Multiple ImageUpload Component Refs
const uploadedImages = ref<string[]>([])
const imageUploadRef = ref<any>(null)

// Preview Fullscreen modal
const showPreviewModal = ref(false)
const previewImageUrl = ref('')

const showCancelModal = ref(false)
const cancelModalPolicyDesc = ref('')
const cancelModalTripValue = ref(0)
const cancelModalFeeAmount = ref(0)
const cancelModalRefundAmount = ref(0)
const cancelModalCompensationAmount = ref(0)
const cancelModalIsOwner = ref(false)

const handleCancelTrip = () => {
  if (!trip.value) return

  const bookingTime = new Date(trip.value.created_at)
  const startTime = new Date(trip.value.start_at)
  const now = new Date()
  const tripValue = trip.value.cost - trip.value.discount_amount

  cancelModalTripValue.value = tripValue
  cancelModalIsOwner.value = isOwner.value

  if (isOwner.value) {
    cancelModalPolicyDesc.value = 'Chủ xe tự hủy chuyến (Hoàn tiền 100%)'
    cancelModalFeeAmount.value = 0
    cancelModalRefundAmount.value = totalPaid.value
    cancelModalCompensationAmount.value = 0
  } else {
    let feePercent = 0
    let policyDesc = 'Miễn phí hủy chuyến (Trong vòng 1h sau khi đặt xe)'

    const diffInMinutes = Math.floor((now.getTime() - bookingTime.getTime()) / 60000)
    if (diffInMinutes > 60) {
      const diffInDays = (startTime.getTime() - now.getTime()) / (1000 * 60 * 60 * 24)
      if (diffInDays >= 7) {
        feePercent = 0.10
        policyDesc = 'Phí hủy 10% giá trị chuyến đi (Trước chuyến đi >= 7 ngày và sau 1h khi đặt)'
      } else {
        feePercent = 0.40
        policyDesc = 'Phí hủy 40% giá trị chuyến đi (Trong vòng 7 ngày trước chuyến đi và sau 1h khi đặt)'
      }
    }

    const cancelFeeAmount = tripValue * feePercent
    const actualCompensation = Math.min(totalPaid.value, cancelFeeAmount)
    const actualRefund = Math.max(0, totalPaid.value - actualCompensation)

    cancelModalPolicyDesc.value = policyDesc
    cancelModalFeeAmount.value = cancelFeeAmount
    cancelModalRefundAmount.value = actualRefund
    cancelModalCompensationAmount.value = actualCompensation
  }

  showCancelModal.value = true
}

const executeCancelTrip = async () => {
  if (!trip.value) return

  processingAction.value = true
  showCancelModal.value = false
  try {
    const res = isOwner.value
      ? await carService.cancelTripByOwner(trip.value.id)
      : await carService.cancelTrip(trip.value.id)

    if (res && res.success) {
      showToast('Đã hủy chuyến đi thành công!', 'success')
      await fetchTripDetails()
    } else {
      showToast(res.message || 'Hủy chuyến đi thất bại.', 'error')
    }
  } catch (err: any) {
    console.error('Lỗi khi hủy chuyến đi:', err)
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi hủy chuyến đi.', 'error')
  } finally {
    processingAction.value = false
  }
}

// Extension request states (Calendar UI matching Homepage Filter)
const showExtensionModal = ref(false)
const extensionEndDate = ref<Date | null>(null)
const extensionEndTime = ref('20:00')
const submittingExtension = ref(false)

const timeOptions = Array.from({ length: 48 }).map((_, i) => {
  const h = Math.floor(i / 2).toString().padStart(2, '0')
  const m = (i % 2 === 0) ? '00' : '30'
  return `${h}:${m}`
})

const openExtensionModal = () => {
  if (trip.value?.end_at) {
    const currentEnd = new Date(trip.value.end_at)
    extensionEndTime.value = currentEnd.toTimeString().slice(0, 5)
    const defaultNextDay = new Date(currentEnd.getTime() + 86400000)
    extensionEndDate.value = defaultNextDay
  } else {
    extensionEndDate.value = new Date(Date.now() + 86400000)
    extensionEndTime.value = '20:00'
  }
  showExtensionModal.value = true
}

const minExtensionDate = computed(() => {
  if (!trip.value?.end_at) return new Date()
  const currentEnd = new Date(trip.value.end_at)
  return new Date(currentEnd.getTime() + 86400000)
})

const disabledDatesFormatted = computed(() => {
  if (!trip.value?.end_at) return []
  const currentEnd = new Date(trip.value.end_at)
  return [
    { start: new Date(1970, 0, 1), end: currentEnd }
  ]
})

const totalPaid = computed(() => {
  if (!trip.value || !trip.value.transactions) return 0
  return trip.value.transactions.reduce((sum: number, tx: any) => sum + Number(tx.amount || 0), 0)
})

const totalExtensionPaid = computed(() => {
  if (!trip.value || !trip.value.extensions) return 0
  return trip.value.extensions
    .filter((ext: any) => ext.status === 3)
    .reduce((sum: number, ext: any) => sum + Number(ext.extension_amount || 0), 0)
})

const paidPercent = computed(() => {
  if (!trip.value || totalPaid.value === 0) return 0
  const total = trip.value.cost - trip.value.discount_amount
  if (total === 0) return 0
  return Math.round((totalPaid.value / total) * 100)
})

const cancellationDetails = computed(() => {
  if (!trip.value) return null

  const bookingTime = new Date(trip.value.created_at)
  const startTime = new Date(trip.value.start_at)
  const cancelTime = new Date(trip.value.updated_at)
  const tripValue = trip.value.cost - trip.value.discount_amount

  let feePercent = 0
  let policyDesc = 'Miễn phí hủy chuyến (Trong vòng 1h sau khi đặt xe)'

  const diffInMinutes = Math.floor((cancelTime.getTime() - bookingTime.getTime()) / 60000)
  if (diffInMinutes > 60) {
    const diffInDays = (startTime.getTime() - cancelTime.getTime()) / (1000 * 60 * 60 * 24)
    if (diffInDays >= 7) {
      feePercent = 0.10
      policyDesc = 'Phí hủy 10% giá trị chuyến đi (Trước chuyến đi >= 7 ngày và sau 1h khi đặt)'
    } else {
      feePercent = 0.40
      policyDesc = 'Phí hủy 40% giá trị chuyến đi (Trong vòng 7 ngày trước chuyến đi và sau 1h khi đặt)'
    }
  }

  const cancelFeeAmount = tripValue * feePercent
  const actualCompensation = Math.min(totalPaid.value, cancelFeeAmount)
  const actualRefund = Math.max(0, totalPaid.value - actualCompensation)

  return {
    policyDesc,
    feePercent: feePercent * 100,
    cancellationFee: cancelFeeAmount,
    compensationFee: actualCompensation,
    refundAmount: actualRefund,
    totalPaid: totalPaid.value
  }
})

const calculatedExtensionDays = computed(() => {
  if (!trip.value || !extensionEndDate.value) return 1
  const start = new Date(trip.value.end_at)
  const end = new Date(extensionEndDate.value)
  if (extensionEndTime.value) {
    const [eh, em] = extensionEndTime.value.split(':').map(Number)
    end.setHours(Number(eh), Number(em), 0, 0)
  }
  const startTime = start.getTime()
  const endTime = end.getTime()
  if (isNaN(startTime) || isNaN(endTime)) return 1
  const diffMinutes = Math.floor((endTime - startTime) / 60000)
  if (diffMinutes <= 0) return 1
  return Math.max(1, Math.ceil(diffMinutes / 1440))
})

const calculatedExtensionAmount = computed(() => {
  if (!trip.value) return 0
  const unitPrice = Number(trip.value.car?.unit_price || 0)
  return calculatedExtensionDays.value * unitPrice
})

const formatNewEndDateTime = computed(() => {
  if (!extensionEndDate.value) return ''
  const end = new Date(extensionEndDate.value)
  if (extensionEndTime.value) {
    const [eh, em] = extensionEndTime.value.split(':').map(Number)
    end.setHours(Number(eh), Number(em), 0, 0)
  }
  return formatDate(end.toISOString())
})

const submitExtensionRequest = async () => {
  if (!extensionEndDate.value) {
    showToast('Vui lòng chọn ngày trả xe mới trên lịch.', 'error')
    return
  }
  submittingExtension.value = true
  try {
    const end = new Date(extensionEndDate.value)
    if (extensionEndTime.value) {
      const [eh, em] = extensionEndTime.value.split(':').map(Number)
      end.setHours(Number(eh), Number(em), 0, 0)
    }
    const endFormatted = end.getFullYear() + '-' +
      String(end.getMonth() + 1).padStart(2, '0') + '-' +
      String(end.getDate()).padStart(2, '0') + ' ' +
      String(end.getHours()).padStart(2, '0') + ':' +
      String(end.getMinutes()).padStart(2, '0') + ':00'

    const tripId = route.params.id as string
    const res = await carService.requestExtension(tripId, {
      end_date: endFormatted,
      extended_days: calculatedExtensionDays.value,
      extension_amount: calculatedExtensionAmount.value
    })
    if (res && res.success) {
      showToast('Đã gửi yêu cầu gia hạn chuyến đi thành công!', 'success')
      showExtensionModal.value = false
      await fetchTripDetails()
    } else {
      showToast(res?.message || 'Gửi yêu cầu gia hạn thất bại.', 'error')
    }
  } catch (err: any) {
    console.error('Lỗi khi gửi yêu cầu gia hạn:', err)
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi gửi yêu cầu gia hạn.', 'error')
  } finally {
    submittingExtension.value = false
  }
}

const openPayExtensionModal = () => {
  const tripId = route.params.id as string
  router.push({
    path: '/payment',
    query: {
      trip_id: tripId,
      type: 'extension'
    }
  })
}


const openImageModal = (url: string) => {
  previewImageUrl.value = url
  showPreviewModal.value = true
}

// Retrieve the photos taken before trip (type = 0)
const beforeTripImages = computed(() => {
  if (!trip.value || !trip.value.images) return []
  return trip.value.images.filter((img: any) => img.type === 0).map((img: any) => img.image_url)
})

// Retrieve the photos taken after trip (type = 1)
const afterTripImages = computed(() => {
  if (!trip.value || !trip.value.images) return []
  return trip.value.images.filter((img: any) => img.type === 1).map((img: any) => img.image_url)
})

const fuelTypeLabel = computed(() => {
  if (!trip.value?.car?.fuel_type) return 'Chưa cập nhật'
  const lower = trip.value.car.fuel_type.toLowerCase()
  if (lower.includes('xăng') || lower.includes('gasoline') || lower.includes('petrol')) {
    return 'Xăng'
  }
  if (lower.includes('dầu') || lower.includes('diesel')) {
    return 'Dầu Diesel'
  }
  if (lower.includes('điện') || lower.includes('electric') || lower.includes('ev')) {
    return 'Điện'
  }
  return trip.value.car.fuel_type
})

const transmissionLabel = computed(() => {
  if (!trip.value?.car?.transmission) return 'Chưa cập nhật'
  const lower = trip.value.car.transmission.toLowerCase()
  if (lower === 'automatic' || lower.includes('tự động')) {
    return 'Số tự động'
  }
  if (lower === 'manual' || lower.includes('số sàn')) {
    return 'Số sàn'
  }
  return trip.value.car.transmission
})

// Normalize car thumbnail image
function carThumbnail(car: any) {
  if (!car) return 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=600'
  const thumbnailImg = car.images?.find((img: any) => img.is_thumbnail === 1)?.image_url
    || car.images?.[0]?.image_url
    || 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=600';
  return thumbnailImg
}

// Fetch trip details
const fetchTripDetails = async () => {
  loading.value = true
  error.value = null
  try {
    const tripId = route.params.id as string
    const res = await carService.getTripById(tripId)
    if (res && res.success && res.data) {
      trip.value = res.data
    } else {
      error.value = 'Không lấy được thông tin chuyến đi từ máy chủ.'
    }
  } catch (err: any) {
    console.error('Lỗi khi tải chi tiết chuyến đi:', err)
    error.value = err.response?._data?.message || 'Có lỗi xảy ra khi tải thông tin.'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchTripDetails()
})

// Helper functions
function statusLabel(status: number) {
  return (TripStatusLabel as any)[status] ?? 'Không xác định'
}

function statusClass(status: number) {
  return (TripStatusBadgeClass as any)[status] ?? 'bg-slate-100 text-slate-500'
}

function extensionStatusLabel(status?: number) {
  switch (status) {
    case 1: return 'Gia hạn: Chờ duyệt'
    case 2: return 'Gia hạn: Chờ thanh toán'
    case 3: return 'Gia hạn: Thành công'
    case 4: return 'Gia hạn: Bị từ chối'
    default: return ''
  }
}

function extensionStatusClass(status?: number) {
  switch (status) {
    case 1: return 'bg-indigo-50 border-indigo-200 text-indigo-700'
    case 2: return 'bg-amber-50 border-amber-200 text-amber-700'
    case 3: return 'bg-emerald-50 border-emerald-200 text-emerald-700'
    case 4: return 'bg-rose-50 border-rose-200 text-rose-700'
    default: return 'bg-slate-100 border-slate-200 text-slate-600'
  }
}

function formatDate(dt: string) {
  if (!dt) return '—'
  return new Date(dt).toLocaleString('vi-VN', {
    day: '2-digit', month: '2-digit', year: 'numeric',
    hour: '2-digit', minute: '2-digit',
  })
}

function duration(start: string, end: string) {
  if (!start || !end) return '—'
  const diff = new Date(end).getTime() - new Date(start).getTime()
  const days = Math.floor(diff / 86400000)
  const hours = Math.floor((diff % 86400000) / 3600000)
  return days > 0 ? `${days} ngày${hours > 0 ? ` ${hours} giờ` : ''}` : `${hours} giờ`
}

function formatCurrency(amount: number) {
  if (amount === undefined || amount === null) return '—'
  return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount)
}

// Start Trip Submission (Cloud Multiple Upload)
const handleStartTrip = async () => {
  if (uploadedImages.value.length === 0) {
    showToast('Vui lòng tải lên ít nhất 1 ảnh xe trước khi bắt đầu chuyến đi.', 'error')
    return
  }

  uploading.value = true

  try {
    const urls = await imageUploadRef.value.upload()
    if (urls.length === 0) {
      showToast('Vui lòng tải lên ít nhất 1 ảnh xe trước khi bắt đầu chuyến đi.', 'error')
      uploading.value = false
      return
    }

    const tripId = route.params.id as string
    const res = await carService.startTrip(tripId, { images: urls })
    if (res && res.success) {
      showToast('Đã bắt đầu chuyến đi thành công!', 'success')
      uploadedImages.value = []
      await fetchTripDetails()
    } else {
      showToast(res.message || 'Bắt đầu chuyến đi thất bại.', 'error')
    }
  } catch (err: any) {
    console.error('Lỗi khi bắt đầu chuyến đi:', err)
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi bắt đầu chuyến đi.', 'error')
  } finally {
    uploading.value = false
  }
}

const renterReview = computed(() => {
  if (!trip.value || !trip.value.reviews) return null;
  return trip.value.reviews.find((r: any) => r.review_type === 1);
})

const showReviewModal = ref(false)
const reviewRating = ref(5)
const reviewComment = ref('')
const submittingReview = ref(false)

const openReviewModal = () => {
  reviewRating.value = 5
  reviewComment.value = ''
  showReviewModal.value = true
}

const ratingLabel = (rating: number) => {
  switch (rating) {
    case 1: return 'Rất kém 😠';
    case 2: return 'Kém 🙁';
    case 3: return 'Bình thường 😐';
    case 4: return 'Tốt 🙂';
    case 5: return 'Tuyệt vời 🥰';
    default: return 'Chọn số sao';
  }
}

const submitTripReview = async () => {
  if (reviewRating.value === 0) {
    showToast('Vui lòng chọn số sao đánh giá.', 'error')
    return
  }
  submittingReview.value = true
  try {
    const tripId = route.params.id as string
    const res = await carService.submitReview(tripId, {
      rating: reviewRating.value,
      comment: reviewComment.value
    })
    if (res && res.success) {
      showToast('Gửi đánh giá thành công!', 'success')
      showReviewModal.value = false
      await fetchTripDetails()
    } else {
      showToast(res.message || 'Gửi đánh giá thất bại.', 'error')
    }
  } catch (err: any) {
    console.error('Lỗi khi gửi đánh giá:', err)
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi gửi đánh giá.', 'error')
  } finally {
    submittingReview.value = false
  }
}

// Return Trip Early Request
const executeReturnRequest = async () => {
  returningTrip.value = true
  try {
    const tripId = route.params.id as string
    const res = await carService.requestReturn(tripId)
    if (res && res.success) {
      showToast('Gửi yêu cầu trả xe thành công!', 'success')
      await fetchTripDetails()
    } else {
      showToast(res.message || 'Gửi yêu cầu trả xe thất bại.', 'error')
    }
  } catch (err: any) {
    console.error('Lỗi khi gửi yêu cầu trả xe:', err)
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi gửi yêu cầu trả xe.', 'error')
  } finally {
    returningTrip.value = false
  }
}

const handleReturnRequest = () => {
  showReturnConfirm.value = true
}

// Owner Confirms Complete Trip
const handleCompleteTrip = async () => {
  if (postTripUploadedImages.value.length === 0) {
    showToast('Vui lòng tải lên ít nhất 1 ảnh xe sau chuyến đi để hoàn thành.', 'error')
    return
  }

  completingTrip.value = true

  try {
    const urls = await postTripImageUploadRef.value.upload()
    if (urls.length === 0) {
      showToast('Vui lòng tải lên ít nhất 1 ảnh xe sau chuyến đi để hoàn thành.', 'error')
      completingTrip.value = false
      return
    }

    const tripId = route.params.id as string
    const res = await carService.completeTrip(tripId, { images: urls })
    if (res && res.success) {
      showToast('Đã hoàn thành chuyến đi thành công!', 'success')
      postTripUploadedImages.value = []
      await fetchTripDetails()
    } else {
      showToast(res.message || 'Hoàn thành chuyến đi thất bại.', 'error')
    }
  } catch (err: any) {
    console.error('Lỗi khi hoàn thành chuyến đi:', err)
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi hoàn thành chuyến đi.', 'error')
  } finally {
    completingTrip.value = false
  }
}
</script>

<style scoped>
/* Custom styling for v-calendar to match brand (#1e4e57) exactly like Homepage filter */
:deep(.custom-calendar) {
  --vc-color-green-50: #f0fdf4;
  --vc-color-green-100: #dcfce7;
  --vc-color-green-200: #bbf7d0;
  --vc-color-green-300: #86efac;
  --vc-color-green-400: #4ade80;
  --vc-color-green-500: #22c55e;
  --vc-color-green-600: #1e4e57;
  --vc-font-family: inherit;
  font-weight: 600;
}

:deep(.vc-day) {
  min-height: 40px;
}

/* Visually mark disabled/busy days */
:deep(.custom-calendar .vc-day-content.vc-disabled) {
  color: #ef4444 !important;
  text-decoration: line-through !important;
  opacity: 1 !important;
  cursor: not-allowed !important;
  background-color: #fee2e2 !important;
  border-radius: 50%;
  --vc-day-content-disabled-color: #ef4444;
}

:deep(.custom-calendar .vc-day:has(.vc-day-content.vc-disabled)) {
  background-color: #fff1f2 !important;
  position: relative;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

@keyframes scaleUp {
  from {
    transform: scale(0.95);
    opacity: 0;
  }

  to {
    transform: scale(1);
    opacity: 1;
  }
}

.animate-scale-up {
  animation: scaleUp 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>

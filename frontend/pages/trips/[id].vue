<template>
  <div class="min-h-screen bg-slate-50/50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      
      <!-- Back Button & Breadcrumbs -->
      <div class="mb-6 flex items-center justify-between">
        <button 
          @click="navigateTo('/profile/my-trips')" 
          class="flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-[#1e4e57] transition-colors bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm"
        >
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
      <div v-else-if="error" class="bg-white rounded-3xl border border-rose-100 p-12 text-center shadow-sm max-w-lg mx-auto">
        <div class="w-16 h-16 rounded-full bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-500 mx-auto mb-4">
          <Icon name="lucide:triangle-alert" class="w-6 h-6" />
        </div>
        <h3 class="text-lg font-bold text-slate-800">Đã xảy ra lỗi</h3>
        <p class="text-slate-500 mt-2 text-sm">{{ error }}</p>
        <button 
          @click="fetchTripDetails" 
          class="mt-6 px-6 py-2.5 bg-[#1e4e57] hover:bg-[#286874] text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-[#1e4e57]/10"
        >
          Thử lại
        </button>
      </div>

      <!-- Content -->
      <div v-else-if="trip" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 columns: Trip Details -->
        <div class="lg:col-span-2 space-y-6">
          
          <!-- Top summary banner -->
          <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-2xl bg-[#1e4e57]/10 text-[#1e4e57] flex items-center justify-center shrink-0">
                <Icon name="lucide:route" class="w-6 h-6" />
              </div>
              <div>
                <h1 class="text-lg font-black text-slate-900">Chi tiết hành trình</h1>
                <p class="text-xs text-slate-400 font-medium">Khám phá thông tin lịch trình và thanh toán</p>
              </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
              <span 
                class="px-4 py-1.5 rounded-full text-xs font-bold border shadow-sm"
                :class="statusClass(trip.status)"
              >
                {{ statusLabel(trip.status) }}
              </span>
              <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-[#1e4e57]/10 text-[#1e4e57] flex items-center gap-1.5">
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
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider flex items-center gap-1.5 mb-1.5">
                  <span class="w-2 h-2 rounded-full bg-[#1e4e57]"></span> Nhận xe
                </p>
                <p class="text-base font-bold text-slate-800">{{ formatDate(trip.start_at) }}</p>
              </div>
              <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider flex items-center gap-1.5 mb-1.5">
                  <span class="w-2 h-2 rounded-full bg-rose-500"></span> Trả xe
                </p>
                <p class="text-base font-bold text-slate-800">{{ formatDate(trip.end_at) }}</p>
              </div>
            </div>

            <div class="flex items-center justify-between rounded-2xl bg-[#1e4e57]/5 px-4 py-3.5 border border-[#1e4e57]/10">
              <span class="text-sm font-semibold text-slate-600">Tổng thời gian thuê</span>
              <span class="text-sm font-extrabold text-[#1e4e57]">{{ duration(trip.start_at, trip.end_at) }}</span>
            </div>

            <!-- Locations -->
            <div class="space-y-4 pt-2">
              <div class="flex gap-3">
                <div class="flex flex-col items-center shrink-0">
                  <div class="w-7 h-7 rounded-full bg-[#1e4e57]/10 text-[#1e4e57] flex items-center justify-center text-xs border border-[#1e4e57]/20 font-bold">1</div>
                  <div class="w-0.5 flex-grow border-l-2 border-dashed border-slate-200 my-1"></div>
                </div>
                <div>
                  <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Địa điểm nhận xe</h4>
                  <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ trip.car?.car_location?.address || 'Chưa cập nhật' }}</p>
                </div>
              </div>
              <div class="flex gap-3">
                <div class="flex flex-col items-center shrink-0">
                  <div class="w-7 h-7 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-xs border border-rose-100 font-bold">2</div>
                </div>
                <div>
                  <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Địa chỉ giao xe (nếu có)</h4>
                  <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ trip.delivery_address || 'Nhận xe tại địa điểm đăng ký của xe' }}</p>
                  <p v-if="trip.delivery_location" class="text-xs text-slate-400 font-medium mt-0.5">{{ trip.delivery_location }}</p>
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
                <span>{{ formatCurrency(trip.cost) }}</span>
              </div>
              <div v-if="trip.discount_amount > 0" class="flex justify-between text-rose-500 font-bold">
                <span>Số tiền được giảm giá</span>
                <span>-{{ formatCurrency(trip.discount_amount) }}</span>
              </div>
              <div class="border-t border-slate-100 pt-3 flex justify-between items-baseline">
                <span class="text-base font-bold text-slate-800">Thành tiền</span>
                <span class="text-2xl font-black text-[#1e4e57]">{{ formatCurrency(trip.cost - trip.discount_amount) }}</span>
              </div>
              
              <!-- Payment status and deposit details -->
              <div v-if="trip.transactions && trip.transactions.length > 0" class="pt-3 border-t border-dashed border-slate-100 space-y-2 animate-fade-in">
                <div class="flex justify-between items-center text-sm font-medium">
                  <span class="text-slate-500">Đã đặt cọc / thanh toán:</span>
                  <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-xs font-bold flex items-center gap-1">
                    <Icon name="lucide:check-circle" class="w-3.5 h-3.5" />
                    {{ paidPercent }}% ({{ formatCurrency(totalPaid) }})
                  </span>
                </div>
                <div v-if="paidPercent < 95" class="flex justify-between text-xs font-semibold leading-normal bg-amber-50/50 border border-amber-100 p-2.5 rounded-xl mt-1 text-slate-500">
                  <span class="text-amber-800">Còn lại cần trả chủ xe:</span>
                  <span class="text-amber-900 font-bold">{{ formatCurrency((trip.cost - trip.discount_amount) - totalPaid) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right 1 column: Car Details & Action Control -->
        <div class="space-y-6">
          
          <!-- Car Card -->
          <div class="bg-white rounded-3xl border border-slate-200/60 overflow-hidden shadow-sm">
            <div class="relative h-48 bg-slate-100">
              <img 
                :src="carThumbnail(trip.car)" 
                :alt="trip.car?.name" 
                class="w-full h-full object-cover"
              />
              <span class="absolute top-4 right-4 bg-slate-900/75 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm border border-white/10 backdrop-blur-sm">
                {{ trip.car?.license_plate }}
              </span>
            </div>
            
            <div class="p-6 space-y-4">
              <div>
                <span class="text-[10px] font-black uppercase text-[#1e4e57] tracking-wider">
                  {{ trip.car?.car_brand?.brand_name || 'Hãng xe' }} • {{ trip.car?.car_type?.type_name || 'Dòng xe' }}
                </span>
                <h3 class="text-lg font-black text-slate-900 mt-0.5">{{ trip.car?.name }}</h3>
              </div>

              <!-- Specs Grid -->
              <div class="grid grid-cols-2 gap-3.5 text-xs font-semibold text-slate-600 border-t border-b border-slate-100 py-4">
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
                <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 leading-relaxed max-h-32 overflow-y-auto">
                  {{ trip.car.rental_terms }}
                </p>
              </div>
            </div>
          </div>

          <!-- Owner Info Card -->
          <div class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-black text-slate-800 border-b border-slate-100 pb-2.5 uppercase tracking-wider">
              Thông tin chủ xe
            </h3>
            <div class="flex items-center gap-3">
              <img 
                :src="trip.car?.owner?.avatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=150'" 
                alt="Owner Avatar" 
                class="w-12 h-12 rounded-2xl object-cover border border-slate-100 shadow-sm"
              />
              <div class="min-w-0">
                <p class="text-sm font-bold text-slate-800 truncate">{{ trip.car?.owner?.name || 'Chưa cập nhật' }}</p>
                <p class="text-xs text-slate-400 font-medium">Chủ sở hữu xe</p>
              </div>
            </div>
            <div class="space-y-1.5 text-xs text-slate-500 font-semibold pt-1">
              <p class="flex items-center gap-2"><Icon name="lucide:phone" class="text-slate-400 w-4 h-4" /> {{ trip.car?.owner?.phone || 'Chưa cập nhật SĐT' }}</p>
              <p class="flex items-center gap-2"><Icon name="lucide:mail" class="text-slate-400 w-4 h-4" /> {{ trip.car?.owner?.email || 'Chưa cập nhật Email' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Trip Status Transition Card (Full-width underneath the two-column grid) -->
      <div v-if="trip" class="bg-white rounded-3xl border border-slate-200/60 p-6 shadow-sm space-y-4 mt-8">
        <h3 class="text-sm font-black text-slate-800 border-b border-slate-100 pb-2.5 uppercase tracking-wider flex items-center gap-2">
          <Icon name="lucide:camera" class="text-[#1e4e57] w-4 h-4" />
          Trạng thái & Hình ảnh xe
        </h3>

        <!-- CASE 1: Trip is Confirmed (status = 2) - Allow uploading and starting -->
        <div v-if="trip.status === 2" class="space-y-4">
          <p class="text-xs text-slate-500 leading-relaxed">
            Vui lòng chụp và tải lên hình ảnh hiện trạng của xe trước khi khởi hành. Đây là cơ sở để đối chiếu khi bàn giao lại xe.
          </p>

          <!-- Premium Cloud ImageUpload Component -->
          <ImageUpload ref="imageUploadRef" v-model="uploadedImages" :max-files="5" />

          <!-- Start Trip Button -->
          <button 
            @click="handleStartTrip" 
            :disabled="uploadedImages.length === 0 || uploading"
            class="w-full py-3 px-4 rounded-xl text-xs font-bold text-white transition-all flex items-center justify-center gap-2 shadow-md transform"
            :class="uploadedImages.length > 0 && !uploading 
              ? 'bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] shadow-emerald-600/10 cursor-pointer' 
              : 'bg-slate-300 shadow-none cursor-not-allowed'"
          >
            <span v-if="uploading" class="flex items-center gap-1.5">
              <Icon name="lucide:loader-2" class="animate-spin w-4 h-4" />
              Đang khởi hành...
            </span>
            <span v-else class="flex items-center gap-1.5">
              <Icon name="lucide:play" class="w-4 h-4" />
              Bắt đầu chuyến đi
            </span>
          </button>
        </div>

        <!-- CASE 2: Trip is Ongoing (status = 3), Completed (status = 4) or Waiting Extension (status = 7) - Display uploaded photos -->
        <div v-else-if="trip.status === 3 || trip.status === 4 || trip.status === 7" class="space-y-4">
          <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider text-[10px] flex items-center gap-1">
            <Icon name="lucide:check-circle" class="text-emerald-500 w-4 h-4" />
            Ảnh xe trước chuyến đi đã tải lên
          </p>
          
          <div v-if="beforeTripImages.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <div 
              v-for="(imgUrl, index) in beforeTripImages" 
              :key="index"
              class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm aspect-video bg-slate-50 group relative cursor-pointer"
              @click="openImageModal(imgUrl)"
            >
              <img :src="imgUrl" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="Car state before trip" />
              <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <span class="text-white text-[10px] bg-black/60 px-2 py-1 rounded-md font-bold">Xem ảnh lớn</span>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-6 text-xs text-slate-400 border border-dashed rounded-2xl bg-slate-50 font-medium">
            Không tìm thấy ảnh xe trước chuyến đi
          </div>

          <!-- Trip is ongoing (status = 3) -->
          <div v-if="trip.status === 3" class="space-y-3">
            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-xs text-amber-800 flex items-start gap-2.5">
              <Icon name="lucide:info" class="mt-0.5 w-4 h-4 shrink-0 text-amber-600" />
              <div class="leading-relaxed">
                <p class="font-bold">Đang trong chuyến hành trình</p>
                <p class="mt-0.5 font-medium opacity-90">
                  Chuyến đi đang diễn ra an toàn. Vui lòng liên hệ với chủ xe nếu có bất kỳ sự cố hay phát sinh nào trong quá trình di chuyển.
                </p>
              </div>
            </div>
            
            <button 
              @click="openExtensionModal" 
              class="w-full py-3 px-4 rounded-xl text-xs font-bold text-white transition-all bg-[#1e4e57] hover:bg-[#286874] active:scale-[0.98] cursor-pointer shadow-md shadow-[#1e4e57]/10 flex items-center justify-center gap-1.5"
            >
              <Icon name="lucide:calendar-plus" class="w-4 h-4" />
              Gia hạn chuyến đi
            </button>
          </div>

          <!-- Trip is completed (status = 4) -->
          <div v-else-if="trip.status === 4" class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 text-xs text-emerald-800 flex items-start gap-2.5">
            <Icon name="lucide:check-circle" class="mt-0.5 w-4 h-4 shrink-0 text-emerald-600" />
            <div class="leading-relaxed">
              <p class="font-bold">Chuyến đi đã kết thúc</p>
              <p class="mt-0.5 font-medium opacity-90">
                Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi! Chuyến đi đã được hoàn thành thành công.
              </p>
            </div>
          </div>

          <!-- Trip is waiting for extension approval (status = 7) -->
          <div v-else-if="trip.status === 7" class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4 text-xs text-indigo-900 flex items-start gap-2.5">
            <Icon name="lucide:clock" class="mt-0.5 w-4 h-4 shrink-0 text-indigo-600" />
            <div class="leading-relaxed flex-grow">
              <p class="font-bold">Đang chờ chủ xe duyệt gia hạn</p>
              <p class="mt-0.5 font-medium opacity-90">
                Yêu cầu gia hạn thêm ngày đang chờ chủ xe phê duyệt.
              </p>
              <div v-if="trip.extended_end_at" class="mt-2.5 pt-2 border-t border-indigo-200/60 flex flex-col sm:flex-row gap-4">
                <div>
                  <span class="text-slate-500 font-semibold block text-[10px] uppercase tracking-wider">Ngày trả xe đề xuất:</span>
                  <span class="font-bold text-sm text-indigo-950">{{ formatDate(trip.extended_end_at) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- CASE 3: Trip is pending, waiting payment or cancelled -->
        <div v-else class="text-center py-6 text-xs text-slate-400 border border-dashed rounded-2xl bg-slate-50 font-semibold p-4">
          <template v-if="trip.status === 0">
            <Icon name="lucide:hourglass" class="text-amber-500 mb-2 mx-auto block w-6 h-6" />
            Chuyến đi đang chờ chủ xe duyệt. Bạn sẽ có thể tải ảnh lên sau khi yêu cầu được chấp nhận.
          </template>
          <template v-else-if="trip.status === 1">
            <Icon name="lucide:credit-card" class="text-sky-500 mb-2 mx-auto block w-6 h-6" />
            <p class="mb-3 text-slate-600 font-medium">Chuyến đi đang chờ thanh toán đặt cọc.</p>
            <nuxt-link 
              :to="`/payment?trip_id=${trip.id}`" 
              class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-sky-600/10 cursor-pointer mx-auto flex items-center gap-1.5 active:scale-[0.98] inline-flex"
            >
              <Icon name="lucide:wallet" class="w-4 h-4" />
              Thanh toán ngay
            </nuxt-link>
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
        <div v-if="showPreviewModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[150] flex items-center justify-center p-4">
          <div @click="showPreviewModal = false" class="absolute inset-0 cursor-zoom-out"></div>
          <div class="relative max-w-4xl w-full bg-transparent rounded-3xl overflow-hidden shadow-2xl flex flex-col items-center">
            <button @click="showPreviewModal = false" class="absolute top-4 right-4 bg-black/50 hover:bg-black/80 text-white p-2.5 rounded-full transition-colors z-10 shadow-md flex items-center justify-center">
              <Icon name="lucide:x" class="w-5 h-5" />
            </button>
            <img :src="previewImageUrl" class="max-h-[85vh] max-w-full object-contain rounded-2xl border border-white/10 shadow-2xl" />
          </div>
        </div>
      </Transition>
    </Teleport>



    <!-- Extension Modal -->
    <Teleport to="body">
      <Transition name="fade">
        <div v-if="showExtensionModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[140] flex items-center justify-center p-4">
          <div @click="showExtensionModal = false" class="absolute inset-0 cursor-pointer"></div>
          <div class="relative w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl p-6 border border-slate-100 animate-scale-up" @click.stop>
            
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
              <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <Icon name="lucide:calendar-plus" class="w-5 h-5 text-[#1e4e57]" />
                Yêu cầu gia hạn chuyến đi
              </h3>
              <button @click="showExtensionModal = false" class="p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition">
                <Icon name="lucide:x" class="w-4 h-4" />
              </button>
            </div>

            <!-- Content -->
            <div class="space-y-4 text-xs">
              <p class="text-slate-500 font-medium">Bạn có thể chọn số ngày muốn gia hạn thêm. Đơn giá thuê của xe là <span class="font-bold text-slate-800">{{ formatCurrency(trip?.car?.unit_price) }} / ngày</span>.</p>
              
              <div class="space-y-1.5">
                <label class="block font-bold text-slate-700">Số ngày gia hạn thêm:</label>
                <div class="flex items-center gap-3">
                  <button 
                    type="button" 
                    @click="adjustExtensionDays(-1)" 
                    class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 hover:bg-slate-200 active:scale-95 transition"
                  >
                    <Icon name="lucide:minus" class="w-4 h-4" />
                  </button>
                  <span class="w-12 text-center text-base font-black text-slate-800">{{ extensionDays }}</span>
                  <button 
                    type="button" 
                    @click="adjustExtensionDays(1)" 
                    class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 hover:bg-slate-200 active:scale-95 transition"
                  >
                    <Icon name="lucide:plus" class="w-4 h-4" />
                  </button>
                  <span class="text-slate-400 font-medium">ngày</span>
                </div>
              </div>

              <!-- Cost Summary Box -->
              <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-2">
                <div class="flex justify-between text-slate-500 font-medium">
                  <span>Ngày trả xe hiện tại:</span>
                  <span class="font-bold text-slate-700">{{ formatDate(trip?.end_at) }}</span>
                </div>
                <div class="flex justify-between text-slate-500 font-medium">
                  <span>Ngày trả xe mới:</span>
                  <span class="font-bold text-emerald-600">{{ formatDate(newEndAtDate) }}</span>
                </div>
              </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex gap-3 mt-6">
              <button 
                @click="showExtensionModal = false" 
                class="flex-1 py-2.5 border border-slate-200 bg-white hover:bg-slate-50 active:scale-[0.98] transition-all text-xs font-bold text-slate-600 rounded-xl"
              >
                Hủy
              </button>
              <button 
                @click="submitExtensionRequest" 
                :disabled="submittingExtension"
                class="flex-1 py-2.5 bg-[#1e4e57] hover:bg-[#286874] active:scale-[0.98] transition-all text-xs font-bold text-white rounded-xl shadow-md shadow-[#1e4e57]/10 flex items-center justify-center gap-1.5"
              >
                <Icon v-if="submittingExtension" name="lucide:loader-2" class="animate-spin w-4 h-4" />
                <span v-else>Xác nhận gia hạn</span>
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
import { useRoute } from 'vue-router'
import { carService } from '~/services/car.service'
import { useToast } from '~/composables/useToast'
import { TripStatusLabel, TripStatusBadgeClass } from '~/config/trip-status'
import ImageUpload from '~/components/ImageUpload/ImageUpload.vue'

definePageMeta({
  layout: 'profile-no-sidebar'
})

const route = useRoute()
const { showToast } = useToast()

const trip = ref<any>(null)
const loading = ref(true)
const error = ref<string | null>(null)
const uploading = ref(false)

// Cloudinary Multiple ImageUpload Component Refs
const uploadedImages = ref<string[]>([])
const imageUploadRef = ref<any>(null)

// Preview Fullscreen modal
const showPreviewModal = ref(false)
const previewImageUrl = ref('')



// Extension request states
const showExtensionModal = ref(false)
const extensionDays = ref(1)
const submittingExtension = ref(false)

const openExtensionModal = () => {
  extensionDays.value = 1
  showExtensionModal.value = true
}

const adjustExtensionDays = (val: number) => {
  extensionDays.value = Math.max(1, extensionDays.value + val)
}

const totalPaid = computed(() => {
  if (!trip.value || !trip.value.transactions) return 0
  return trip.value.transactions.reduce((sum: number, tx: any) => sum + Number(tx.amount || 0), 0)
})

const paidPercent = computed(() => {
  if (!trip.value || totalPaid.value === 0) return 0
  const total = trip.value.cost - trip.value.discount_amount
  if (total === 0) return 0
  return Math.round((totalPaid.value / total) * 100)
})

const newEndAtDate = computed(() => {
  if (!trip.value || !trip.value.end_at) return ''
  const currentEnd = new Date(trip.value.end_at)
  currentEnd.setDate(currentEnd.getDate() + extensionDays.value)
  return currentEnd.toISOString()
})

const submitExtensionRequest = async () => {
  submittingExtension.value = true
  try {
    const tripId = route.params.id as string
    const res = await carService.requestExtension(tripId, { extended_days: extensionDays.value })
    if (res && res.success) {
      showToast('Đã gửi yêu cầu gia hạn chuyến đi thành công!', 'success')
      showExtensionModal.value = false
      await fetchTripDetails()
    } else {
      showToast(res.message || 'Gửi yêu cầu gia hạn thất bại.', 'error')
    }
  } catch (err: any) {
    console.error('Lỗi khi gửi yêu cầu gia hạn:', err)
    showToast(err.response?._data?.message || 'Có lỗi xảy ra khi gửi yêu cầu gia hạn.', 'error')
  } finally {
    submittingExtension.value = false
  }
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

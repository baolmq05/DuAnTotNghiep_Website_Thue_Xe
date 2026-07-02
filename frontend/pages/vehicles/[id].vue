<template>
  <div class="min-h-screen bg-slate-50/50 pb-12">
    <!-- Loading State -->
    <div v-if="loading" class="max-w-7xl mx-auto px-4 py-32 flex flex-col items-center justify-center">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-primary"></div>
      <p class="mt-4 text-slate-500 font-medium">Đang tải thông tin xe...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error || !car" class="max-w-7xl mx-auto px-4 py-32 flex flex-col items-center justify-center">
      <Icon name="lucide:alert-circle" size="48" class="text-rose-500" />
      <h2 class="mt-4 text-xl font-bold text-slate-800">{{ error || 'Không tìm thấy thông tin xe' }}</h2>
      <NuxtLink to="/vehicle-list" class="mt-6 px-6 py-2.5 rounded-xl bg-brand-primary text-white font-bold hover:opacity-90">
        Quay lại danh sách xe
      </NuxtLink>
    </div>

    <!-- Main Content -->
    <div v-else class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
        <!-- ═══════════════════════════════════════
             LEFT COLUMN (7) — Gallery + nội dung
        ════════════════════════════════════════ -->
        <div class="lg:col-span-7 space-y-6">
          <!-- IMAGE GALLERY -->
          <div
            class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100/80"
          >
            <div
              class="relative rounded-xl overflow-hidden shadow-sm border border-slate-100 h-[240px] sm:h-[340px] bg-slate-900 w-full"
            >
              <img
                :src="carImages[activeIndex]"
                alt="Xe chính"
                class="w-full h-full object-cover transition-all duration-500"
              />
              <div
                class="absolute bottom-3 right-3 bg-slate-950/70 backdrop-blur-sm text-white text-[10px] px-2.5 py-1 rounded-full font-bold tracking-wider"
              >
                {{ activeIndex + 1 }} / {{ carImages.length }}
              </div>
            </div>
            <div class="flex gap-2.5 mt-3 overflow-x-auto pb-1.5">
              <div
                v-for="(imgUrl, idx) in carImages"
                :key="idx"
                @click="activeIndex = idx"
                :class="[
                  'relative w-16 h-12 rounded-lg overflow-hidden cursor-pointer flex-shrink-0 transition-all duration-300 border-2',
                  activeIndex === idx
                    ? 'border-brand-primary scale-[1.02] shadow-sm'
                    : 'border-slate-100 hover:border-slate-200 opacity-80',
                ]"
              >
                <img
                  :src="imgUrl"
                  alt="Thumbnail"
                  class="w-full h-full object-cover"
                />
              </div>
            </div>
          </div>

          <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100/80"
          >
            <div class="flex items-start justify-between gap-4">
              <div>
                <h1
                  class="text-2xl sm:text-3xl font-black text-brand-dark tracking-tight"
                >
                  {{ car?.name }}
                </h1>
                <div class="flex items-center gap-3 mt-2 flex-wrap">
                  <div class="flex items-center gap-1.5">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="w-4 h-4 text-yellow-400 fill-current"
                      viewBox="0 0 24 24"
                    >
                      <path
                        d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2L9.19 8.63L2 9.24l5.46 4.73L5.82 21z"
                      />
                    </svg>
                    <span class="text-sm font-extrabold text-brand-dark"
                      >{{ car?.reviews_avg_rating ? parseFloat(car.reviews_avg_rating).toFixed(1) : '5.0' }}</span
                    >
                    <span
                      class="text-xs text-gray-400 font-bold uppercase tracking-wider"
                      >• {{ car?.trips_count || 0 }} chuyến</span
                    >
                  </div>
                  <span class="text-slate-200">|</span>
                  <div class="flex items-center gap-1 text-slate-500">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="w-4 h-4 text-gray-400"
                      viewBox="0 0 24 24"
                    >
                      <path
                        fill="currentColor"
                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7m0 9.5a2.5 2.5 0 0 1 0-5a2.5 2.5 0 0 1 0 5"
                      />
                    </svg>
                    <span class="text-sm font-medium"
                      >{{ car?.car_location?.address || 'Chưa cập nhật' }}</span
                    >
                  </div>
                </div>
                <div class="flex items-center gap-2 mt-3 flex-wrap">
                  <span
                    class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-full border border-emerald-100"
                  >
                    <span
                      class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"
                    ></span>
                    Miễn thế chấp
                  </span>
                  <span v-if="car?.delivery_option_id"
                    class="inline-flex items-center gap-1.5 bg-brand-secondary text-brand-primary text-xs font-bold px-3 py-1.5 rounded-full border border-brand-primary/10"
                  >
                    Giao xe tận nơi
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-2 flex-shrink-0">
                <button
                  class="p-2.5 rounded-xl border border-slate-200 hover:border-brand-primary hover:text-brand-primary text-slate-500 hover:bg-slate-50 transition-all duration-200"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    viewBox="0 0 24 24"
                  >
                    <path
                      fill="currentColor"
                      d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81c1.66 0 3-1.34 3-3s-1.34-3-3-3s-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65c0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92"
                    />
                  </svg>
                </button>
                <button
                  class="p-2.5 rounded-xl border transition-all duration-200"
                  :class="isFavorite ? 'border-rose-200 text-rose-600 bg-rose-50' : 'border-slate-200 text-slate-500 hover:border-rose-200 hover:text-rose-600 hover:bg-rose-50'"
                  @click="handleToggleFavorite"
                >
                  <Icon 
                    :name="isFavorite ? 'heroicons:heart-solid' : 'heroicons:heart'" 
                    class="w-5 h-5"
                  />
                </button>
              </div>
            </div>
          </div>

          <!-- Đặc điểm -->
          <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100/80"
          >
            <h2
              class="text-base font-bold text-brand-dark mb-4 flex items-center gap-2"
            >
              <span class="w-1.5 h-5 bg-brand-primary rounded-full"></span>
              Đặc điểm
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
              <div
                v-for="spec in specs"
                :key="spec.label"
                class="flex flex-col items-center text-center gap-1.5 p-4 bg-slate-50/50 border border-slate-100 rounded-2xl hover:border-brand-primary/35 hover:bg-white hover:shadow-md transition-all duration-300"
              >
                <div
                  class="w-10 h-10 rounded-full bg-brand-secondary text-brand-primary flex items-center justify-center"
                  v-html="spec.icon"
                ></div>
                <span
                  class="text-[11px] font-medium text-gray-400 uppercase tracking-wider mt-1"
                  >{{ spec.label }}</span
                >
                <span class="text-sm font-extrabold text-brand-dark">{{
                  spec.value
                }}</span>
              </div>
            </div>
          </div>

          <!-- Mô tả -->
          <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100/80"
          >
            <h2
              class="text-base font-bold text-brand-dark mb-3 flex items-center gap-2"
            >
              <span class="w-1.5 h-5 bg-brand-primary rounded-full"></span>
              Mô tả
            </h2>
            <div
              class="text-sm text-slate-600 leading-relaxed space-y-3"
              :class="{ 'line-clamp-4': !showFullDesc }"
            >
              <p class="font-semibold text-brand-dark whitespace-pre-line">
                {{ car?.description || 'Chưa có mô tả chi tiết cho xe này.' }}
              </p>
              <div v-if="car?.rental_terms" class="mt-4 pt-4 border-t border-slate-100">
                <p class="font-bold text-brand-dark mb-1">Điều khoản tự lái:</p>
                <p class="text-slate-600">{{ car.rental_terms }}</p>
              </div>
            </div>
            <button
              @click="showFullDesc = !showFullDesc"
              class="mt-4 text-sm text-brand-primary font-bold hover:underline flex items-center gap-1"
            >
              {{ showFullDesc ? "Thu gọn" : "Xem thêm" }}
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4 transition-transform duration-200"
                :class="{ 'rotate-180': showFullDesc }"
                viewBox="0 0 24 24"
              >
                <path
                  fill="none"
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="m6 9l6 6l6-6"
                />
              </svg>
            </button>
          </div>

          <!-- Tiện nghi -->
          <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100/80"
          >
            <h2
              class="text-base font-bold text-brand-dark mb-4 flex items-center gap-2"
            >
              <span class="w-1.5 h-5 bg-brand-primary rounded-full"></span>
              Các tiện nghi khác
            </h2>
            <div class="grid grid-cols-2 gap-3">
              <div
                v-for="amenity in amenities"
                :key="amenity.name"
                class="flex items-center gap-3 text-sm text-slate-700 bg-slate-50/40 p-3 rounded-xl border border-slate-100/60 hover:border-brand-primary/20 hover:bg-white transition-all duration-200"
              >
                <img
                  v-if="amenity.icon"
                  :src="amenity.icon"
                  alt="Icon"
                  class="w-5 h-5 object-contain flex-shrink-0"
                />
                <svg
                  v-else
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-4 h-4 text-brand-primary flex-shrink-0"
                  viewBox="0 0 24 24"
                >
                  <path
                    fill="currentColor"
                    d="M9 16.17L4.83 12l-1.42 1.41L9 19L21 7l-1.41-1.41z"
                  />
                </svg>
                <span class="font-semibold text-slate-700">{{ amenity.name }}</span>
              </div>
            </div>
          </div>

          <!-- Giấy tờ thuê xe -->
          <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100/80"
          >
            <h2
              class="text-base font-bold text-brand-dark mb-1 flex items-center gap-2"
            >
              <span class="w-1.5 h-5 bg-brand-primary rounded-full"></span>
              Giấy tờ thuê xe
            </h2>
            <p class="text-xs text-gray-400 mb-4 ml-3.5 font-semibold">
              Chọn 1 trong 2 hình thức sau
            </p>
            <div class="grid grid-cols-1 gap-3">
              <div
                v-for="doc in documents"
                :key="doc.title"
                class="flex items-start gap-3.5 p-4 bg-slate-50 border border-slate-100 rounded-2xl hover:border-brand-primary/20 transition-all duration-300"
              >
                <div
                  class="w-10 h-10 rounded-xl bg-brand-secondary text-brand-primary flex items-center justify-center flex-shrink-0"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    viewBox="0 0 24 24"
                  >
                    <path
                      fill="currentColor"
                      d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zm-1 7V3.5L18.5 9z"
                    />
                  </svg>
                </div>
                <div>
                  <h4 class="text-sm font-bold text-brand-dark mb-0.5">
                    {{ doc.title }}
                  </h4>
                  <p
                    class="text-xs text-slate-600 leading-relaxed font-semibold"
                  >
                    {{ doc.desc }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Chính sách hủy chuyến -->
          <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100/80"
          >
            <h2
              class="text-base font-bold text-brand-dark mb-4 flex items-center gap-2"
            >
              <span class="w-1.5 h-5 bg-brand-primary rounded-full"></span>
              Chính sách hủy chuyến
            </h2>
            <div class="overflow-hidden border border-slate-100 rounded-xl">
              <table class="w-full text-sm text-left border-collapse">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-100">
                    <th
                      class="py-2.5 px-4 font-semibold text-gray-500 text-xs uppercase tracking-wider"
                    >
                      Thời điểm hủy
                    </th>
                    <th
                      class="py-2.5 px-4 font-semibold text-gray-500 text-xs uppercase tracking-wider text-right"
                    >
                      Phí hủy
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr
                    v-for="policy in cancelPolicies"
                    :key="policy.time"
                    class="hover:bg-slate-50/50 transition-colors"
                  >
                    <td
                      class="py-3 px-4 text-slate-700 font-medium text-xs sm:text-sm"
                    >
                      {{ policy.time }}
                    </td>
                    <td
                      class="py-3 px-4 text-right font-extrabold text-xs sm:text-sm"
                      :class="
                        policy.fee === 'Miễn phí'
                          ? 'text-green-600'
                          : 'text-brand-accent'
                      "
                    >
                      {{ policy.fee }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Chủ xe -->
          <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100/80"
          >
            <h2
              class="text-base font-bold text-brand-dark mb-4 flex items-center gap-2"
            >
              <span class="w-1.5 h-5 bg-brand-primary rounded-full"></span>
              Chủ xe
            </h2>
            <div
              class="flex items-center gap-4 bg-slate-50/50 p-4 border border-slate-100 rounded-2xl"
            >
              <div v-if="!car?.owner?.avatar"
                class="w-14 h-14 rounded-full bg-brand-primary flex items-center justify-center text-white text-xl font-bold flex-shrink-0 shadow-md shadow-brand-primary/10"
              >
                {{ car?.owner?.name?.charAt(0).toUpperCase() || 'M' }}
              </div>
              <img v-else
                :src="car.owner.avatar"
                alt="Owner Avatar"
                class="w-14 h-14 rounded-full object-cover shrink-0 shadow-md shadow-brand-primary/10"
              />
              <div class="flex-1">
                <p class="font-extrabold text-brand-dark">
                  {{ car?.owner?.name || 'Chủ xe' }}
                </p>
                <div class="flex items-center gap-2 mt-1">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 text-yellow-400 fill-current"
                    viewBox="0 0 24 24"
                  >
                    <path
                      d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2L9.19 8.63L2 9.24l5.46 4.73L5.82 21z"
                    />
                  </svg>
                  <span class="text-sm font-extrabold text-brand-dark"
                    >{{ car?.reviews_avg_rating ? parseFloat(car.reviews_avg_rating).toFixed(1) : '5.0' }}</span
                  >
                  <span class="text-gray-300">•</span>
                  <span
                    class="text-xs text-gray-500 font-bold uppercase tracking-wider"
                    >{{ car?.trips_count || 0 }} chuyến</span
                  >
                </div>
              </div>
              <button
                class="flex-shrink-0 flex items-center gap-2 border border-brand-primary text-brand-primary hover:bg-brand-primary hover:text-white text-sm font-bold px-4 py-2.5 rounded-xl transition-all duration-200 shadow-sm"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-4 h-4"
                  viewBox="0 0 24 24"
                >
                  <path
                    fill="currentColor"
                    d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"
                  />
                </svg>
                Nhắn tin
              </button>
            </div>
            <div class="grid grid-cols-3 gap-3 mt-4">
              <div
                v-for="stat in hostStats"
                :key="stat.label"
                class="text-center p-3 bg-slate-50 border border-slate-100 rounded-xl hover:border-brand-primary/15 transition-all"
              >
                <p class="text-base sm:text-lg font-black text-brand-primary">
                  {{ stat.value }}
                </p>
                <p
                  class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5"
                >
                  {{ stat.label }}
                </p>
              </div>
            </div>
          </div>

          <!-- Đánh giá -->
          <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100/80"
          >
            <h2
              class="text-base font-bold text-brand-dark mb-4 flex items-center gap-2"
            >
              <span class="w-1.5 h-5 bg-brand-primary rounded-full"></span>
              Đánh giá từ khách hàng
            </h2>
            <div class="space-y-4">
              <div
                v-for="review in formattedReviews"
                :key="review.name"
                class="border-b border-slate-100 pb-4 last:border-0 last:pb-0"
              >
                <div class="flex items-center gap-3 mb-2.5">
                  <div
                    class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm"
                    :style="{ backgroundColor: review.color }"
                  >
                    {{ review.name.charAt(0) }}
                  </div>
                  <div>
                    <p class="text-sm font-bold text-brand-dark">
                      {{ review.name }}
                    </p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                      <div class="flex items-center gap-0.5 text-yellow-400">
                        <svg
                          v-for="s in 5"
                          :key="s"
                          xmlns="http://www.w3.org/2000/svg"
                          class="w-3.5 h-3.5 fill-current"
                          viewBox="0 0 24 24"
                        >
                          <path
                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2L9.19 8.63L2 9.24l5.46 4.73L5.82 21z"
                          />
                        </svg>
                      </div>
                      <span
                        class="text-[11px] text-gray-400 font-bold uppercase tracking-wider"
                        >{{ review.date }}</span
                      >
                    </div>
                  </div>
                </div>
                <p
                  class="text-sm text-slate-600 leading-relaxed pl-1 font-medium"
                >
                  {{ review.text }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- ═══════════════════════════════════════
             RIGHT COLUMN (5) — Tên xe + Booking sticky
        ════════════════════════════════════════ -->
        <div class="  lg:col-span-5">
          <div class="sticky top-6 space-y-4">
            <!-- Booking card -->
            <div
              class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden"
            >
              <!-- Price header -->
              <div class="bg-brand-dark px-6 py-5">
                <div class="flex items-end gap-2">
                  <span class="text-3xl font-black text-white">{{ car ? (car.unit_price / 1000).toFixed(0) : '' }}K</span>
                  <span class="text-brand-light text-sm font-medium pb-0.5"
                    >/ngày</span
                  >
                </div>
                <div class="flex items-center gap-2 mt-1.5 text-white">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-3.5 h-3.5 text-yellow-400 fill-current"
                    viewBox="0 0 24 24"
                  >
                    <path
                      d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2L9.19 8.63L2 9.24l5.46 4.73L5.82 21z"
                    />
                  </svg>
                  <span class="text-xs text-white/70 font-semibold"
                    >{{ car?.reviews_avg_rating ? parseFloat(car.reviews_avg_rating).toFixed(1) : '5.0' }} • {{ car?.trips_count || 0 }} chuyến • Miễn thế chấp</span
                  >
                </div>
              </div>

              <div class="p-5 space-y-4">
                <!-- Date picker -->
                <div>
                  <p
                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2"
                  >
                    Thời gian thuê
                  </p>
                  <div @click="isDatePickerOpen = true" class="grid grid-cols-2 gap-2">
                    <div
                      class="border border-slate-200 rounded-xl p-3 cursor-pointer hover:border-brand-primary transition-colors"
                    >
                      <p
                        class="text-[9px] text-gray-400 font-bold uppercase tracking-wider"
                      >
                        NHẬN XE
                      </p>
                      <p
                        class="text-sm font-extrabold text-brand-dark mt-0.5"
                      >
                        {{ formattedStart || 'Chọn thời gian' }}
                      </p>
                    </div>
                    <div
                      class="border border-slate-200 rounded-xl p-3 cursor-pointer hover:border-brand-primary transition-colors"
                    >
                      <p
                        class="text-[9px] text-gray-400 font-bold uppercase tracking-wider"
                      >
                        TRẢ XE
                      </p>
                      <p
                        class="text-sm font-extrabold text-brand-dark mt-0.5"
                      >
                        {{ formattedEnd || 'Chọn thời gian' }}
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Địa điểm -->
                <div>
                  <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                    Hình thức nhận xe
                  </p>
                  
                  <!-- Tabs/Toggles -->
                  <div class="grid grid-cols-2 gap-2 mb-3">
                    <button
                      type="button"
                      :class="[
                        'py-2 px-3 text-xs font-bold rounded-xl border transition-all duration-200',
                        receiveMethod === 'pickup'
                          ? 'bg-[#1e4e57] text-white border-[#1e4e57]'
                          : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'
                      ]"
                      @click="receiveMethod = 'pickup'"
                    >
                      Nhận tại vị trí xe
                    </button>
                    
                    <button
                      type="button"
                      :disabled="!car?.delivery_option || car.delivery_option.status !== 1"
                      :class="[
                        'py-2 px-3 text-xs font-bold rounded-xl border transition-all duration-200 flex items-center justify-center gap-1',
                        receiveMethod === 'delivery'
                          ? 'bg-[#1e4e57] text-white border-[#1e4e57]'
                          : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 disabled:opacity-50 disabled:cursor-not-allowed'
                      ]"
                      @click="receiveMethod = 'delivery'"
                      :title="(!car?.delivery_option || car.delivery_option.status !== 1) ? 'Chủ xe không hỗ trợ giao xe tận nơi' : ''"
                    >
                      <Icon name="lucide:truck" class="w-3.5 h-3.5" />
                      Giao xe tận nơi
                    </button>
                  </div>

                  <!-- Content based on selected method -->
                  <!-- 1. Self-pickup -->
                  <div
                    v-if="receiveMethod === 'pickup'"
                    class="flex items-center gap-2 p-3 border border-slate-200 rounded-xl hover:border-brand-primary transition-colors"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="w-4 h-4 text-brand-primary flex-shrink-0"
                      viewBox="0 0 24 24"
                    >
                      <path
                        fill="currentColor"
                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7m0 9.5a2.5 2.5 0 0 1 0-5a2.5 2.5 0 0 1 0 5"
                      />
                    </svg>
                    <span class="text-sm text-slate-700 font-semibold truncate flex-1">
                      {{ car?.car_location?.address || 'Chưa cập nhật' }}
                    </span>
                    <span class="text-xs font-bold text-green-600 flex-shrink-0">
                      Miễn phí
                    </span>
                  </div>

                  <!-- 2. Delivery Search Autocomplete -->
                  <div v-else-if="receiveMethod === 'delivery'" class="space-y-3">
                    <div class="relative">
                      <input
                        type="text"
                        v-model="deliveryAddress"
                        @input="searchDeliveryPlace"
                        placeholder="Nhập địa chỉ nhận xe..."
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 pr-10 outline-none transition text-xs font-semibold text-slate-700 focus:border-[#1e4e57] focus:ring-2 focus:ring-[#1e4e57]/10"
                      >
                      <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <Icon name="lucide:map-pin" class="w-4 h-4" />
                      </div>

                      <!-- Suggestions Dropdown -->
                      <div
                        v-if="deliverySuggestions.length"
                        class="absolute z-[100] left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl max-h-48 overflow-y-auto divide-y divide-slate-100"
                      >
                        <div
                          v-for="item in deliverySuggestions"
                          :key="item.place_id"
                          class="p-3 hover:bg-slate-50 cursor-pointer text-xs text-slate-700 transition-colors font-medium"
                          @click="selectDeliveryPlace(item)"
                        >
                          {{ item.description }}
                        </div>
                      </div>
                    </div>

                    <!-- Distance & Fee details -->
                    <div v-if="deliveryDistance !== null" class="bg-slate-50 border border-slate-100 rounded-xl p-3 space-y-1 text-xs">
                      <div class="flex justify-between font-semibold">
                        <span class="text-slate-500">Khoảng cách giao xe:</span>
                        <span class="text-slate-800">{{ deliveryDistance }} km</span>
                      </div>
                      <div class="flex justify-between font-semibold" v-if="car?.delivery_option?.free_distance">
                        <span class="text-slate-500">Miễn phí giao xe:</span>
                        <span class="text-green-600">Trong vòng {{ car.delivery_option.free_distance }} km</span>
                      </div>
                      <div class="flex justify-between font-semibold" v-if="deliveryFee > 0">
                        <span class="text-slate-500">Phí giao xe tính thêm:</span>
                        <span class="text-brand-dark font-bold">{{ deliveryFee.toLocaleString('vi-VN') }}đ</span>
                      </div>
                      <div class="flex justify-between font-semibold" v-else>
                        <span class="text-slate-500">Phí giao xe tính thêm:</span>
                        <span class="text-green-600 font-bold">Miễn phí</span>
                      </div>

                      <!-- Warning if too far -->
                      <div v-if="isDistanceTooFar" class="text-rose-500 font-bold text-center pt-1.5 border-t border-rose-100 flex items-center justify-center gap-1">
                        <Icon name="lucide:triangle-alert" class="w-4 h-4" />
                        Vị trí quá xa! Chủ xe chỉ giao tối đa {{ car?.delivery_option?.max_distance }} km.
                      </div>
                    </div>

                    <!-- Map container -->
                    <div
                      id="detail-map"
                      class="w-full rounded-xl overflow-hidden border border-slate-200 shadow-inner"
                      style="height: 200px; display: none;"
                      :style="{ display: deliveryCoords ? 'block' : 'none' }"
                    ></div>
                  </div>
                </div>

                <!-- Chi tiết giá -->
                <div class="space-y-2.5 pt-2 border-t border-slate-100">
                  <div
                    v-for="item in priceDetails"
                    :key="item.label"
                    class="flex items-center justify-between text-sm"
                  >
                    <span
                      class="text-slate-500 font-medium flex items-center gap-1"
                    >
                      {{ item.label }}
                      <svg
                        v-if="item.info"
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-3.5 h-3.5 text-gray-300"
                        viewBox="0 0 24 24"
                      >
                        <path
                          fill="currentColor"
                          d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2m1 15h-2v-6h2zm0-8h-2V7h2z"
                        />
                      </svg>
                    </span>
                    <span
                      :class="
                        item.discount
                          ? 'text-green-600 font-bold'
                          : 'font-semibold text-brand-dark'
                      "
                      >{{ item.value }}</span
                    >
                  </div>
                </div>

                <!-- Tổng -->
                <div
                  class="bg-brand-secondary rounded-xl p-4 flex items-center justify-between"
                >
                  <div>
                    <p class="text-xs text-gray-500 font-medium">Tổng cộng</p>
                    <p class="text-xl font-black text-brand-dark">{{ totalPrice.toLocaleString('vi-VN') }}đ</p>
                  </div>
                  <div class="text-right">
                    <p class="text-xs text-gray-500 font-medium">Tiết kiệm</p>
                    <p class="text-sm font-black text-green-600">-{{ totalSavings.toLocaleString('vi-VN') }}đ</p>
                  </div>
                </div>

                <!-- CTA -->
                <button
                  :disabled="hasActiveBooking || (receiveMethod === 'delivery' && (isDistanceTooFar || !deliveryCoords))"
                  class="w-full bg-brand-primary hover:bg-brand-dark text-white font-extrabold py-4 rounded-2xl transition-all duration-300 text-sm tracking-widest shadow-lg shadow-brand-primary/20 hover:shadow-brand-primary/30 hover:-translate-y-[0.5px] transform disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                  @click="handleBooking"
                >
                  <span v-if="hasActiveBooking">CHUYẾN ĐI CHƯA HOÀN THÀNH</span>
                  <span v-else-if="receiveMethod === 'delivery' && isDistanceTooFar">KHOẢNG CÁCH QUÁ XA</span>
                  <span v-else>CHỌN THUÊ</span>
                </button>

                <!-- Phụ phí -->
                <button
                  class="w-full text-center text-sm text-brand-primary hover:underline font-semibold"
                >
                  Xem phụ phí có thể phát sinh →
                </button>
              </div>
            </div>

            <!-- Báo cáo -->
            <button
              class="w-full flex items-center justify-center gap-2 text-sm text-gray-400 hover:text-red-500 transition-colors py-1"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4"
                viewBox="0 0 24 24"
              >
                <path
                  fill="currentColor"
                  d="M14.4 6L14 4H5v17h2v-7h5.6l.4 2h7V6z"
                />
              </svg>
              Báo cáo xe này
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- XE TƯƠNG TỰ -->
    <div
      class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 border-t border-slate-100 mt-6"
    >
      <h2
        class="text-xl font-black text-brand-dark mb-6 flex items-center gap-2"
      >
        <span class="w-1.5 h-6 bg-brand-primary rounded-full"></span>
        Xe tương tự (cùng hãng {{ car?.car_brand?.name }})
      </h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <NuxtLink
          v-for="simCar in similarCars"
          :key="simCar.id"
          :to="`/vehicles/${simCar.id}`"
          class="bg-white rounded-2xl overflow-hidden border border-slate-100/60 shadow-sm hover:shadow-md hover:border-brand-primary/10 transition-all duration-300 cursor-pointer group block"
        >
          <div class="relative h-36 overflow-hidden">
            <img
              :src="simCar.image"
              :alt="simCar.name"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
            <span
              v-if="simCar.badge"
              class="absolute top-2 left-2 bg-brand-accent text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow-sm"
              >{{ simCar.badge }}</span
            >
          </div>
          <div class="p-3.5">
            <p class="text-xs font-black text-brand-dark truncate">
              {{ simCar.name }}
            </p>
            <div class="flex items-center gap-1 mt-1">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-3 h-3 text-yellow-400 fill-current"
                viewBox="0 0 24 24"
              >
                <path
                  d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2L9.19 8.63L2 9.24l5.46 4.73L5.82 21z"
                />
              </svg>
              <span class="text-[10px] font-bold text-gray-500"
                >{{ simCar.rating }} • {{ simCar.trips }} chuyến</span
              >
            </div>
            <div
              class="flex items-center justify-between mt-3 pt-2 border-t border-slate-50"
            >
              <span class="text-sm font-black text-brand-primary"
                >{{ simCar.price
                }}<span class="text-[10px] font-semibold text-gray-400"
                  >/ngày</span
                ></span
              >
              <div
                class="flex items-center gap-1 text-[10px] text-gray-400 font-bold"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-3 h-3 text-gray-300"
                  viewBox="0 0 24 24"
                >
                  <path
                    fill="currentColor"
                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7m0 9.5a2.5 2.5 0 0 1 0-5a2.5 2.5 0 0 1 0 5"
                  />
                </svg>
                {{ simCar.location }}
              </div>
            </div>
          </div>
        </NuxtLink>
      </div>
    </div>
    
    <!-- Date Picker Modal -->
    <DatePickerModal 
      :is-open="isDatePickerOpen" 
      :initial-start="selectedStart || undefined" 
      :initial-end="selectedEnd || undefined" 
      :disabled-dates="disabledDates"
      @close="isDatePickerOpen = false" 
      @apply="handleApplyDates"
    />
  </div>

  <!-- ════════════════════════════════════════════════════════════
         MODAL CẬP NHẬT THÔNG TIN NHANH (TÍCH HỢP LOGIC UPLOAD CỦA PROFILE)
         ════════════════════════════════════════════════════════════ -->
    <div v-if="isUpdateModalOpen" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
      <!-- Màn đen mờ phía sau (Overlay) -->
      <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm" @click="isUpdateModalOpen = false"></div>
      
      <!-- Khung nội dung Modal -->
      <div class="relative bg-white rounded-3xl w-full max-w-md p-6 md:p-8 shadow-2xl border border-slate-100/80 z-10 flex flex-col max-h-[90vh] overflow-y-auto transform transition-all duration-300 animate-in fade-in zoom-in-95 no-scrollbar">
        
        <!-- Nút đóng nhanh -->
        <button @click="isUpdateModalOpen = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
          <Icon name="ic:outline-close" size="20" />
        </button>

        <!-- Tiêu đề Modal -->
        <div class="mb-5">
          <h3 class="text-xl font-black text-brand-dark flex items-center gap-2">
            <Icon name="ic:outline-stars" class="text-brand-primary" size="24" />
            Xác thực thông tin thuê xe
          </h3>
          <p class="text-xs text-slate-500 mt-1 font-medium">
            Vui lòng hoàn thiện các thông tin còn thiếu bên dưới để tiếp tục chuyến đi của bạn.
          </p>
        </div>

        <!-- Form Xử lý cập nhật -->
        <form @submit.prevent="submitQuickUpdate" class="space-y-5">
          
          <!-- KHU VỰC 1: SỐ ĐIỆN THOẠI (Chỉ hiển thị nếu tài khoản chưa có) -->
          <div v-if="missingFields.phone" class="space-y-1.5">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
              Số điện thoại <span class="text-rose-500">*</span>
            </label>
            <input 
              type="text" 
              v-model="quickUpdateForm.phone" 
              placeholder="Nhập số điện thoại của bạn"
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-brand-primary focus:bg-white focus:ring-2 focus:ring-brand-primary/5 transition-all"
              
            />
          </div>

          <!-- KHU VỰC 2: GIẤY PHÉP LÁI XE (Chỉ hiển thị nếu chưa có hoặc bị từ chối) -->
          <div v-if="missingFields.drivingLicense" class="space-y-4 pt-1">
            <div class="border-t border-dashed border-slate-200 my-2"></div>
            
            <h4 class="text-xs font-extrabold text-brand-dark uppercase tracking-widest flex items-center gap-1.5">
              <Icon name="ic:outline-credit-card" class="text-emerald-500" size="18" />
              Thông tin Giấy phép lái xe
            </h4>

            <!-- Trường Số GPLX -->
            <div class="space-y-1.5">
              <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider">Số GPLX</label>
              <input 
                type="text" 
                v-model="quickUpdateForm.driving_license_number" 
                placeholder="Nhập số GPLX ghi trên thẻ"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-brand-primary focus:bg-white transition-all"
                
              />
            </div>

            <!-- Trường Họ và Tên -->
            <div class="space-y-1.5">
              <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider">Họ và tên</label>
              <input 
                type="text" 
                v-model="quickUpdateForm.full_name" 
                placeholder="Nhập họ và tên đầy đủ viết hoa"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-brand-primary focus:bg-white transition-all"
                
              />
            </div>

            <!-- Trường Ngày Sinh -->
            <div class="space-y-1.5">
              <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ngày sinh</label>
              <input 
                type="date" 
                v-model="quickUpdateForm.DOB" 
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-brand-primary focus:bg-white transition-all"
                
              />
            </div>

            <!-- Vùng Kéo thả & Upload ảnh bằng lái (Bê nguyên cấu trúc mượt mà từ Profile sang) -->
            <div class="space-y-1.5">
              <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ảnh mặt trước GPLX</label>
              
              <div 
                @click="triggerLicenseFileInput" 
                @dragover.prevent="isLicenseDragging = true"
                @dragleave.prevent="isLicenseDragging = false" 
                @drop.prevent="onLicenseDrop"
                class="h-[160px] border-2 border-dashed rounded-xl flex flex-col items-center justify-center cursor-pointer relative overflow-hidden transition-all bg-slate-50/50"
                :class="isLicenseDragging ? 'border-brand-primary bg-brand-primary/5 shadow-inner' : (licenseImagePreview ? 'border-solid border-slate-200 bg-white' : 'border-slate-300 hover:border-brand-primary')"
              >
                <!-- Hiển thị Preview ảnh khi người dùng chọn file -->
                <img v-if="licenseImagePreview" :src="licenseImagePreview" class="w-full h-full object-contain absolute inset-0 p-1" alt="Preview GPLX" />
                
                <!-- Giao diện mặc định khi chưa chọn file -->
                <div v-else class="flex flex-col items-center p-4 text-center">
                  <Icon name="ic:outline-cloud-upload" size="36" class="text-green-500 mb-1.5" />
                  <p class="text-xs text-slate-600 font-bold">Kéo thả ảnh vào đây hoặc nhấp để chọn file</p>
                  <p class="text-[10px] text-slate-400 mt-1 font-medium">Chấp nhận JPG, PNG dung lượng tối đa 5MB</p>
                </div>

                <!-- Input file bị ẩn chạy ngầm dưới nền -->
                <input type="file" ref="licenseFileInputRef" @change="onLicenseFileChange" accept="image/*" class="hidden" />
              </div>
            </div>
          </div>

          <!-- NÚT THAO TÁC (ACTIONS BUTTON) -->
          <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100">
            <!-- Nút Hủy -->
            <button 
              type="button" 
              @click="isUpdateModalOpen = false"
              class="py-3 px-4 border border-slate-200 text-slate-500 font-bold rounded-xl hover:bg-slate-50 hover:text-slate-700 transition-colors focus:outline-none text-xs tracking-wider uppercase"
            >
              Hủy bỏ
            </button>
            
            <!-- Nút Xác nhận & Lưu đơn -->
            <button 
              type="submit" 
              :disabled="isUpdating"
              class="py-3 px-4 bg-brand-primary hover:bg-brand-dark text-white font-bold rounded-xl transition-all duration-200 focus:outline-none text-xs tracking-wider uppercase shadow-md shadow-brand-primary/10 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <Icon v-if="isUpdating" name="svg-spinners:ring-resize" class="w-4 h-4" />
              <span>{{ isUpdating ? 'Đang lưu...' : 'Xác nhận & Thuê xe' }}</span>
            </button>
          </div>

        </form>
      </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted, watch, nextTick } from "vue";
import { useRoute } from "#app";
import { TripStatus } from "~/config/trip-status";
import { carService } from "~/services/car.service";
import { favoriteService } from "~/services/favorite.service";
import { notificationService } from "~/services/notification.service";
import DatePickerModal from "~/components/Shared/DatePickerModal.vue";

definePageMeta({ layout: "vehicle-detail" });

const route = useRoute();
const carId = route.params.id as string;
const { user, updateProfile, submitDrivingLicense } = useAuth();
const { showToast } = useToast();
const { openLogin } = useAuthModal();

const car = ref<any>(null);
const loading = ref(true);
const error = ref<string | null>(null);
const isFavorite = ref(false);

// Goong Map for Delivery Location
const MAP_KEY = '8Gh3kHiOvTsc6QHzNT4Aq0aFjH2I69PNiFyzk5Ex'
const API_KEY = 'xEcFmnV3loWHnfqa9ZsEENH7Wu6lehK4QmabQk7V'

let maplibregl: any = null
const receiveMethod = ref<'pickup' | 'delivery'>('pickup')
const deliveryAddress = ref('')
const deliverySuggestions = ref<any[]>([])
const deliveryCoords = ref<{ lat: number; lng: number } | null>(null)
const deliveryDistance = ref<number | null>(null)
const deliveryFee = ref<number>(0)
const isDistanceTooFar = ref(false)

const detailMapRef = ref<any>(null)
const carMarker = ref<any>(null)
const userMarker = ref<any>(null)

const getHaversineDistance = (lat1: number, lon1: number, lat2: number, lon2: number) => {
  const R = 6371 // Earth radius in km
  const dLat = (lat2 - lat1) * Math.PI / 180
  const dLon = (lon2 - lon1) * Math.PI / 180
  const a =
    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
    Math.sin(dLon / 2) * Math.sin(dLon / 2)
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a))
  return R * c
}

const calculateDistance = async (carLat: number, carLng: number, userLat: number, userLng: number) => {
  try {
    const res = await fetch(`https://rsapi.goong.io/DistanceMatrix?origins=${carLat},${carLng}&destinations=${userLat},${userLng}&vehicle=car&api_key=${API_KEY}`)
    const data = await res.json()
    if (data.rows && data.rows[0] && data.rows[0].elements && data.rows[0].elements[0] && data.rows[0].elements[0].status === 'OK') {
      return data.rows[0].elements[0].distance.value / 1000 // In km
    }
  } catch (e) {
    console.error("Lỗi khi tính khoảng cách qua Goong API. Dùng Haversine làm phương án dự phòng:", e)
  }
  return getHaversineDistance(carLat, carLng, userLat, userLng)
}

const searchDeliveryPlace = async () => {
  if (!deliveryAddress.value) {
    deliverySuggestions.value = []
    return
  }
  try {
    const res = await fetch(`https://rsapi.goong.io/Place/AutoComplete?api_key=${API_KEY}&input=${encodeURIComponent(deliveryAddress.value)}`)
    const data = await res.json()
    deliverySuggestions.value = data.predictions || []
  } catch (error) {
    console.error('Lỗi khi tìm kiếm địa điểm:', error)
  }
}

const selectDeliveryPlace = async (item: any) => {
  deliveryAddress.value = item.description
  deliverySuggestions.value = []
  try {
    const res = await fetch(`https://rsapi.goong.io/Place/Detail?place_id=${item.place_id}&api_key=${API_KEY}`)
    const data = await res.json()
    if (data.result && data.result.geometry) {
      const loc = data.result.geometry.location
      deliveryCoords.value = { lat: loc.lat, lng: loc.lng }

      if (car.value && car.value.car_location) {
        const carLocStr = car.value.car_location.location || ''
        const [carLat, carLng] = carLocStr.split(',').map(Number)

        if (carLat && carLng) {
          const dist = await calculateDistance(carLat, carLng, loc.lat, loc.lng)
          deliveryDistance.value = parseFloat(dist.toFixed(1))

          const deliveryOpt = car.value.delivery_option
          if (deliveryOpt) {
            const maxDist = deliveryOpt.max_distance || 0
            const freeDist = deliveryOpt.free_distance || 0
            const feeDistance = deliveryOpt.fee_distance || 0

            if (dist > maxDist) {
              isDistanceTooFar.value = true
              deliveryFee.value = 0
              showToast(`Địa điểm vượt quá khoảng cách giao xe tối đa của chủ xe (${maxDist} km)`, 'error')
            } else {
              isDistanceTooFar.value = false
              if (dist <= freeDist) {
                deliveryFee.value = 0
              } else {
                const extraKm = dist - freeDist
                deliveryFee.value = Math.round(extraKm * feeDistance)
              }
            }
          }

          nextTick(() => {
            drawDeliveryMap(carLat, carLng, loc.lat, loc.lng)
          })
        }
      }
    }
  } catch (error) {
    console.error('Lỗi khi lấy thông tin chi tiết địa điểm:', error)
  }
}

const drawDeliveryMap = (carLat: number, carLng: number, userLat: number, userLng: number) => {
  const container = document.getElementById('detail-map')
  if (!container || !maplibregl) return

  if (detailMapRef.value) {
    try {
      detailMapRef.value.remove()
    } catch (e) {
      console.error('Lỗi khi xóa bản đồ cũ:', e)
    }
    detailMapRef.value = null
    carMarker.value = null
    userMarker.value = null
  }

  const map = new maplibregl.Map({
    container: 'detail-map',
    style: `https://tiles.goong.io/assets/goong_map_web.json?api_key=${MAP_KEY}`,
    center: [(carLng + userLng) / 2, (carLat + userLat) / 2],
    zoom: 12
  })

  map.addControl(new maplibregl.NavigationControl(), 'top-right')

  map.on('load', () => {
    carMarker.value = new maplibregl.Marker({ color: '#1e4e57' })
      .setLngLat([carLng, carLat])
      .setPopup(new maplibregl.Popup({ offset: 25 }).setText("Vị trí xe"))
      .addTo(map)

    userMarker.value = new maplibregl.Marker({ color: 'red' })
      .setLngLat([userLng, userLat])
      .setPopup(new maplibregl.Popup({ offset: 25 }).setText("Địa điểm giao xe"))
      .addTo(map)
      .togglePopup()

    const bounds = new maplibregl.LngLatBounds()
    bounds.extend([carLng, carLat])
    bounds.extend([userLng, userLat])
    map.fitBounds(bounds, { padding: 40 })

    setTimeout(() => {
      map.resize()
    }, 200)
  })

  detailMapRef.value = map
}

const calculatedDeliveryFee = computed(() => {
  if (receiveMethod.value === 'pickup') return 0
  return deliveryFee.value
})

const handleBooking = async () => {
  if (!selectedStart.value || !selectedEnd.value) {
    showToast('Vui lòng chọn thời gian nhận và trả xe.', 'warning')
    return
  }

  // Kiểm tra trùng lịch bận
  if (disabledDates.value.length > 0) {
    const start = selectedStart.value
    const end = selectedEnd.value
    const overlap = disabledDates.value.some((range: any) => {
      return start <= range.end && end >= range.start
    })
    if (overlap) {
      showToast('Thời gian thuê trùng với lịch xe đã bận. Vui lòng chọn thời gian khác.', 'error')
      return
    }
  }

  if (!user.value) {
    showToast('Vui lòng đăng nhập để thực hiện đặt xe.', 'warning')
    openLogin()
    return
  }

  const drivingLicense = user.value.driving_license

if (drivingLicense && drivingLicense.status === 0) {
  showToast('Giấy phép lái xe của bạn đang chờ duyệt. Vui lòng đợi quản trị viên phê duyệt để thuê xe.', 'warning')
  return
}

const isPhoneMissing = !user.value.phone
const isLicenseMissing = !drivingLicense || drivingLicense.status === 2

if (isPhoneMissing || isLicenseMissing) {
  missingFields.value = { phone: isPhoneMissing, drivingLicense: isLicenseMissing }
  
  // Điền sẵn data cũ (nếu có) vào form modal
  quickUpdateForm.value.phone = user.value.phone || ''
  quickUpdateForm.value.driving_license_number = drivingLicense?.driving_license_number || ''
  quickUpdateForm.value.full_name = drivingLicense?.full_name || ''
  quickUpdateForm.value.DOB = drivingLicense?.DOB || ''
  
  // Reset trạng thái file ảnh tạm
  licenseImagePreview.value = ''
  licenseImageFile.value = null
  
  // Bật modal lên và dừng luồng xử lý đơn hàng tại đây
  isUpdateModalOpen.value = true
  return
}

  // Kiểm tra số điện thoại
  // if (!user.value.phone) {
  //   showToast('Bạn chưa cập nhật số điện thoại. Đang chuyển hướng đến trang cá nhân...', 'warning')
  //   setTimeout(() => {
  //     navigateTo('/profile')
  //   }, 2000)
  //   return
  // }

  // Kiểm tra giấy phép lái xe
  // const drivingLicense = user.value.driving_license
  // if (!drivingLicense) {
  //   showToast('Bạn chưa cập nhật thông tin giấy phép lái xe. Đang chuyển hướng đến trang cá nhân...', 'warning')
  //   setTimeout(() => {
  //     navigateTo('/profile')
  //   }, 2000)
  //   return
  // }

  // if (drivingLicense.status === 0) {
  //   showToast('Giấy phép lái xe của bạn đang chờ duyệt. Vui lòng đợi quản trị viên phê duyệt để thuê xe.', 'warning')
  //   return
  // }

  // if (drivingLicense.status === 2) {
  //   showToast('Giấy phép lái xe của bạn đã bị từ chối. Đang chuyển hướng đến trang cá nhân để cập nhật...', 'warning')
  //   setTimeout(() => {
  //     navigateTo('/profile')
  //   }, 2000)
  //   return
  // }

  if (drivingLicense.status !== 1) {
    showToast('Giấy phép lái xe của bạn không hợp lệ.', 'error')
    return
  }

  if (receiveMethod.value === 'delivery') {
    if (!deliveryCoords.value) {
      showToast('Vui lòng chọn địa điểm nhận xe.', 'warning')
      return
    }
    if (isDistanceTooFar.value) {
      showToast('Địa điểm giao xe vượt quá khoảng cách tối đa của chủ xe.', 'error')
      return
    }
  }

  if (car.value && car.value.user_id === user.value.id) {
    showToast('Bạn không thể thuê xe của chính mình!', 'error')
    return
  }

  if (hasActiveBooking.value) {
    showToast('Bạn đang có chuyến đi chưa hoàn thành với xe này!', 'error')
    return
  }

  try {
    const pad = (num: number) => String(num).padStart(2, '0')
    const formatFullDate = (d: Date) => `${pad(d.getDate())}/${pad(d.getMonth() + 1)}/${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}`
    const startStr = selectedStart.value ? formatFullDate(selectedStart.value) : ''
    const endStr = selectedEnd.value ? formatFullDate(selectedEnd.value) : ''

    const formatDbDate = (d: Date) => {
      const year = d.getFullYear()
      const month = String(d.getMonth() + 1).padStart(2, '0')
      const day = String(d.getDate()).padStart(2, '0')
      const hours = String(d.getHours()).padStart(2, '0')
      const minutes = String(d.getMinutes()).padStart(2, '0')
      const seconds = String(d.getSeconds()).padStart(2, '0')
      return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`
    }

    // 1. Tạo Trip trên hệ thống (status = 5: Chờ duyệt)
    const tripPayload = {
      cost: totalPrice.value,
      discount_amount: totalSavings.value,
      trip_type: 0, // thuê theo ngày
      start_at: selectedStart.value ? formatDbDate(selectedStart.value) : '',
      end_at: selectedEnd.value ? formatDbDate(selectedEnd.value) : '',
      car_id: car.value.id,
      delivery_address: receiveMethod.value === 'delivery' ? deliveryAddress.value : car.value.car_location?.address,
      delivery_location: receiveMethod.value === 'delivery' && deliveryCoords.value 
        ? `${deliveryCoords.value.lat},${deliveryCoords.value.lng}` 
        : car.value.car_location?.location,
    }

    const tripRes = await carService.createTrip(tripPayload)

    if (tripRes && tripRes.success) {
      // 2. Gửi thông báo đến chủ xe
      const message = `Khách hàng ${user.value.name} (${user.value.phone || 'Chưa cập nhật SĐT'}) gửi yêu cầu thuê xe ${car.value.name} (${car.value.license_plate}) từ ${startStr} đến ${endStr}. Tổng tiền: ${totalPrice.value.toLocaleString('vi-VN')}đ. Trạng thái: Đang chờ duyệt.`

      const notifPayload = {
        message: message,
        user_id: car.value.user_id
      }

      await notificationService.createNotification(notifPayload)
      showToast('Gửi yêu cầu thuê xe thành công! Chuyến đi đang chờ chủ xe duyệt.', 'success')
      navigateTo('/profile/my-trips')
    } else {
      showToast(tripRes.message || 'Gửi yêu cầu thuê xe thất bại.', 'error')
    }
  } catch (error: any) {
    console.error('Lỗi khi gửi yêu cầu thuê xe:', error)
    const errMsg = error.response?._data?.message || 'Có lỗi xảy ra khi gửi yêu cầu thuê xe.'
    showToast(errMsg, 'error')
  }
}

watch(receiveMethod, (newMethod) => {
  if (newMethod === 'pickup') {
    deliveryDistance.value = null
    deliveryCoords.value = null
    isDistanceTooFar.value = false
    deliveryFee.value = 0
  }
})

const parseDateString = (str: string | null | undefined): Date | null => {
  if (!str) return null
  const formattedStr = str.replace(' ', 'T')
  const date = new Date(formattedStr)
  return isNaN(date.getTime()) ? null : date
}

const selectedStart = ref<Date | null>(parseDateString(route.query.startDate as string))
const selectedEnd = ref<Date | null>(parseDateString(route.query.endDate as string))

// Set default values if not defined in query
if (!selectedStart.value) {
  const d = new Date()
  d.setDate(d.getDate() + 1)
  d.setHours(21, 0, 0, 0)
  selectedStart.value = d
}
if (!selectedEnd.value) {
  const d = new Date()
  d.setDate(d.getDate() + 3)
  d.setHours(20, 0, 0, 0)
  selectedEnd.value = d
}

const isDatePickerOpen = ref(false)

const formattedStart = computed(() => {
  if (!selectedStart.value) return ''
  const d = selectedStart.value
  const pad = (num: number) => String(num).padStart(2, '0')
  return `${pad(d.getHours())}:${pad(d.getMinutes())}, ${pad(d.getDate())}/${pad(d.getMonth() + 1)}`
})

const formattedEnd = computed(() => {
  if (!selectedEnd.value) return ''
  const d = selectedEnd.value
  const pad = (num: number) => String(num).padStart(2, '0')
  return `${pad(d.getHours())}:${pad(d.getMinutes())}, ${pad(d.getDate())}/${pad(d.getMonth() + 1)}`
})

const disabledDates = computed(() => {
  console.log("Raw trips from backend:", car.value?.trips)
  if (!car.value?.trips || car.value.trips.length === 0) return []
  const mapped = car.value.trips.map((trip: any) => {
    const startStr = typeof trip.start_at === 'string' ? trip.start_at.replace(' ', 'T') : trip.start_at
    const endStr = typeof trip.end_at === 'string' ? trip.end_at.replace(' ', 'T') : trip.end_at
    const start = new Date(startStr)
    const end = new Date(endStr)
    return { start, end }
  })
  console.log("Mapped disabledDates:", mapped)
  return mapped
})

// Check if default dates overlap with busy dates on load
watch(disabledDates, (newDisabled) => {
  if (selectedStart.value && selectedEnd.value && newDisabled.length > 0) {
    const start = selectedStart.value
    const end = selectedEnd.value
    const overlap = newDisabled.some((range: any) => {
      return start <= range.end && end >= range.start
    })
    if (overlap) {
      showToast('Lưu ý: Thời gian mặc định trùng với lịch bận của xe. Vui lòng chọn lịch khác!', 'warning')
      selectedStart.value = null
      selectedEnd.value = null
    }
  }
})

const rentalDays = computed(() => {
  if (!selectedStart.value || !selectedEnd.value) return 1
  const ms = selectedEnd.value.getTime() - selectedStart.value.getTime()
  const days = Math.max(1, Math.ceil(ms / (1000 * 60 * 60 * 24)))
  return days
})

const handleApplyDates = (payload: any) => {
  selectedStart.value = payload.start
  selectedEnd.value = payload.end
  isDatePickerOpen.value = false
}

const showFullDesc = ref(false);
const activeIndex = ref(0);

const rawSimilarCars = ref<any[]>([]);

const normalizeTransmission = (trans: string) => {
  if (!trans) return 'Số tự động';
  const lower = trans.toLowerCase();
  if (lower.includes('tự động') || lower.includes('auto') || lower.includes('at')) {
    return 'Số tự động';
  }
  if (lower.includes('sàn') || lower.includes('manual') || lower.includes('mt')) {
    return 'Số sàn';
  }
  return trans;
}

const normalizeFuel = (fuel: string) => {
  if (!fuel) return 'Xăng';
  const lower = fuel.toLowerCase();
  if (lower.includes('xăng') || lower.includes('gasoline') || lower.includes('petrol')) {
    return 'Xăng';
  }
  if (lower.includes('dầu') || lower.includes('diesel')) {
    return 'Dầu';
  }
  if (lower.includes('điện') || lower.includes('electric') || lower.includes('ev')) {
    return 'Điện';
  }
  return fuel;
}

const checkFavoriteStatus = async (id: string) => {
  if (!user.value) {
    isFavorite.value = false;
    return;
  }
  try {
    const res = await favoriteService.getFavorites();
    if (res.success && res.data) {
      isFavorite.value = res.data.some((fav) => fav.car_id === parseInt(id));
    }
  } catch (error) {
    console.error("Lỗi khi kiểm tra trạng thái yêu thích:", error);
  }
};

const handleToggleFavorite = async () => {
  if (!user.value) {
    showToast("Vui lòng đăng nhập để lưu xe yêu thích!", "warning");
    openLogin();
    return;
  }

  const currentCarId = car.value?.id;
  if (!currentCarId) return;

  try {
    if (isFavorite.value) {
      const res = await favoriteService.removeFavorite(currentCarId);
      if (res.success) {
        isFavorite.value = false;
        showToast("Đã xóa khỏi danh sách yêu thích!", "success");
      }
    } else {
      const res = await favoriteService.addFavorite(currentCarId);
      if (res.success) {
        isFavorite.value = true;
        showToast("Đã thêm vào danh sách yêu thích!", "success");
      }
    }
  } catch (error) {
    console.error("Lỗi khi thay đổi trạng thái yêu thích:", error);
    showToast("Đã có lỗi xảy ra!", "error");
  }
};

const loadCarDetails = async (id: string) => {
  loading.value = true;
  error.value = null;
  activeIndex.value = 0;
  try {
    const response = await carService.getCarById(id);
    if (response.success && response.data) {
      car.value = response.data;
      
      // Kiểm tra nếu là chủ xe thì không được phép truy cập
      if (user.value && car.value.user_id === user.value.id) {
        showToast('Bạn không thể truy cập chi tiết xe của chính mình!', 'warning');
        navigateTo('/vehicle-list');
        return;
      }
      
      // Kiểm tra xem xe này có nằm trong danh sách yêu thích hay không
      await checkFavoriteStatus(id);

      // Tải các xe liên quan cùng hãng
      let similarData: any[] = [];
      const brandResponse = await carService.getCars({ brand_id: car.value.car_brand_id });
      if (brandResponse.success && brandResponse.data) {
        similarData = brandResponse.data.filter((c: any) => c.id !== car.value.id);
      }

      // Nếu không đủ 4 xe cùng hãng, lấy thêm các xe cùng loại (type)
      if (similarData.length < 4) {
        const typeResponse = await carService.getCars({ type_id: car.value.car_type_id });
        if (typeResponse.success && typeResponse.data) {
          const typeCars = typeResponse.data.filter((c: any) => c.id !== car.value.id && !similarData.some(sd => sd.id === c.id));
          similarData = [...similarData, ...typeCars];
        }
      }

      // Nếu vẫn chưa đủ, lấy thêm danh sách xe nói chung
      if (similarData.length < 4) {
        const generalResponse = await carService.getCars();
        if (generalResponse.success && generalResponse.data) {
          const generalCars = generalResponse.data.filter((c: any) => c.id !== car.value.id && !similarData.some(sd => sd.id === c.id));
          similarData = [...similarData, ...generalCars];
        }
      }

      rawSimilarCars.value = similarData.slice(0, 4);
    } else {
      error.value = response.message || "Không tìm thấy thông tin xe";
    }
  } catch (err) {
    console.error("Lỗi khi tải thông tin xe:", err);
    error.value = "Có lỗi xảy ra khi kết nối tới máy chủ";
  } finally {
    loading.value = false;
  }
}

onMounted(async () => {
  loadCarDetails(carId);
  if (process.client) {
    try {
      const module = await import('maplibre-gl');
      maplibregl = module.default;
      await import('maplibre-gl/dist/maplibre-gl.css');
    } catch (e) {
      console.error('Không tải được thư viện bản đồ:', e);
    }
  }
});

// Watch thay đổi ID trên URL để tải lại dữ liệu khi đổi xe liên quan
watch(() => route.params.id, (newId) => {
  if (newId) {
    loadCarDetails(newId as string);
  }
});

// Watch thay đổi thông tin user đăng nhập để chuyển hướng nếu sở hữu xe
watch(() => user.value, (newUser) => {
  if (newUser && car.value && car.value.user_id === newUser.id) {
    showToast('Bạn không thể truy cập chi tiết xe của chính mình!', 'warning');
    navigateTo('/vehicle-list');
  }
});

// Computed values based on car data
const carImages = computed(() => {
  if (!car.value?.images || car.value.images.length === 0) {
    return ["https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=1000"];
  }
  return car.value.images.map((img: any) => img.image_url);
});

const specs = computed(() => {
  if (!car.value) return [];
  return [
    {
      label: "Truyền động",
      value: normalizeTransmission(car.value.transmission),
      icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><path fill="currentColor" d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>',
    },
    {
      label: "Số ghế",
      value: `${car.value.seat_count} chỗ`,
      icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><path fill="currentColor" d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3m-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3m0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5m8 0c-.29 0-.62.02-.97.05c1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5"/></svg>',
    },
    {
      label: "Nhiên liệu",
      value: normalizeFuel(car.value.fuel_type),
      icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><path fill="currentColor" d="M19.77 7.23l.01-.01l-3.72-3.72L15 4.56l2.11 2.11c-.94.36-1.61 1.26-1.61 2.33a2.5 2.5 0 0 0 2.5 2.5c.36 0 .69-.08 1-.21v7.21c0 .55-.45 1-1 1s-1-.45-1-1V14c0-1.1-.9-2-2-2h-1V5c0-1.1-.9-2-2-2H6c-1.1 0-2 .9-2 2v16h10v-7.5h1.5v5a2.5 2.5 0 0 0 5 0V9c0-.69-.28-1.32-.73-1.77M18 10.5c-.55 0-1-.45-1-1s.45-1 1-1s1 .45 1 1s-.45 1-1 1M6 10V5h5v5z"/></svg>',
    },
    {
      label: "Tiêu hao",
      value: `${car.value.fuel_consumption}L/100km`,
      icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m3.88-11.71L10 10.5l-1.71 5.29l5.88-1.71z"/></svg>',
    },
  ];
});

const amenities = computed(() => {
  if (!car.value?.features || car.value.features.length === 0) {
    return [
      { name: "Bluetooth", icon: null },
      { name: "Camera lùi", icon: null },
      { name: "Định vị GPS", icon: null },
      { name: "Khe cắm USB", icon: null },
      { name: "Lốp dự phòng", icon: null },
      { name: "ETC", icon: null },
      { name: "Túi khí an toàn", icon: null },
      { name: "Cửa sổ trời", icon: null },
    ];
  }
  return car.value.features.map((f: any) => ({
    name: f.feature_name,
    icon: f.icon || null
  }));
});

const descItems = [
  "Giao xe tận nơi",
  "Miễn thế chấp",
  "Hỗ trợ 24/7",
  "Thủ tục nhanh gọn",
  "Xe sạch sẽ, bảo dưỡng định kỳ",
  "Bảo hiểm chuyến đi",
];

const documents = [
  {
    title: "GPLX + CCCD gắn chip",
    desc: "Đối chiếu bản gốc khi nhận xe",
  },
  {
    title: "GPLX + Hộ chiếu",
    desc: "Áp dụng cho khách quốc tế hoặc Việt kiều",
  },
];

const cancelPolicies = [
  { time: "Trong vòng 1h sau khi đặt xe", fee: "Miễn phí" },
  {
    time: "Trước chuyến đi 7 ngày (Sau 1h khi đặt)",
    fee: "10% giá trị chuyến đi",
  },
  {
    time: "Trong vòng 7 ngày trước chuyến đi (Sau 1h khi đặt)",
    fee: "40% giá trị chuyến đi",
  },
];

const hostStats = [
  { label: "Tỉ lệ phản hồi", value: "100%" },
  { label: "Phản hồi trong", value: "5 phút" },
  { label: "Tỉ lệ đồng ý", value: "100%" },
];

const priceDetails = computed(() => {
  if (!car.value || !selectedStart.value || !selectedEnd.value) return [];
  const unitPrice = car.value.unit_price;
  const insuranceFee = Math.round(unitPrice * 0.09); // Phí bảo hiểm 9%
  const deliveryFeeVal = calculatedDeliveryFee.value;
  const discountVal = car.value.discount_value || 0;
  const days = rentalDays.value;
  
  const details = [
    { label: "Đơn giá thuê", value: `${(unitPrice * days).toLocaleString('vi-VN')}đ (${days} ngày)`, info: true },
    { label: "Bảo hiểm thuê xe", value: `${(insuranceFee * days).toLocaleString('vi-VN')}đ (${days} ngày)`, info: true },
  ];

  if (deliveryFeeVal > 0) {
    details.push({ label: "Phí giao nhận xe", value: `${deliveryFeeVal.toLocaleString('vi-VN')}đ`, info: false });
  } else if (receiveMethod.value === 'delivery') {
    details.push({ label: "Phí giao nhận xe", value: `Miễn phí`, info: false });
  }

  if (discountVal > 0) {
    details.push({
      label: "Chương trình giảm giá",
      value: `-${(discountVal * days).toLocaleString('vi-VN')}đ`,
      info: false,
      discount: true,
    });
  }

  return details;
});

const totalSavings = computed(() => {
  if (!car.value || !selectedStart.value || !selectedEnd.value) return 0;
  return (car.value.discount_value || 0) * rentalDays.value;
});

const totalPrice = computed(() => {
  if (!car.value) return 0;
  if (!selectedStart.value || !selectedEnd.value) return 0;
  const unitPrice = car.value.unit_price;
  const insuranceFee = Math.round(unitPrice * 0.09);
  const deliveryFeeVal = calculatedDeliveryFee.value;
  const discountVal = car.value.discount_value || 0;
  return (unitPrice + insuranceFee - discountVal) * rentalDays.value + deliveryFeeVal;
});

const formattedReviews = computed(() => {
  if (!car.value?.reviews || car.value.reviews.length === 0) {
    return [
      {
        name: "Nguyễn Văn Hoài Thương",
        date: "15/06/2025",
        color: "#286874",
        text: "Xe sạch đẹp, chủ xe nhiệt tình. Chuyến đi rất tuyệt vời, sẽ thuê lại lần sau!",
      },
      {
        name: "Nguyễn Minh Nghĩa",
        date: "11/06/2025",
        color: "#A77E52",
        text: "Xe mới, êm, tiết kiệm xăng. Thủ tục nhanh gọn, không rắc rối. Highly recommended!",
      },
    ];
  }
  const colors = ["#286874", "#A77E52", "#1e4e57", "#286874"];
  return car.value.reviews.map((r: any, idx: number) => ({
    name: r.reviewer?.name || "Khách hàng",
    date: new Date(r.created_at || Date.now()).toLocaleDateString('vi-VN'),
    color: colors[idx % colors.length],
    text: r.comment || "Đánh giá tốt!",
  }));
});

const similarCars = computed(() => {
  return rawSimilarCars.value.map((c: any) => {
    const thumbnailImg = c.images?.find((img: any) => img.is_thumbnail === 1)?.image_url 
        || c.images?.[0]?.image_url 
        || 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=600';
    
    const discountPct = c.unit_price > 0 && c.discount_value > 0
        ? Math.round((c.discount_value / c.unit_price) * 100)
        : 0;

    return {
      id: c.id,
      name: c.name,
      image: thumbnailImg,
      price: c.unit_price.toLocaleString('vi-VN') + 'đ',
      location: c.car_location?.address || 'Chưa cập nhật',
      rating: c.reviews_avg_rating ? parseFloat(c.reviews_avg_rating).toFixed(1) : '5.0',
      trips: c.trips_count || 0,
      badge: discountPct > 0 ? `-${discountPct}%` : null
    };
  });
});

const hasActiveBooking = computed(() => {
  if (!user.value || !car.value?.trips) return false;
  return car.value.trips.some((trip: any) => 
    trip.user_id === user.value.id && [TripStatus.Pending, TripStatus.WaitingPayment, TripStatus.Confirmed, TripStatus.Ongoing].includes(Number(trip.status))
  );
});

// ============================================================================
// bổ sung các state & hàm xử lý cho Modal cập nhật nhanh SĐT + GPLX
// ============================================================================


const isUpdateModalOpen = ref(false);
const isUpdating = ref(false);
const missingFields = ref({ phone: false, drivingLicense: false });

// Form lưu trữ data tạm trên Modal
const quickUpdateForm = ref({
  phone: '',
  driving_license_number: '',
  full_name: '',
  DOB: ''
});

// State quản lý việc kéo thả & upload ảnh GPLX
const isLicenseDragging = ref(false);
const licenseFileInputRef = ref<HTMLInputElement | null>(null);
const licenseImageFile = ref<File | null>(null);
const licenseImagePreview = ref<string>('');

// Các hàm xử lý kích hoạt File Input & Kéo thả ảnh
const triggerLicenseFileInput = () => {
  if (licenseFileInputRef.value) licenseFileInputRef.value.click();
};

const onLicenseFileChange = (e: Event) => {
  const input = e.target as HTMLInputElement;
  if (input.files && input.files[0]) setLicenseFile(input.files[0]);
};

const onLicenseDrop = (e: DragEvent) => {
  isLicenseDragging.value = false;
  if (e.dataTransfer?.files && e.dataTransfer.files[0]) setLicenseFile(e.dataTransfer.files[0]);
};

const setLicenseFile = (file: File) => {
  if (!file.type.startsWith('image/')) {
    showToast('Vui lòng chọn một tệp hình ảnh hợp lệ.', 'error');
    return;
  }
  if (file.size > 5 * 1024 * 1024) {
    showToast('Dung lượng ảnh vượt quá 5MB.', 'error');
    return;
  }
  licenseImageFile.value = file;
  licenseImagePreview.value = URL.createObjectURL(file);
};

// Hàm xử lý lưu Form từ Modal lên Database
const submitQuickUpdate = async () => {
  if (missingFields.value.drivingLicense && !user.value?.driving_license && !licenseImageFile.value) {
    showToast('Vui lòng tải lên ảnh mặt trước bằng lái xe.', 'error');
    return;
  }

  isUpdating.value = true;
  try {
    // ════════════════════════════════════════════════════════════
    //  BẮT LỖI SỐ ĐIỆN THOẠI (Nếu đang hiển thị ô nhập SĐT)
    // ════════════════════════════════════════════════════════════
    if (missingFields.value.phone) {
      const phoneInput = quickUpdateForm.value.phone.trim();
      
      if (!phoneInput) {
        showToast('Vui lòng không để trống Số điện thoại.', 'error');
        isUpdating.value = false;
        return;
      }
      
      const phoneRegex = /^0\d{9}$/;
      if (!phoneRegex.test(phoneInput)) {
        showToast('Số điện thoại không hợp lệ! Phải bắt đầu bằng số 0 và có đúng 10  số.', 'error');
        isUpdating.value = false;
        return;
      }

      //  KHÔI PHỤC API: Bắn request cập nhật SĐT lên Database hệ thống
      const profileRes = await updateProfile({
        name: user.value.name || '',
        phone: phoneInput,
        gender: user.value.gender !== undefined ? user.value.gender : 1,
        DOB: user.value.DOB || ''
      });

      if (profileRes.success) {
        user.value.phone = phoneInput;
        if (typeof window !== "undefined") {
          localStorage.setItem("USER_INFO", JSON.stringify(user.value));
        }
      } else {
        showToast(profileRes.message || 'Cập nhật số điện thoại thất bại.', 'error');
        isUpdating.value = false;
        return;
      }
    }

    // ════════════════════════════════════════════════════════════
    //  BẮT LỖI GIẤY PHÉP LÁI XE (Nếu đang hiển thị form GPLX)
    // ════════════════════════════════════════════════════════════
    if (missingFields.value.drivingLicense) {
      const licenseNumber = quickUpdateForm.value.driving_license_number.trim();
      const fullName = quickUpdateForm.value.full_name.trim();
      const dob = quickUpdateForm.value.DOB;

      if (!licenseNumber || !fullName || !dob) {
        showToast('Vui lòng điền đầy đủ thông tin Họ tên, Số GPLX và Ngày sinh.', 'error');
        isUpdating.value = false;
        return;
      }

      const licenseRegex = /^\d{9,12}$/;
      if (!licenseRegex.test(licenseNumber)) {
        showToast('Số GPLX không hợp lệ! Độ dài chuẩn phải từ 9 đến 12 chữ số.', 'error');
        isUpdating.value = false;
        return;
      }
    }

    // 2. Tải ảnh lên Cloudinary & Lưu Giấy phép lái xe lên DB nếu đang thiếu
    if (missingFields.value.drivingLicense) {
      let imageUrl = user.value?.driving_license?.image || '';

      if (licenseImageFile.value) {
        const CLOUD_NAME = "djbobb5oe";
        const UPLOAD_PRESET = "Drivio";

        const cloudinaryData = new FormData();
        cloudinaryData.append("file", licenseImageFile.value);
        cloudinaryData.append("upload_preset", UPLOAD_PRESET);

        const response = await $fetch<any>(
          `https://api.cloudinary.com/v1_1/${CLOUD_NAME}/image/upload`,
          { method: "POST", body: cloudinaryData }
        );

        imageUrl = response.secure_url;

        if (licenseImagePreview.value.startsWith('blob:')) {
          URL.revokeObjectURL(licenseImagePreview.value);
        }
      }

      const formData = new FormData();
      formData.append('driving_license_number', quickUpdateForm.value.driving_license_number);
      formData.append('full_name', quickUpdateForm.value.full_name);
      formData.append('DOB', quickUpdateForm.value.DOB);
      formData.append('image', imageUrl);

      const licenseRes = await submitDrivingLicense(formData);
      if (licenseRes.success) {
        user.value.driving_license = {
          driving_license_number: quickUpdateForm.value.driving_license_number,
          full_name: quickUpdateForm.value.full_name,
          DOB: quickUpdateForm.value.DOB,
          image: imageUrl,
          status: 0 // Quay về trạng thái Chờ duyệt
        };
        if (typeof window !== "undefined") {
          localStorage.setItem("USER_INFO", JSON.stringify(user.value));
        }
      } else {
        showToast(licenseRes.message || 'Gửi duyệt bằng lái xe thất bại.', 'error');
        isUpdating.value = false;
        return;
      }
    }

    showToast('Cập nhật thông tin thành công! Hãy nhấn nút đặt xe lại nha.', 'success');
    isUpdateModalOpen.value = false;

  } catch (err) {
    console.error('Lỗi khi cập nhật nhanh:', err);
    showToast('Đã xảy ra lỗi, vui lòng thử lại.', 'error');
  } finally {
    isUpdating.value = false;
  }
};
</script>

<style scoped>
/* Ẩn thanh cuộn cho Chrome, Safari, Opera và các trình duyệt dùng Webkit */
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

/* Ẩn thanh cuộn cho IE, Edge và Firefox */
.no-scrollbar {
  -ms-overflow-style: none;  /* IE và Edge */
  scrollbar-width: none;  /* Firefox */
}
</style>
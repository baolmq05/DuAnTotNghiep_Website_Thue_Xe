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
                      >{{ car?.car_location?.street_name || 'Chưa cập nhật' }}</span
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
                  class="p-2.5 rounded-xl border border-slate-200 hover:border-rose-200 hover:text-rose-600 text-slate-500 hover:bg-rose-50 transition-all duration-200"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    viewBox="0 0 24 24"
                  >
                    <path
                      fill="currentColor"
                      d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54z"
                    />
                  </svg>
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
                :key="amenity"
                class="flex items-center gap-3 text-sm text-slate-700 bg-slate-50/40 p-3 rounded-xl border border-slate-100/60 hover:border-brand-primary/20 hover:bg-white transition-all duration-200"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-4 h-4 text-brand-primary flex-shrink-0"
                  viewBox="0 0 24 24"
                >
                  <path
                    fill="currentColor"
                    d="M9 16.17L4.83 12l-1.42 1.41L9 19L21 7l-1.41-1.41z"
                  />
                </svg>
                <span class="font-semibold text-slate-700">{{ amenity }}</span>
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
                  <div class="grid grid-cols-2 gap-2">
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
                        21:00, 12/06
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
                        20:00, 16/06
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Địa điểm -->
                <div>
                  <p
                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2"
                  >
                    Địa điểm nhận xe
                  </p>
                  <div
                    class="flex items-center gap-2 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-brand-primary transition-colors"
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
                    <span
                      class="text-sm text-slate-700 font-semibold truncate flex-1"
                      >{{ car?.car_location?.street_name || 'Chưa cập nhật' }}</span
                    >
                    <span
                      class="text-xs font-bold text-green-600 flex-shrink-0"
                      >Miễn phí</span
                    >
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
                  class="w-full bg-brand-primary hover:bg-brand-dark text-white font-extrabold py-4 rounded-2xl transition-all duration-300 text-sm tracking-widest shadow-lg shadow-brand-primary/20 hover:shadow-brand-primary/30 hover:-translate-y-[0.5px] transform"
                >
                  + CHỌN THUÊ
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
  </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted, watch } from "vue";
import { useRoute } from "#app";
import { carService } from "~/services/car.service";

definePageMeta({ layout: "vehicle-detail" });

const route = useRoute();
const carId = route.params.id as string;

const car = ref<any>(null);
const loading = ref(true);
const error = ref<string | null>(null);

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

const loadCarDetails = async (id: string) => {
  loading.value = true;
  error.value = null;
  activeIndex.value = 0;
  try {
    const response = await carService.getCarById(id);
    if (response.success && response.data) {
      car.value = response.data;

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

onMounted(() => {
  loadCarDetails(carId);
});

// Watch thay đổi ID trên URL để tải lại dữ liệu khi đổi xe liên quan
watch(() => route.params.id, (newId) => {
  if (newId) {
    loadCarDetails(newId as string);
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
      "Bluetooth",
      "Camera lùi",
      "Định vị GPS",
      "Khe cắm USB",
      "Lốp dự phòng",
      "ETC",
      "Túi khí an toàn",
      "Cửa sổ trời",
    ];
  }
  return car.value.features.map((f: any) => f.name);
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
  if (!car.value) return [];
  const unitPrice = car.value.unit_price;
  const insuranceFee = Math.round(unitPrice * 0.09); // Phí bảo hiểm 9%
  const deliveryFee = car.value.delivery_option_id ? 100000 : 0;
  const discountVal = car.value.discount_value || 0;
  
  const details = [
    { label: "Đơn giá thuê", value: `${unitPrice.toLocaleString('vi-VN')}đ/ngày`, info: true },
    { label: "Bảo hiểm thuê xe", value: `${insuranceFee.toLocaleString('vi-VN')}đ/ngày`, info: true },
  ];

  if (deliveryFee > 0) {
    details.push({ label: "Phí giao nhận xe", value: `${deliveryFee.toLocaleString('vi-VN')}đ`, info: false });
  }

  if (discountVal > 0) {
    details.push({
      label: "Chương trình giảm giá",
      value: `-${discountVal.toLocaleString('vi-VN')}đ`,
      info: false,
      discount: true,
    });
  }

  return details;
});

const totalSavings = computed(() => {
  if (!car.value) return 0;
  return car.value.discount_value || 0;
});

const totalPrice = computed(() => {
  if (!car.value) return 0;
  const unitPrice = car.value.unit_price;
  const insuranceFee = Math.round(unitPrice * 0.09);
  const deliveryFee = car.value.delivery_option_id ? 100000 : 0;
  const discountVal = car.value.discount_value || 0;
  return unitPrice + insuranceFee + deliveryFee - discountVal;
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
      location: c.car_location?.street_name || 'Chưa cập nhật',
      rating: c.reviews_avg_rating ? parseFloat(c.reviews_avg_rating).toFixed(1) : '5.0',
      trips: c.trips_count || 0,
      badge: discountPct > 0 ? `-${discountPct}%` : null
    };
  });
});
</script>

<style scoped></style>

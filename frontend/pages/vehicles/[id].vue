<template>
  <div class="min-h-screen bg-slate-50/50 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                    HYUNDAI I10 2021
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
                        >5.0</span
                      >
                      <span
                        class="text-xs text-gray-400 font-bold uppercase tracking-wider"
                        >• 11 chuyến</span
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
                        >Phường 8, Quận Gò Vấp</span
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
                    <span
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
              <p class="font-bold text-brand-dark">
                DRIVIO gửi đến Quý khách hàng thuê xe với các ưu đãi sau:
              </p>
              <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                <li
                  v-for="item in descItems"
                  :key="item"
                  class="flex items-center gap-2.5 bg-slate-50/60 p-3 rounded-xl border border-slate-100/50"
                >
                  <span
                    class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 text-xs font-bold"
                    >✓</span
                  >
                  <span class="font-semibold text-slate-700">{{ item }}</span>
                </li>
              </ul>
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
              <div
                class="w-14 h-14 rounded-full bg-brand-primary flex items-center justify-center text-white text-xl font-bold flex-shrink-0 shadow-md shadow-brand-primary/10"
              >
                M
              </div>
              <div class="flex-1">
                <p class="font-extrabold text-brand-dark">
                  MICARRO TP Hồ Chí Minh
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
                    >5.0</span
                  >
                  <span class="text-gray-300">•</span>
                  <span
                    class="text-xs text-gray-500 font-bold uppercase tracking-wider"
                    >500+ chuyến</span
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
                v-for="review in reviews"
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
                    <span class="text-3xl font-black text-white">495K</span>
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
                      >5.0 • 11 chuyến • Miễn thế chấp</span
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
                        >Phường 8, Quận Gò Vấp</span
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
                      <p class="text-xl font-black text-brand-dark">653.611đ</p>
                    </div>
                    <div class="text-right">
                      <p class="text-xs text-gray-500 font-medium">Tiết kiệm</p>
                      <p class="text-sm font-black text-green-600">-90.000đ</p>
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
        Xe tương tự
      </h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div
          v-for="car in similarCars"
          :key="car.name"
          class="bg-white rounded-2xl overflow-hidden border border-slate-100/60 shadow-sm hover:shadow-md hover:border-brand-primary/10 transition-all duration-300 cursor-pointer group"
        >
          <div class="relative h-36 overflow-hidden">
            <img
              :src="car.image"
              :alt="car.name"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
            <span
              v-if="car.badge"
              class="absolute top-2 left-2 bg-brand-accent text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow-sm"
              >{{ car.badge }}</span
            >
            <button
              class="absolute top-2 right-2 p-1.5 bg-white/95 rounded-full text-gray-500 hover:text-red-500 transition-colors shadow-sm"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-3.5 h-3.5"
                viewBox="0 0 24 24"
              >
                <path
                  fill="currentColor"
                  d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54z"
                />
              </svg>
            </button>
          </div>
          <div class="p-3.5">
            <p class="text-xs font-black text-brand-dark truncate">
              {{ car.name }}
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
                >{{ car.rating }} • {{ car.trips }} chuyến</span
              >
            </div>
            <div
              class="flex items-center justify-between mt-3 pt-2 border-t border-slate-50"
            >
              <span class="text-sm font-black text-brand-primary"
                >{{ car.price
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
                {{ car.location }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from "vue";

definePageMeta({ layout: "vehicle-detail" });

const showFullDesc = ref(false);
const activeIndex = ref(0);

const carImages = [
  "https://images.unsplash.com/photo-1617788138017-80ad40651399?w=1000&auto=format&fit=crop", // Main car image
  "https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=600&auto=format&fit=crop", // Detail 1
  "https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=600&auto=format&fit=crop", // Detail 2
  "https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=600&auto=format&fit=crop", // Detail 3
  "https://images.unsplash.com/photo-1580274455191-1c62238fa333?w=600&auto=format&fit=crop", // Detail 4
];

const specs = [
  {
    label: "Truyền động",
    value: "Số sàn",
    icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><path fill="currentColor" d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>',
  },
  {
    label: "Số ghế",
    value: "5 chỗ",
    icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><path fill="currentColor" d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3m-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3m0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5m8 0c-.29 0-.62.02-.97.05c1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5"/></svg>',
  },
  {
    label: "Nhiên liệu",
    value: "Xăng",
    icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><path fill="currentColor" d="M19.77 7.23l.01-.01l-3.72-3.72L15 4.56l2.11 2.11c-.94.36-1.61 1.26-1.61 2.33a2.5 2.5 0 0 0 2.5 2.5c.36 0 .69-.08 1-.21v7.21c0 .55-.45 1-1 1s-1-.45-1-1V14c0-1.1-.9-2-2-2h-1V5c0-1.1-.9-2-2-2H6c-1.1 0-2 .9-2 2v16h10v-7.5h1.5v5a2.5 2.5 0 0 0 5 0V9c0-.69-.28-1.32-.73-1.77M18 10.5c-.55 0-1-.45-1-1s.45-1 1-1s1 .45 1 1s-.45 1-1 1M6 10V5h5v5z"/></svg>',
  },
  {
    label: "Tiêu hao",
    value: "6L/100km",
    icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m3.88-11.71L10 10.5l-1.71 5.29l5.88-1.71z"/></svg>',
  },
];

const amenities = [
  "Bluetooth",
  "Camera lùi",
  "Định vị GPS",
  "Khe cắm USB",
  "Lốp dự phòng",
  "ETC",
  "Túi khí an toàn",
  "Cửa sổ trời",
];
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
const amenityIcons: Record<string, string> = {
  Bluetooth:
    '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24"><path fill="currentColor" d="M14.88 16.29L13 18.17v-3.76zM13 5.83l1.88 1.88L13 9.59zM19 7l-5-5h-1v7.59L8.41 5L7 6.41L11.59 11L7 15.59L8.41 17L13 12.41V22h1l5-5l-3.88-3.88z"/></svg>',
  "Camera lùi":
    '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24"><path fill="currentColor" d="M12 9a3 3 0 1 0 0 6a3 3 0 0 0 0-6m-9 3a9 9 0 1 1 18 0a9 9 0 0 1-18 0m9-5a5 5 0 1 0 0 10a5 5 0 0 0 0-10"/></svg>',
  "Định vị GPS":
    '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7m0 9.5a2.5 2.5 0 0 1 0-5a2.5 2.5 0 0 1 0 5"/></svg>',
  "Khe cắm USB":
    '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24"><path fill="currentColor" d="M15 7v4h1v2h-3V5h2l-3-4l-3 4h2v8H8v-2h1V7H7v4H6v2h2v4h3v2h2v-2h3v-4h2v-2h-1V7z"/></svg>',
  "Lốp dự phòng":
    '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m0-12a4 4 0 1 0 0 8a4 4 0 0 0 0-8m0 6a2 2 0 1 1 0-4a2 2 0 0 1 0 4"/></svg>',
  ETC: '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24"><path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2m-8 12H8v-2h3v-2H8V9h3V7H6v10h5zm4-8h-2v10h2c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2m0 8h-1V9h1zm5-6h-2v2h2zm0 4h-2v2h2z"/></svg>',
  "Túi khí an toàn":
    '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2m0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8s8 3.59 8 8s-3.59 8-8 8m-2.5-7.5c-.83 0-1.5-.67-1.5-1.5S8.67 9.5 9.5 9.5s1.5.67 1.5 1.5s-.67 1.5-1.5 1.5m5 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5s1.5.67 1.5 1.5s-.67 1.5-1.5 1.5"/></svg>',
  "Cửa sổ trời":
    '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24"><path fill="currentColor" d="M19 19H5V5h14v14M5 3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm8 4H7v4h6zm4 0h-2v4h2zm-4 6H7v4h6zm4 0h-2v4h2z"/></svg>',
};

const defaultAmenityIcon =
  '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-brand-primary" viewBox="0 0 24 24"><path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19L21 7l-1.41-1.41z"/></svg>';

const cancelPolicies = [
  { time: "Trong vòng 1h sau khi đặt xe", fee: "Miễn phí" },
  {
    time: "Trước chuyến đi 7 ngày(Sau 1h khi đặt)",
    fee: "10% giá trị chuyến đi",
  },
  {
    time: "Trong vòng 7 ngày trước chuyến đi(Sau 1h khi đặt)",
    fee: "40% giá trị chuyến đi",
  },
];

const hostStats = [
  { label: "Tỉ lệ phản hồi", value: "100%" },
  { label: "Phản hồi trong", value: "5 phút" },
  { label: "Tỉ lệ đồng ý", value: "100%" },
];

const priceDetails = [
  { label: "Đơn giá thuê", value: "585.480đ/ngày", info: true },
  { label: "Bảo hiểm thuê xe", value: "52.531đ/ngày", info: true },
  { label: "Phí giao nhận xe", value: "105.600đ (7km)", info: false },
  {
    label: "Chương trình giảm giá",
    value: "-90.000đ",
    info: false,
    discount: true,
  },
  { label: "Mã khuyến mãi", value: "Đã áp dụng", info: false, discount: true },
];

const reviews = [
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

const similarCars = [
  {
    name: "HYUNDAI I10 2018",
    rating: "4.8",
    trips: "23",
    price: "455K",
    location: "Gò Vấp",
    badge: "HOT",
    image: "https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=400",
  },
  {
    name: "HYUNDAI I10 2015",
    rating: "4.6",
    trips: "41",
    price: "465K",
    location: "Bình Thạnh",
    badge: null,
    image: "https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=400",
  },
  {
    name: "CHEVROLET SPARK 2016",
    rating: "4.5",
    trips: "12",
    price: "352K",
    location: "Quận 9",
    badge: "15%",
    image: "https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=400",
  },
  {
    name: "HYUNDAI I10 2019",
    rating: "4.9",
    trips: "67",
    price: "520K",
    location: "Tân Bình",
    badge: null,
    image: "https://images.unsplash.com/photo-1580274455191-1c62238fa333?w=400",
  },
];
</script>

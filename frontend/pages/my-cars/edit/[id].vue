<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 pb-10">
    <div class="mb-6 flex items-center justify-between">
      <NuxtLink
        to="/my-cars"
        class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900"
      >
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m15 18-6-6 6-6"></path>
        </svg>
        Quay lại danh sách
      </NuxtLink>
    </div>

    <!-- Alert cảnh báo admin duyệt lại -->
    <div class="mb-8 flex items-start gap-4 rounded-3xl border border-amber-200 bg-amber-50 p-5 shadow-sm max-w-5xl mx-auto">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600 shrink-0 mt-0.5">
        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
        <line x1="12" y1="9" x2="12" y2="13"></line>
        <line x1="12" y1="17" x2="12.01" y2="17"></line>
      </svg>
      <div>
        <h4 class="font-extrabold text-amber-900 text-base">Lưu ý quan trọng</h4>
        <p class="text-amber-800 text-sm leading-relaxed mt-1">
          Sau khi bạn gửi các chỉnh sửa, xe của bạn sẽ được chuyển sang trạng thái <strong>Chờ phê duyệt</strong>.
          Xe sẽ tạm thời không thể hiển thị cho khách thuê trên hệ thống cho tới khi quản trị viên duyệt lại các thông tin thay đổi.
        </p>
      </div>
    </div>

    <section class="mw-7xl mx-auto">
      <div class="mx-auto max-w-5xl">
        <div v-if="loadingCar" class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm">
          <svg class="animate-spin h-10 w-10 text-[#1e4e57] mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
          </svg>
          <p class="text-slate-500 font-semibold text-sm">Đang tải thông tin xe...</p>
        </div>

        <form
          v-else
          @submit.prevent="onSubmit"
          class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.06)]"
        >
          <!-- Stepper Header -->
          <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-4 sm:px-8">
            <div class="text-center py-2">
              <h2 class="text-2xl font-black text-slate-900">Chỉnh sửa thông tin xe</h2>
              <p class="text-xs text-slate-500 mt-1">Cập nhật chi tiết xe và cấu hình cho thuê</p>
            </div>
            <div class="flex flex-col gap-4 lg:items-center lg:justify-between mt-4">
              <div class="grid gap-3 sm:grid-cols-3 w-full">
                <div
                  v-for="step in steps"
                  :key="step.id"
                  class="flex items-center gap-3 rounded-2xl border px-4 py-3 transition duration-200"
                  :class="activeStep === step.id
                    ? 'border-[#1e4e57]/20 bg-[#1e4e57]/5 text-[#1e4e57]'
                    : 'border-slate-200 bg-white text-slate-500'"
                >
                  <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-sm font-black shadow-sm"
                    :class="activeStep === step.id ? 'text-[#1e4e57]' : 'text-slate-400'"
                  >
                    {{ step.id }}
                  </div>
                  <div>
                    <p class="text-sm font-bold">{{ step.title }}</p>
                    <p class="text-[11px] leading-4 text-current/70">{{ step.summary }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="p-5 sm:p-8 lg:p-10">
            <!-- STEP 1: THÔNG TIN XE -->
            <div v-show="activeStep === 1" class="space-y-8">
              <section>
                <div class="mb-4">
                  <h3 class="text-lg font-bold text-slate-900">Thông tin đăng ký xe</h3>
                  <p class="text-xs text-slate-400 mt-1">
                    Các thông tin đăng kiểm của xe không thể thay đổi sau khi đăng ký thành công.
                  </p>
                </div>

                <!-- Biển số -->
                <div class="mb-5">
                  <label class="mb-1 block text-sm font-medium text-slate-700">Biển số xe</label>
                  <div class="relative">
                    <input
                      type="text"
                      v-model="licensePlate"
                      disabled
                      class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-slate-500 font-semibold cursor-not-allowed outline-none"
                    >
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                      </svg>
                    </span>
                  </div>
                </div>

                <!-- Số khung + Số máy -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                  <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Số khung (VIN)</label>
                    <div class="relative">
                      <input
                        type="text"
                        v-model="VIN"
                        disabled
                        class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-slate-500 font-semibold cursor-not-allowed outline-none"
                      >
                      <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                          <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                      </span>
                    </div>
                  </div>

                  <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Số máy</label>
                    <div class="relative">
                      <input
                        type="text"
                        v-model="engineNumber"
                        disabled
                        class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-slate-500 font-semibold cursor-not-allowed outline-none"
                      >
                      <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                          <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                      </span>
                    </div>
                  </div>
                </div>
              </section>

              <div class="mb-4">
                <h3 class="text-lg font-bold text-slate-900">Thông tin cơ bản</h3>
                <p class="text-sm text-slate-500">Các thông số kỹ thuật cấu hình xe.</p>
              </div>

              <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                  <label class="mb-1 block text-sm font-medium text-slate-700">Hãng xe</label>
                  <select
                    v-model="selectedBrandId"
                    @change="onBrandChange"
                    class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"
                  >
                    <option :value="null">Chọn hãng xe</option>
                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                      {{ brand.brand_name }}
                    </option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-slate-700">Mẫu xe</label>
                  <select
                    v-model="selectedTypeId"
                    :disabled="!selectedBrandId"
                    class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"
                  >
                    <option :value="null">
                      {{ selectedBrandId ? 'Chọn mẫu xe' : 'Chọn dòng xe trước' }}
                    </option>
                    <option v-for="type in carTypes" :key="type.id" :value="type.id">
                      {{ type.type_name }}
                    </option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-slate-700">Số ghế</label>
                  <select
                    v-model="selectedSeatCount"
                    class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"
                  >
                    <option :value="4">4</option>
                    <option :value="5">5</option>
                    <option :value="7">7</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-slate-700">Năm sản xuất</label>
                  <select
                    v-model="selectedManufactureYear"
                    class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"
                  >
                    <option v-for="year in manufactureYears" :key="year" :value="year">
                      {{ year }}
                    </option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-slate-700">Truyền động</label>
                  <select
                    v-model="selectedTransmission"
                    class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"
                  >
                    <option value="Số tự động">Số tự động</option>
                    <option value="Số sàn">Số sàn</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-slate-700">Nhiên liệu</label>
                  <select
                    v-model="selectedFuelType"
                    class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"
                  >
                    <option value="Xăng">Xăng</option>
                    <option value="Dầu Diesel">Dầu Diesel</option>
                    <option value="Điện">Điện</option>
                  </select>
                </div>
              </div>

              <section>
                <div class="mb-4">
                  <h3 class="text-lg font-bold text-slate-900">Mức tiêu thụ nhiên liệu</h3>
                  <p class="text-sm text-slate-500">Số lít nhiên liệu tiêu thụ cho quãng đường 100km.</p>
                </div>
                <div class="relative w-full sm:w-1/3">
                  <input
                    type="number"
                    v-model="fuelConsumption"
                    class="w-full rounded-2xl border border-slate-300 px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"
                  >
                </div>
              </section>

              <section>
                <div class="mb-4">
                  <h3 class="text-lg font-bold text-slate-900">Mô tả</h3>
                  <p class="text-sm text-slate-500">Mô tả chi tiết về chiếc xe của bạn.</p>
                </div>
                <textarea
                  v-model="description"
                  rows="5"
                  placeholder="Ví dụ: Xe đời mới chạy êm, tiết kiệm xăng..."
                  class="w-full resize-y rounded-2xl border border-slate-300 p-4 leading-relaxed outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"
                ></textarea>
              </section>

              <section>
                <div class="mb-4">
                  <h3 class="text-lg font-bold text-slate-900">Tính năng</h3>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                  <label
                    v-for="feature in featureItems"
                    :key="feature.name"
                    class="group cursor-pointer"
                    @click.prevent="toggleFeature(feature.name)"
                  >
                    <input type="checkbox" class="sr-only" :checked="feature.checked" :value="feature.name">

                    <div
                      class="relative flex flex-col h-full items-center gap-3 rounded-2xl border p-4 transition-all duration-200"
                      :class="feature.checked
                        ? 'border-[#1e4e57] bg-[#1e4e57]/8 shadow-md shadow-[#1e4e57]/10 ring-2 ring-[#1e4e57]/20'
                        : 'border-slate-200 bg-white group-hover:border-[#1e4e57]/40 group-hover:bg-[#1e4e57]/5'"
                    >
                      <img
                        :src="feature.icon"
                        :alt="feature.name"
                        class="h-8 w-8 shrink-0 object-cover shadow-sm transition-transform duration-200"
                        :class="feature.checked ? 'scale-110' : 'group-hover:scale-105'"
                      >

                      <div class="min-w-0 text-center">
                        <p
                          class="text-sm font-semibold transition-colors"
                          :class="feature.checked ? 'text-[#1e4e57]' : 'text-slate-900'"
                        >
                          {{ feature.name }}
                        </p>
                      </div>
                    </div>
                  </label>
                </div>
              </section>
            </div>

            <!-- STEP 2: THÔNG TIN CHO THUÊ -->
            <div v-show="activeStep === 2" class="space-y-7">
              <!-- SECTION 1: ĐƠN GIÁ THUÊ MẶC ĐỊNH -->
              <section class="space-y-4">
                <div>
                  <h3 class="text-lg font-bold text-slate-900">Đơn giá thuê mặc định</h3>
                  <p class="mt-1 text-sm text-slate-500 leading-relaxed">
                    Đơn giá thuê xe theo ngày (đơn vị: nghìn đồng).
                  </p>
                </div>
                <div class="space-y-1.5">
                  <div class="relative max-w-xs flex items-center">
                    <input
                      type="number"
                      v-model="basePrice"
                      class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-10 outline-none transition font-bold text-lg text-slate-800 focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"
                    >
                    <span class="absolute right-4 font-bold text-slate-400">K/ngày</span>
                  </div>
                </div>
              </section>

              <hr class="border-slate-100">

              <!-- SECTION 2: GIẢM GIÁ -->
              <section class="space-y-4">
                <div class="flex items-center justify-between gap-4">
                  <div>
                    <h3 class="text-lg font-bold text-slate-900">Giảm giá</h3>
                    <p class="text-sm text-slate-500">Giảm giá thuê tuần (là % trên đơn giá)</p>
                  </div>
                  <button
                    type="button"
                    role="switch"
                    :aria-checked="discountEnabled"
                    @click="discountEnabled = !discountEnabled"
                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-[#1e4e57]/40 focus:ring-offset-2"
                    :class="discountEnabled ? 'bg-[#1e4e57]' : 'bg-slate-200'"
                  >
                    <span
                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-300"
                      :class="discountEnabled ? 'translate-x-5' : 'translate-x-0'"
                    />
                  </button>
                </div>

                <div v-if="discountEnabled" class="space-y-4">
                  <div class="space-y-2 max-w-xl">
                    <input type="range" v-model="discountVal" min="5" max="50" class="w-full accent-[#1e4e57]">
                    <div class="flex justify-between text-xs text-slate-500">
                      <span>Mức giảm thiết lập: <strong class="text-slate-800">{{ discountVal }}%</strong></span>
                      <span class="font-bold">50%</span>
                    </div>
                  </div>
                </div>
              </section>

              <hr class="border-slate-100">

              <!-- SECTION 3: ĐỊA CHỈ XE -->
              <section class="space-y-3">
                <div>
                  <h3 class="text-lg font-bold text-slate-900">Địa chỉ xe</h3>
                  <p class="mt-1 text-sm text-slate-500">Vị trí giao nhận xe mặc định.</p>
                </div>
                <div class="relative">
                  <input
                    type="text"
                    v-model="address"
                    @input="searchPlace"
                    placeholder="Nhập địa chỉ mốc giao nhận xe..."
                    class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-12 outline-none transition text-sm text-slate-700 focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"
                  >
                  <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                      <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                  </div>

                  <!-- Suggestions Dropdown -->
                  <div
                    v-if="suggestions.length"
                    class="absolute z-[100] left-0 right-0 mt-1 bg-white border border-slate-200 rounded-2xl shadow-xl max-h-60 overflow-y-auto divide-y divide-slate-100"
                  >
                    <div
                      v-for="item in suggestions"
                      :key="item.place_id"
                      class="p-4 hover:bg-slate-50 cursor-pointer text-sm text-slate-700 transition-colors"
                      @click="selectPlace(item)"
                    >
                      {{ item.description }}
                    </div>
                  </div>
                </div>

                <!-- Map Container -->
                <div id="map" class="w-full rounded-3xl overflow-hidden border border-slate-200 shadow-inner mt-4" style="height: 350px;"></div>
              </section>

              <hr class="border-slate-100">

              <!-- SECTION 4: GIAO XE TẬN NƠI -->
              <section class="space-y-4">
                <div class="flex items-center justify-between gap-4">
                  <div>
                    <h3 class="text-lg font-bold text-slate-900">Giao xe tận nơi</h3>
                    <p class="text-sm text-slate-500">Hỗ trợ giao nhận xe đến tận tay khách hàng</p>
                  </div>
                  <button
                    type="button"
                    role="switch"
                    :aria-checked="deliveryEnabled"
                    @click="deliveryEnabled = !deliveryEnabled"
                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-[#1e4e57]/40 focus:ring-offset-2"
                    :class="deliveryEnabled ? 'bg-[#1e4e57]' : 'bg-slate-200'"
                  >
                    <span
                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-300"
                      :class="deliveryEnabled ? 'translate-x-5' : 'translate-x-0'"
                    />
                  </button>
                </div>

                <div v-if="deliveryEnabled" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5 pt-1">
                  <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Quãng đường giao xe tối đa</label>
                    <input type="range" v-model="maxDistVal" min="5" max="50" class="w-full accent-[#1e4e57]">
                    <div class="flex justify-between text-xs text-slate-500">
                      <span>Thiết lập: <strong class="text-slate-800">{{ maxDistVal }}km</strong></span>
                      <span class="font-bold">50km</span>
                    </div>
                  </div>
                  <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Phí giao nhận mỗi km</label>
                    <input type="range" v-model="feeVal" min="1" max="30" class="w-full accent-[#1e4e57]">
                    <div class="flex justify-between text-xs text-slate-500">
                      <span>Thiết lập: <strong class="text-slate-800">{{ feeVal }}K/km</strong></span>
                      <span class="font-bold">30K</span>
                    </div>
                  </div>
                  <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Miễn phí giao nhận trong vòng</label>
                    <input type="range" v-model="freeLimitVal" min="0" max="15" class="w-full accent-[#1e4e57]">
                    <div class="flex justify-between text-xs text-slate-500">
                      <span>Thiết lập: <strong class="text-slate-800">{{ freeLimitVal }}km</strong></span>
                      <span class="font-bold">15km</span>
                    </div>
                  </div>
                </div>
              </section>

              <hr class="border-slate-100">

              <!-- SECTION 5: GIỚI HẠN SỐ KM -->
              <section class="space-y-4">
                <div class="flex items-center justify-between gap-4">
                  <div>
                    <h3 class="text-lg font-bold text-slate-900">Giới hạn số km</h3>
                    <p class="text-sm text-slate-500">Giới hạn quãng đường khách đi mỗi ngày</p>
                  </div>
                  <button
                    type="button"
                    role="switch"
                    :aria-checked="kmLimitEnabled"
                    @click="kmLimitEnabled = !kmLimitEnabled"
                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-[#1e4e57]/40 focus:ring-offset-2"
                    :class="kmLimitEnabled ? 'bg-[#1e4e57]' : 'bg-slate-200'"
                  >
                    <span
                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-300"
                      :class="kmLimitEnabled ? 'translate-x-5' : 'translate-x-0'"
                    />
                  </button>
                </div>

                <div v-if="kmLimitEnabled" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5 pt-1">
                  <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Số km tối đa trong 1 ngày</label>
                    <input type="range" v-model="kmLimitVal" min="100" max="500" step="10" class="w-full accent-[#1e4e57]">
                    <div class="flex justify-between text-xs text-slate-500">
                      <span>Thiết lập: <strong class="text-slate-800">{{ kmLimitVal }}km</strong></span>
                      <span class="font-bold">500km</span>
                    </div>
                  </div>
                  <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Phí vượt giới hạn (mỗi km)</label>
                    <input type="range" v-model="overFeeVal" min="1" max="10" class="w-full accent-[#1e4e57]">
                    <div class="flex justify-between text-xs text-slate-500">
                      <span>Thiết lập: <strong class="text-slate-800">{{ overFeeVal }}K/km</strong></span>
                      <span class="font-bold">10K</span>
                    </div>
                  </div>
                </div>
              </section>

              <hr class="border-slate-100">

              <!-- SECTION 6: ĐIỀU KHOẢN THUÊ XE -->
              <section class="space-y-3">
                <div>
                  <h3 class="text-lg font-bold text-slate-900">Điều khoản thuê xe</h3>
                  <p class="mt-1 text-sm text-slate-500">Ghi rõ các yêu cầu điều khoản để khách lưu ý khi thuê xe.</p>
                </div>
                <textarea
                  rows="4"
                  v-model="rentalTerms"
                  class="w-full resize-y rounded-2xl border border-slate-300 p-4 outline-none transition text-slate-700 leading-relaxed text-sm bg-slate-50/60 hover:bg-white focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"
                ></textarea>
              </section>
            </div>

            <!-- STEP 3: HÌNH ẢNH & HOÀN TẤT -->
            <div v-show="activeStep === 3" class="space-y-8">
              <!-- Hiển thị ảnh cũ ở trên -->
              <div v-if="existingImages.length > 0" class="space-y-4 rounded-3xl border border-slate-100 bg-slate-50/50 p-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                  <div>
                    <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Hình ảnh hiện tại của xe</h4>
                    <p class="text-xs text-slate-400 mt-1">Khi bạn tải lên ảnh mới bên dưới, tất cả ảnh hiện tại này sẽ bị thay thế.</p>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                  <div v-for="(img, idx) in existingImages" :key="img" class="group relative aspect-[4/3] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <img :src="img" class="h-full w-full object-cover" />
                    <span v-if="idx === 0" class="absolute left-2.5 top-2.5 rounded-full bg-[#1e4e57] px-2.5 py-1 text-[10px] font-bold text-white shadow-sm flex items-center gap-1">
                      <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                      </svg>
                      Ảnh đại diện
                    </span>
                  </div>
                </div>
              </div>

              <!-- Component tải ảnh ở dưới -->
              <ImageUpload ref="imageUploadRef" v-model="uploadedImages" :max-files="5" />

              <section class="rounded-3xl border border-slate-100 bg-slate-50/70 p-6">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Kiểm tra các thay đổi</h3>
                <ul class="mt-4 space-y-3 text-sm text-slate-600">
                  <li class="flex items-center gap-3">
                    <span
                      class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full"
                      :class="(uploadedImages.length > 0 || existingImages.length > 0) ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-400'"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                      </svg>
                    </span>
                    <span :class="(uploadedImages.length > 0 || existingImages.length > 0) ? 'text-slate-700 font-medium' : 'text-slate-400'">
                      Đã có ít nhất 1 ảnh xe được chọn.
                    </span>
                  </li>
                  <li class="flex items-center gap-3">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                      </svg>
                    </span>
                    <span class="text-slate-700 font-medium">Điều khoản thuê xe đã sẵn sàng.</span>
                  </li>
                </ul>
              </section>
            </div>

            <!-- Navigation Buttons -->
            <div class="mt-10 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
              <button
                type="button"
                class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-4 text-sm font-bold text-slate-700 transition hover:bg-slate-50 sm:w-auto"
                :disabled="activeStep === 1"
                :class="activeStep === 1 ? 'cursor-not-allowed opacity-50' : ''"
                @click="prevStep"
              >
                Quay lại
              </button>

              <button
                v-if="activeStep < 3"
                type="button"
                class="inline-flex w-full items-center justify-center rounded-2xl bg-[#1e4e57] px-6 py-4 text-sm font-bold text-white shadow-lg shadow-[#1e4e57]/20 transition hover:bg-[#286874] sm:w-auto"
                @click="nextStep"
              >
                Kế tiếp
              </button>

              <button
                v-else
                type="submit"
                :disabled="submitting"
                class="inline-flex w-full items-center justify-center rounded-2xl bg-[#1e4e57] px-6 py-4 text-sm font-bold text-white shadow-lg shadow-[#1e4e57]/20 transition hover:bg-[#286874] sm:w-auto disabled:opacity-50"
              >
                <span v-if="submitting">Đang lưu thay đổi...</span>
                <span v-else>Cập nhật xe</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick, watch } from 'vue'
import { useRoute } from 'vue-router'
import { carService } from '~/services/car.service'
import { useAuth } from '~/composables/useAuth'
import { useToast } from '~/composables/useToast'
import { useAuthModal } from '~/composables/useAuthModal'
import ImageUpload from '~/components/ImageUpload/ImageUpload.vue'

definePageMeta({
  layout: "my-cars",
})

const { showToast } = useToast()
const route = useRoute()
const carId = route.params.id as string

let maplibregl: any = null

// Goong Map Keys
const MAP_KEY = '8Gh3kHiOvTsc6QHzNT4Aq0aFjH2I69PNiFyzk5Ex'
const API_KEY = 'xEcFmnV3loWHnfqa9ZsEENH7Wu6lehK4QmabQk7V'

const suggestions = ref<any[]>([])
const mapRef = ref<any>(null)
const currentMarker = ref<any>(null)
const selectedCoords = ref<{ lat: number; lng: number } | null>(null)

const activeStep = ref(1)
const loadingCar = ref(true)

// Form states
const licensePlate = ref('')
const VIN = ref('')
const engineNumber = ref('')
const selectedBrandId = ref<number | null>(null)
const selectedTypeId = ref<number | null>(null)
const selectedSeatCount = ref(4)
const selectedManufactureYear = ref(new Date().getFullYear())
const selectedTransmission = ref('Số tự động')
const selectedFuelType = ref('Xăng')
const fuelConsumption = ref(10)
const description = ref('')
const basePrice = ref(350)
const address = ref('')
const rentalTerms = ref('')

const brands = ref<any[]>([])
const carTypes = ref<any[]>([])
const manufactureYears = ref<number[]>([])
const submitting = ref(false)

const currentYear = new Date().getFullYear()
for (let y = currentYear + 1; y >= 2005; y--) {
  manufactureYears.value.push(y)
}

const steps = [
  {
    id: 1,
    title: 'Thông tin xe',
    summary: 'Cấu hình kỹ thuật của xe'
  },
  {
    id: 2,
    title: 'Thông tin cho thuê',
    summary: 'Giá cả, vị trí và giới hạn'
  },
  {
    id: 3,
    title: 'Hình ảnh & hoàn tất',
    summary: 'Quản lý ảnh đại diện & ảnh chi tiết'
  }
]

// Step 2 reactive settings
const discountEnabled = ref(true)
const discountVal = ref(20)
const deliveryEnabled = ref(true)
const maxDistVal = ref(20)
const feeVal = ref(10)
const freeLimitVal = ref(0)
const kmLimitEnabled = ref(true)
const kmLimitVal = ref(400)
const overFeeVal = ref(3)

const featureItems = ref<any[]>([])

const toggleFeature = (name: string) => {
  const item = featureItems.value.find((f) => f.name === name)
  if (!item) return
  item.checked = !item.checked
}

// Step 3 images
const existingImages = ref<string[]>([])
const uploadedImages = ref<string[]>([])
const imageUploadRef = ref<any>(null)

watch(uploadedImages, (newVal) => {
  if (newVal.length > 0 && existingImages.value.length > 0) {
    existingImages.value = []
  }
}, { deep: true })

const { isLoggedIn, user } = useAuth()
const { openLogin } = useAuthModal()

// Load brands
const loadBrands = async () => {
  try {
    const res = await carService.getBrands()
    if (res && res.success) {
      brands.value = res.data
    }
  } catch (e) {
    console.error('Không tải được danh sách hãng xe:', e)
  }
}

// Load car types when brand changes
const onBrandChange = async () => {
  selectedTypeId.value = null
  carTypes.value = []
  if (!selectedBrandId.value) return
  try {
    const res = await carService.getTypes(selectedBrandId.value)
    if (res && res.success) {
      carTypes.value = res.data
    }
  } catch (e) {
    console.error('Không tải được danh sách mẫu xe:', e)
  }
}

// Load feature items list
const loadFeatures = async () => {
  try {
    const res = await carService.getFeatures()
    if (res && res.success && res.data.length > 0) {
      featureItems.value = res.data.map((item: any) => ({
        id: item.id,
        name: item.feature_name,
        checked: false,
        icon: item.icon
      }))
    }
  } catch (e) {
    console.error('Không tải được danh sách tính năng:', e)
  }
}

// Load car details by ID and prefill form
const fetchCarData = async () => {
  try {
    const res = await carService.getCarById(carId)
    if (res && res.success && res.data) {
      const car = res.data

      // Kiểm tra quyền sở hữu chiếc xe
      if (user.value && car.user_id !== user.value.id && user.value.role_id !== 1) {
        showToast('Bạn không có quyền sửa xe này.', 'error')
        navigateTo('/my-cars')
        return
      }

      // Kiểm tra xe có chuyến đi đang diễn ra hay không
      if (car.has_ongoing_trip) {
        showToast('Xe đang có chuyến đi đang diễn ra, không thể thay đổi trạng thái hoặc chỉnh sửa thông tin xe.', 'error')
        navigateTo('/my-cars')
        return
      }

      licensePlate.value = car.license_plate
      VIN.value = car.VIN
      engineNumber.value = car.engine_number
      selectedBrandId.value = car.car_brand_id

      // Load types for this brand before setting selectedTypeId
      if (selectedBrandId.value) {
        const typesRes = await carService.getTypes(selectedBrandId.value)
        if (typesRes && typesRes.success) {
          carTypes.value = typesRes.data
        }
      }
      selectedTypeId.value = car.car_type_id
      selectedSeatCount.value = Number(car.seat_count)
      selectedManufactureYear.value = car.manufacture_year ? new Date(car.manufacture_year).getFullYear() : 2020
      selectedTransmission.value = car.transmission
      selectedFuelType.value = car.fuel_type
      fuelConsumption.value = Number(car.fuel_consumption)
      description.value = car.description || ''
      basePrice.value = Math.round(Number(car.unit_price) / 1000)

      discountEnabled.value = Number(car.discount_value) > 0
      if (discountEnabled.value) {
        discountVal.value = Math.round((Number(car.discount_value) / Number(car.unit_price)) * 100)
      } else {
        discountVal.value = 20
      }

      address.value = car.car_location ? car.car_location.address : ''
      if (car.car_location && car.car_location.location) {
        const parts = car.car_location.location.split(',')
        if (parts.length === 2) {
          selectedCoords.value = {
            lat: Number(parts[0]),
            lng: Number(parts[1])
          }
        }
      }

      deliveryEnabled.value = car.delivery_option ? car.delivery_option.status === 1 : false
      if (car.delivery_option) {
        maxDistVal.value = Number(car.delivery_option.max_distance)
        feeVal.value = Math.round(Number(car.delivery_option.fee_distance) / 1000)
        freeLimitVal.value = Number(car.delivery_option.free_distance)
      }

      kmLimitEnabled.value = car.usage_limit ? car.usage_limit.status === 1 : false
      if (car.usage_limit) {
        kmLimitVal.value = Number(car.usage_limit.max_daily_distance)
        overFeeVal.value = Math.round(Number(car.usage_limit.extra_distance_fee) / 1000)
      }

      rentalTerms.value = car.rental_terms || ''

      // Features list sync
      if (car.features && car.features.length > 0) {
        const carFeatureIds = car.features.map((f: any) => f.id)
        featureItems.value.forEach((item) => {
          if (carFeatureIds.includes(item.id)) {
            item.checked = true
          }
        })
      }

      // Images list sync
      if (car.images && car.images.length > 0) {
        // Đưa ảnh thumbnail lên trước
        const sortedImages = [...car.images].sort((a, b) => b.is_thumbnail - a.is_thumbnail)
        existingImages.value = sortedImages.map((img) => img.image_url)
      }
    } else {
      showToast('Không tìm thấy thông tin xe.', 'error')
      navigateTo('/my-cars')
    }
  } catch (error) {
    console.error('Lỗi khi tải thông tin xe:', error)
    showToast('Không thể tải thông tin xe.', 'error')
    navigateTo('/my-cars')
  } finally {
    loadingCar.value = false
  }
}

const onSubmit = async () => {
  if (!selectedBrandId.value) {
    showToast('Vui lòng chọn hãng xe.', 'error')
    activeStep.value = 1
    return
  }
  if (!selectedTypeId.value) {
    showToast('Vui lòng chọn mẫu xe.', 'error')
    activeStep.value = 1
    return
  }
  if (!address.value) {
    showToast('Vui lòng nhập địa chỉ xe.', 'error')
    activeStep.value = 2
    return
  }
  if (uploadedImages.value.length === 0 && existingImages.value.length === 0) {
    showToast('Vui lòng tải lên ít nhất 1 hình ảnh của xe.', 'error')
    activeStep.value = 3
    return
  }

  submitting.value = true

  try {
    let imageUrls: string[] = []
    let thumbnailIndex = 0

    if (uploadedImages.value.length > 0) {
      // Tải ảnh mới lên Cloudinary (nếu có) thông qua uploader component
      const uploaded = await imageUploadRef.value?.upload()

      if (!uploaded || uploaded.length === 0) {
        showToast("Không thể upload hình ảnh", "error")
        return
      }
      imageUrls = uploaded
      thumbnailIndex = imageUploadRef.value?.getThumbnailIndex() !== -1 ? imageUploadRef.value?.getThumbnailIndex() : 0
    } else {
      imageUrls = existingImages.value
      thumbnailIndex = 0
    }

    const selectedFeatureIds = featureItems.value
      .filter((f: any) => f.checked)
      .map((f: any) => f.id || null)
      .filter((id: any) => id !== null)

    const unitPriceVal = basePrice.value * 1000
    let discountValue = 0
    if (discountEnabled.value) {
      discountValue = Math.round(unitPriceVal * (discountVal.value / 100))
    }

    const payload = {
      license_plate: licensePlate.value,
      VIN: VIN.value,
      engine_number: engineNumber.value,
      car_brand_id: selectedBrandId.value,
      car_type_id: selectedTypeId.value,
      seat_count: selectedSeatCount.value,
      manufacture_year: selectedManufactureYear.value,
      transmission: selectedTransmission.value,
      fuel_type: selectedFuelType.value,
      fuel_consumption: fuelConsumption.value,
      description: description.value,
      unit_price: unitPriceVal,
      discount_value: discountValue,
      address: address.value,
      location: selectedCoords.value ? `${selectedCoords.value.lat},${selectedCoords.value.lng}` : '',
      delivery_enabled: deliveryEnabled.value ? '1' : '0',
      delivery_max_distance: maxDistVal.value,
      delivery_fee: feeVal.value * 1000,
      delivery_free_distance: freeLimitVal.value,
      km_limit_enabled: kmLimitEnabled.value ? '1' : '0',
      km_limit_val: kmLimitVal.value,
      over_fee_val: overFeeVal.value * 1000,
      rental_terms: rentalTerms.value,
      features: selectedFeatureIds,
      images: imageUrls,
      thumbnail_index: thumbnailIndex
    }

    const res = await carService.updateCar(carId, payload)

    if (res && res.success) {
      showToast('Cập nhật thông tin xe thành công! Xe của bạn đang chờ kiểm duyệt lại.', 'success')
      navigateTo('/my-cars')
    } else {
      showToast(res.message || 'Cập nhật xe thất bại.', 'error')
    }
  } catch (e: any) {
    console.error('Cập nhật xe thất bại:', e)
    const errMsg = e.response?._data?.message || 'Có lỗi xảy ra khi kết nối máy chủ.'
    showToast(errMsg, 'error')
  } finally {
    submitting.value = false
  }
}

// Goong Map implementation
const generateMap = async () => {
  await nextTick()
  const container = document.getElementById('map')
  if (!container) return

  if (mapRef.value) {
    try {
      mapRef.value.remove()
    } catch (e) {
      console.error('Lỗi khi hủy bản đồ cũ:', e)
    }
    mapRef.value = null
    currentMarker.value = null
  }

  const map = new maplibregl.Map({
    container: 'map',
    style: `https://tiles.goong.io/assets/goong_map_web.json?api_key=${MAP_KEY}`,
    center: selectedCoords.value ? [selectedCoords.value.lng, selectedCoords.value.lat] : [106.660172, 10.762622],
    zoom: selectedCoords.value ? 16 : 13
  })

  map.addControl(new maplibregl.NavigationControl(), "top-right")

  map.on('load', () => {
    if (selectedCoords.value) {
      const { lat, lng } = selectedCoords.value
      const popup = new maplibregl.Popup({
        offset: 25
      }).setText(address.value)

      currentMarker.value = new maplibregl.Marker({ color: 'red' })
        .setLngLat([lng, lat])
        .setPopup(popup)
        .addTo(map)
        .togglePopup()
    } else {
      currentMarker.value = new maplibregl.Marker({
        color: 'red',
      }).setLngLat([106.660172, 10.762622]).addTo(map)
    }

    setTimeout(() => {
      map.resize()
    }, 200)
  })

  mapRef.value = map
}

const searchPlace = async () => {
  if (!address.value) {
    suggestions.value = []
    return
  }

  try {
    const res = await fetch(
      `https://rsapi.goong.io/Place/AutoComplete?api_key=${API_KEY}&input=${encodeURIComponent(address.value)}`
    )
    const data = await res.json()
    suggestions.value = data.predictions || []
  } catch (error) {
    console.error('Lỗi khi gọi API AutoComplete:', error)
  }
}

const selectPlace = async (item: any) => {
  address.value = item.description
  suggestions.value = []

  try {
    const res = await fetch(
      `https://rsapi.goong.io/Place/Detail?place_id=${item.place_id}&api_key=${API_KEY}`
    )
    const data = await res.json()
    if (data.result && data.result.geometry) {
      const location = data.result.geometry.location
      const lat = location.lat
      const lng = location.lng

      selectedCoords.value = { lat, lng }

      if (mapRef.value) {
        mapRef.value.setCenter([lng, lat])
        mapRef.value.setZoom(16)

        const popup = new maplibregl.Popup({
          offset: 25
        }).setText(item.description)

        if (currentMarker.value) {
          currentMarker.value.remove()
        }

        currentMarker.value = new maplibregl.Marker({ color: 'red' })
          .setLngLat([lng, lat])
          .setPopup(popup)
          .addTo(mapRef.value)
          .togglePopup()
      }
    }
  } catch (error) {
    console.error('Lỗi khi lấy chi tiết địa điểm:', error)
  }
}

const nextStep = () => {
  activeStep.value = Math.min(3, activeStep.value + 1)
  if (activeStep.value === 2) {
    nextTick(() => {
      generateMap()
    })
  }
}

const prevStep = () => {
  activeStep.value = Math.max(1, activeStep.value - 1)
  if (activeStep.value === 2) {
    nextTick(() => {
      generateMap()
    })
  }
}

onMounted(async () => {
  if (!isLoggedIn.value) {
    showToast('Vui lòng đăng nhập trước.', 'error')
    openLogin()
    navigateTo('/')
    return
  }

  // Load maplibre-gl dynamically on client side
  if (process.client) {
    try {
      const module = await import('maplibre-gl')
      maplibregl = module.default
      await import('maplibre-gl/dist/maplibre-gl.css')
    } catch (e) {
      console.error('Không tải được thư viện bản đồ:', e)
    }
  }

  await loadBrands()
  await loadFeatures()
  await fetchCarData()
})

useHead({
  title: 'Chỉnh sửa thông tin xe | DRIVIO'
})
</script>

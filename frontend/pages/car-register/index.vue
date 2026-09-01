<template>
    <div class="min-h-screen bg-slate-50 text-slate-900">
        <!-- <section class="relative overflow-hidden bg-gradient-to-br from-[#173f47] via-[#1e4e57] to-[#2b6f7a] text-white">
            <div class="absolute inset-0">
                <img src="https://xedoisong.vn/uploads/user_4/2025/t12/5/gr/nghenhin_xedoisong_toyota_gr_gt_3.jpg" alt="Banner đăng ký xe" class="h-full w-full object-cover opacity-25" />
                <div class="absolute inset-0 bg-gradient-to-r from-[#102d33]/90 via-[#1e4e57]/75 to-[#2b6f7a]/70"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-24 pb-24 md:pt-28 md:pb-28">
                <button type="button" class="mb-8 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-medium text-white/90 backdrop-blur-sm transition hover:bg-white/15">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                    Quay lại
                </button>

                <div class="grid items-end gap-10 lg:grid-cols-12 mx-auto">
                    <div class="lg:col-span-12 mx-auto text-center">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.25em] text-cyan-50/90 backdrop-blur-sm">
                            Đăng ký xe cho thuê
                        </span>
                        <h1 class="mt-6 max-w-3xl text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-6xl">
                            Gia tăng thu nhập với chiếc xe của bạn
                        </h1>
                        <p class="mt-5 max-w-2xl text-base leading-8 text-cyan-50/90 sm:text-lg">
                            Chỉ với vài bước đơn giản, bạn đã có thể biến chiếc xe của mình thành nguồn thu nhập thụ động đáng tin cậy trên Drivio.
                        </p>
                    </div>
                </div>
            </div>
        </section> -->

        <OwnerBanner></OwnerBanner>

        <section class="mt-12 pb-14 mw-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <form
                    class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.10)]">
                    <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-4 sm:px-8">
                        <div>
                            <h2 class="mt-2 mb-4 text-center text-2xl font-black text-slate-900">Đăng ký xe</h2>
                        </div>
                        <div class="flex flex-col gap-4 lg:items-center lg:justify-between">
                            <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols">
                                <div v-for="step in steps" :key="step.id"
                                    class="flex items-center gap-3 rounded-2xl border px-4 py-3 transition" :class="activeStep === step.id
                                        ? 'border-[#1e4e57]/20 bg-[#1e4e57]/5 text-[#1e4e57]'
                                        : 'border-slate-200 bg-white text-slate-500'">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-sm font-black shadow-sm"
                                        :class="activeStep === step.id ? 'text-[#1e4e57]' : 'text-slate-400'">
                                        {{ step.id }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold">{{ step.title }}</p>
                                        <p class="text-xs leading-5 text-current/70">{{ step.summary }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-8 lg:p-10">
                        <div v-show="activeStep === 1" class="space-y-8">
                            <section>
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-slate-900">Thông tin đăng ký xe</h3>
                                    <p class="text-sm text-red-500/90">
                                        Lưu ý: Biển số, số khung và số máy sẽ không thể thay đổi sau khi đăng ký.
                                    </p>
                                </div>

                                <!-- Biển số -->
                                <div class="mb-5">
                                    <label class="mb-1 block text-sm font-medium text-slate-700">
                                        Biển số xe
                                    </label>
                                    <input type="text" v-model="licensePlate" maxlength="12"
                                        placeholder="Nhập biển số xe (VD: 51H12345)" class="w-full rounded-2xl border border-slate-300 px-4 py-3 outline-none transition
                   focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                </div>

                                <!-- Số khung + Số máy -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-slate-700">
                                            Số khung (VIN)
                                        </label>
                                        <input type="text" v-model="VIN" maxlength="17"
                                            placeholder="Nhập số khung (VD: 1HGCR2F8XHA045239)" class="w-full rounded-2xl border border-slate-300 px-4 py-3 outline-none transition
                       focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-slate-700">
                                            Số máy
                                        </label>
                                        <input type="text" v-model="engineNumber" maxlength="100"
                                            placeholder="Nhập số máy (VD: K24W1-1234567)" class="w-full rounded-2xl border border-slate-300 px-4 py-3 outline-none transition
                       focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                    </div>
                                </div>
                            </section>

                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-slate-900">Thông tin cơ bản</h3>
                                <p class="text-sm text-slate-500">Nhóm các thông tin cần khai báo một lần cho xe.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <!-- Searchable Hãng xe -->
                                <div class="relative id-brand-dropdown">
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Hãng xe</label>
                                    <div @click="toggleBrandDropdown"
                                        class="w-full flex items-center justify-between cursor-pointer rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus-within:border-[#1e4e57] focus-within:ring-4 focus-within:ring-[#1e4e57]/10">
                                        <span :class="selectedBrand ? 'text-slate-900' : 'text-slate-400'">
                                            {{ selectedBrand ? selectedBrand.brand_name : 'Chọn hãng xe' }}
                                        </span>
                                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200"
                                            :class="{ 'rotate-180': isBrandOpen }"></i>
                                    </div>

                                    <!-- Dropdown Menu Hãng Xe -->
                                    <div v-if="isBrandOpen" @click.stop
                                        class="absolute z-50 mt-1 w-full rounded-2xl border border-slate-200 bg-white p-2 shadow-xl">
                                        <div class="relative mb-2">
                                            <i
                                                class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                                            <input type="text" v-model="brandSearch" placeholder="Tìm tên hãng..."
                                                class="w-full rounded-xl border border-slate-200 py-2 pl-8 pr-4 text-sm outline-none focus:border-[#1e4e57]" />
                                        </div>
                                        <div class="max-h-48 overflow-y-auto divide-y divide-slate-50">
                                            <div v-for="brand in filteredBrands" :key="brand.id"
                                                @click="selectBrand(brand)"
                                                class="cursor-pointer rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                                {{ brand.brand_name }}
                                            </div>
                                            <div v-if="filteredBrands.length === 0"
                                                class="p-3 text-center text-xs text-slate-400">
                                                Không tìm thấy hãng xe phù hợp
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Searchable Mẫu xe -->
                                <div class="relative id-type-dropdown">
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Mẫu xe</label>
                                    <div @click="toggleTypeDropdown"
                                        :class="!selectedBrandId ? 'bg-slate-50 opacity-60 cursor-not-allowed' : 'bg-white cursor-pointer'"
                                        class="w-full flex items-center justify-between rounded-2xl border border-slate-300 px-4 py-3 outline-none transition focus-within:border-[#1e4e57] focus-within:ring-4 focus-within:ring-[#1e4e57]/10">
                                        <span :class="selectedType ? 'text-slate-900' : 'text-slate-400'">
                                            {{ selectedBrandId ? (selectedType ? selectedType.type_name : 'Chọn mẫu xe')
                                                : 'Chọn dòng xe trước' }}
                                        </span>
                                        <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200"
                                            :class="{ 'rotate-180': isTypeOpen }"></i>
                                    </div>

                                    <!-- Dropdown Menu Mẫu Xe -->
                                    <div v-if="isTypeOpen && selectedBrandId" @click.stop
                                        class="absolute z-50 mt-1 w-full rounded-2xl border border-slate-200 bg-white p-2 shadow-xl">
                                        <div class="relative mb-2">
                                            <i
                                                class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                                            <input type="text" v-model="typeSearch" placeholder="Tìm tên mẫu xe..."
                                                class="w-full rounded-xl border border-slate-200 py-2 pl-8 pr-4 text-sm outline-none focus:border-[#1e4e57]" />
                                        </div>
                                        <div class="max-h-48 overflow-y-auto divide-y divide-slate-50">
                                            <div v-for="type in filteredCarTypes" :key="type.id"
                                                @click="selectType(type)"
                                                class="cursor-pointer rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                                {{ type.type_name }}
                                            </div>
                                            <div v-if="filteredCarTypes.length === 0"
                                                class="p-3 text-center text-xs text-slate-400">
                                                Không tìm thấy mẫu xe phù hợp
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Số ghế</label>
                                    <select v-model="selectedSeatCount"
                                        class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                        <option :value="4">4</option>
                                        <option :value="5">5</option>
                                        <option :value="7">7</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Năm sản xuất</label>
                                    <select v-model="selectedManufactureYear"
                                        class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                        <option v-for="year in manufactureYears" :key="year" :value="year">{{ year }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Truyền động</label>
                                    <select v-model="selectedTransmission"
                                        class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                        <option value="Số tự động">Số tự động</option>
                                        <option value="Số sàn">Số sàn</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700">Nhiên liệu</label>
                                    <select v-model="selectedFuelType"
                                        class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                        <option value="Xăng">Xăng</option>
                                        <option value="Dầu Diesel">Dầu Diesel</option>
                                        <option value="Điện">Điện</option>
                                    </select>
                                </div>
                            </div>

                            <section>
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-slate-900">Mức tiêu thụ nhiên liệu</h3>
                                    <p class="text-sm text-slate-500">Số lít nhiên liệu cho quãng đường 100km.</p>
                                </div>
                                <div class="relative w-full sm:w-1/3">
                                    <input type="number" v-model="fuelConsumption" min="0.1" step="0.1"
                                        @input="handleFuelConsumptionInput"
                                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                </div>
                            </section>
                            <section>
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-slate-900">Mô tả</h3>
                                    <p class="text-sm text-slate-500">Mô tả ngắn gọn về chiếc xe và điểm mạnh của nó.
                                    </p>
                                </div>
                                <textarea v-model="description" rows="5"
                                    placeholder="Huyndai Elantra số tự động đăng ký tháng 09/2018. Xe gia đình mới đẹp, nội thất nguyên bản, sạch sẽ, bảo dưỡng thường xuyên, rửa xe miễn phí cho khách. Xe rộng rãi, an toàn, tiện nghi, phù hợp cho gia đình du lịch..."
                                    class="w-full resize-y rounded-2xl border border-slate-300 p-4 leading-relaxed outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"></textarea>
                            </section>

                            <section>
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-slate-900">Tính năng</h3>
                                </div>

                                <div class="grid grid-cols-2 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                    <label v-for="feature in featureItems" :key="feature.name"
                                        class="group cursor-pointer" @click.prevent="toggleFeature(feature.name)">
                                        <input type="checkbox" class="sr-only" :checked="feature.checked"
                                            :value="feature.name">

                                        <div class="relative flex flex-col h-full items-center gap-3 rounded-2xl border p-4 transition-all duration-200"
                                            :class="feature.checked
                                                ? 'border-[#1e4e57] bg-[#1e4e57]/8 shadow-md shadow-[#1e4e57]/10 ring-2 ring-[#1e4e57]/20'
                                                : 'border-slate-200 bg-white group-hover:border-[#1e4e57]/40 group-hover:bg-[#1e4e57]/5'">
                                            <!-- Checkmark badge khi đã chọn -->

                                            <img :src="feature.icon" :alt="feature.name"
                                                class="h-8 w-8 shrink-0 object-cover shadow-sm transition-transform duration-200"
                                                :class="feature.checked ? 'scale-110' : 'group-hover:scale-105'">

                                            <div class="min-w-0 text-center">
                                                <p class="text-sm font-semibold transition-colors"
                                                    :class="feature.checked ? 'text-[#1e4e57]' : 'text-slate-900'">{{
                                                        feature.name }}</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </section>
                        </div>

                        <div v-show="activeStep === 2" class="space-y-7">

                            <!-- SECTION 1: ĐƠN GIÁ THUÊ MẶC ĐỊNH -->
                            <section class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Đơn giá thuê mặc định</h3>
                                    <p class="mt-1 text-sm text-slate-500 leading-relaxed">
                                        Đơn giá áp dụng cho tất cả các ngày. Bạn có thể tuỳ chỉnh giá khác cho các ngày
                                        đặc biệt (cuối tuần, lễ tết...) trong mục quản lý xe sau khi đăng ký.
                                    </p>
                                </div>
                                <div class="space-y-1.5">
                                    <span class="text-xs text-slate-400">Giá đề xuất: 350.000 đ/ngày</span>
                                    <div class="relative max-w-xs flex items-center">
                                        <input type="text" id="base-price" :value="displayBasePrice" @input="handleBasePriceInput"
                                            placeholder="350.000"
                                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-16 outline-none transition font-bold text-lg text-slate-800 focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                        <span class="absolute right-4 font-bold text-slate-400">đ/ngày</span>
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
                                    <!-- Toggle -->
                                    <button type="button" role="switch" id="discount_toggle"
                                        :aria-checked="discountEnabled" @click="discountEnabled = !discountEnabled"
                                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-[#1e4e57]/40 focus:ring-offset-2"
                                        :class="discountEnabled ? 'bg-[#1e4e57]' : 'bg-slate-200'">
                                        <span
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-300"
                                            :class="discountEnabled ? 'translate-x-5' : 'translate-x-0'" />
                                    </button>
                                </div>

                                <div v-if="discountEnabled" class="space-y-4">
                                    <div class="space-y-2 max-w-xl">
                                        <input type="range" v-model="discountVal" min="5" max="50"
                                            class="w-full accent-[#1e4e57]">
                                        <div class="flex justify-between text-xs text-slate-500">
                                            <span>Mức giảm đề xuất: <strong class="text-slate-800">{{ discountVal
                                            }}%</strong></span>
                                            <span class="font-bold">50%</span>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- SECTION 3: TỐI ƯU NHẬN CHUYẾN -->
                            <!-- <section>
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900">Tối ưu nhận chuyến</h3>
                                        <p class="text-sm text-slate-500">Tự động xác nhận chuyến khi khách đặt xe</p>
                                    </div>
                                    <button type="button" role="switch" :aria-checked="optimizeEnabled"
                                        @click="optimizeEnabled = !optimizeEnabled"
                                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-[#1e4e57]/40 focus:ring-offset-2"
                                        :class="optimizeEnabled ? 'bg-[#1e4e57]' : 'bg-slate-200'">
                                        <span
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-300"
                                            :class="optimizeEnabled ? 'translate-x-5' : 'translate-x-0'" />
                                    </button>
                                </div>
                            </section> -->

                            <hr class="border-slate-100">

                            <!-- SECTION 4: ĐỊA CHỈ XE -->
                            <section class="space-y-3">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Địa chỉ xe</h3>
                                    <p class="mt-1 text-sm text-slate-500">Mốc giao nhận xe mặc định cho khách thuê.</p>
                                </div>
                                <div class="relative">
                                    <input type="text" v-model="address" @input="searchPlace"
                                        placeholder="Nhập địa chỉ mốc giao nhận xe..."
                                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-12 outline-none transition text-sm text-slate-700 focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                                        <i
                                            class="fa-solid fa-location-dot text-lg hover:text-[#1e4e57] cursor-pointer transition-colors"></i>
                                    </div>

                                    <!-- Suggestions Dropdown -->
                                    <div v-if="suggestions.length"
                                        class="absolute z-[100] left-0 right-0 mt-1 bg-white border border-slate-200 rounded-2xl shadow-xl max-h-60 overflow-y-auto divide-y divide-slate-100">
                                        <div v-for="item in suggestions" :key="item.place_id"
                                            class="p-4 hover:bg-slate-50 cursor-pointer text-sm text-slate-700 transition-colors"
                                            @click="selectPlace(item)">
                                            {{ item.description }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Map Container -->
                                <div id="map"
                                    class="w-full rounded-3xl overflow-hidden border border-slate-200 shadow-inner mt-4"
                                    style="height: 350px;"></div>
                            </section>

                            <hr class="border-slate-100">

                            <!-- SECTION 5: GIAO XE TẬN NƠI -->
                            <section class="space-y-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900">Giao xe tận nơi</h3>
                                        <p class="text-sm text-slate-500">Cho phép giao xe đến địa điểm khách yêu cầu
                                        </p>
                                    </div>
                                    <button type="button" role="switch" :aria-checked="deliveryEnabled"
                                        @click="deliveryEnabled = !deliveryEnabled"
                                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-[#1e4e57]/40 focus:ring-offset-2"
                                        :class="deliveryEnabled ? 'bg-[#1e4e57]' : 'bg-slate-200'">
                                        <span
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-300"
                                            :class="deliveryEnabled ? 'translate-x-5' : 'translate-x-0'" />
                                    </button>
                                </div>

                                <div v-if="deliveryEnabled"
                                    class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5 pt-1">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700">Quãng đường giao xe
                                            tối đa</label>
                                        <input type="range" v-model="maxDistVal" min="5" max="50"
                                            class="w-full accent-[#1e4e57]">
                                        <div class="flex justify-between text-xs text-slate-500">
                                            <span>Đề xuất: <strong class="text-slate-800">{{ maxDistVal
                                                    }}km</strong></span>
                                            <span class="font-bold">50km</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700">Phí giao nhận mỗi
                                            km</label>
                                        <input type="range" v-model="feeVal" min="1000" max="30000" step="1000"
                                            class="w-full accent-[#1e4e57]">
                                        <div class="flex justify-between text-xs text-slate-500">
                                            <span>Đề xuất: <strong class="text-slate-800">{{ Number(feeVal).toLocaleString('vi-VN')
                                                    }} đ/km</strong></span>
                                            <span class="font-bold">30.000 đ/km</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700">Miễn phí giao nhận
                                            trong vòng</label>
                                        <input type="range" v-model="freeLimitVal" min="0" max="15"
                                            class="w-full accent-[#1e4e57]">
                                        <div class="flex justify-between text-xs text-slate-500">
                                            <span>Đề xuất: <strong class="text-slate-800">{{ freeLimitVal
                                                    }}km</strong></span>
                                            <span class="font-bold">15km</span>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <hr class="border-slate-100">

                            <!-- SECTION 6: ĐIỀU KHOẢN THUÊ XE -->
                            <section class="space-y-3">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Điều khoản thuê xe</h3>
                                    <p class="mt-1 text-sm text-slate-500">Ghi rõ các yêu cầu để khách có thể thuê xe.
                                    </p>
                                </div>
                                <textarea rows="4" v-model="rentalTerms"
                                    class="w-full resize-y rounded-2xl border border-slate-300 p-4 outline-none transition text-slate-700 leading-relaxed text-sm bg-slate-50/60 hover:bg-white focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"></textarea>
                            </section>

                        </div>

                        <div v-show="activeStep === 3" class="space-y-8">
                            <!-- đây là chỗ để component nè -->
                            <!-- Component ImageUpload -->
                            <ImageUpload ref="imageUploadRef" v-model="uploadedImages" :max-files="5" />

                            <!-- Checklist -->
                            <section class="rounded-3xl border border-slate-100 bg-slate-50/70 p-6">
                                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Kiểm tra trước khi
                                    hoàn tất</h3>
                                <ul class="mt-4 space-y-3 text-sm text-slate-600">
                                    <li class="flex items-center gap-3">
                                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full"
                                            :class="uploadedImages.length > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-400'">
                                            <i class="fa-solid text-[10px]"
                                                :class="uploadedImages.length > 0 ? 'fa-check' : 'fa-minus'"></i>
                                        </span>
                                        <span
                                            :class="uploadedImages.length > 0 ? 'text-slate-700' : 'text-slate-400'">Đã
                                            tải lên ít nhất 1 ảnh xe</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span
                                            class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                        </span>
                                        <span class="text-slate-700">Điều khoản thuê xe đã được thiết lập</span>
                                    </li>
                                </ul>
                            </section>
                        </div>

                        <!-- BANNER TỔNG HỢP LỖI VALIDATE NẾU THIẾU NHIỀU TRƯỜNG -->
                        <div v-if="formValidationErrors.length > 0"
                            class="mt-6 rounded-2xl border border-rose-200 bg-rose-50/90 p-4 transition-all">
                            <div class="flex items-start gap-3">
                                <Icon name="heroicons:exclamation-circle"
                                    class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" />
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-rose-800">
                                        Vui lòng bổ sung đầy đủ thông tin ({{ formValidationErrors.length }} mục):
                                    </h4>
                                    <ul
                                        class="mt-2 space-y-1 text-xs font-semibold text-rose-700 list-disc list-inside">
                                        <li v-for="(err, idx) in formValidationErrors" :key="idx">
                                            <span class="text-rose-500 font-bold">[Bước {{ err.step }}]</span> {{
                                            err.message }}
                                        </li>
                                    </ul>
                                </div>
                                <button type="button" @click="formValidationErrors = []"
                                    class="text-rose-400 hover:text-rose-600 p-1 cursor-pointer">
                                    <Icon name="heroicons:x-mark" class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <div class="mt-10 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <button type="button"
                                class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-4 text-sm font-bold text-slate-700 transition hover:bg-slate-50 sm:w-auto"
                                :disabled="activeStep === 1"
                                :class="activeStep === 1 ? 'cursor-not-allowed opacity-50' : ''" @click="prevStep">
                                Quay lại
                            </button>

                            <button v-if="activeStep < 3" type="button"
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-[#1e4e57] px-6 py-4 text-sm font-bold text-white shadow-lg shadow-[#1e4e57]/20 transition hover:bg-[#286874] sm:w-auto"
                                @click="nextStep">
                                Kế tiếp
                            </button>
                            <button v-else type="button" @click="onSubmit" :disabled="submitting"
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-[#1e4e57] px-6 py-4 text-sm font-bold text-white shadow-lg shadow-[#1e4e57]/20 transition hover:bg-[#286874] sm:w-auto disabled:opacity-50">
                                <span v-if="submitting">Đang đăng ký...</span>
                                <span v-else>Hoàn tất đăng ký</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- MODAL CẬP NHẬT SỐ ĐIỆN THOẠI TẠI CHỖ -->
        <Teleport to="body">
            <Transition name="fade-modal">
                <div v-if="isProfileModalOpen"
                    class="fixed inset-0 z-[90000] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                    @click.self="isProfileModalOpen = false">
                    <div
                        class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl p-6 sm:p-7 border border-slate-100 overflow-hidden">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-2xl bg-[#1e4e57]/10 flex items-center justify-center text-[#1e4e57]">
                                    <Icon name="solar:phone-calling-rounded-bold" class="w-5 h-5 text-[#1e4e57]" />
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Cập nhật Số điện thoại</h3>
                                    <p class="text-xs text-slate-500">Cần có SĐT để khách thuê liên hệ khi nhận xe</p>
                                </div>
                            </div>
                            <button type="button" @click="isProfileModalOpen = false"
                                class="text-slate-400 hover:text-slate-600 transition-colors p-1.5 rounded-full hover:bg-slate-100 cursor-pointer">
                                <Icon name="heroicons:x-mark" class="w-5 h-5" />
                            </button>
                        </div>

                        <form @submit.prevent="submitProfileUpdate" class="mt-5 space-y-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700">Họ và tên <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" v-model="profileForm.name" @input="profileErrors.name = ''"
                                    placeholder="Nhập họ và tên đầy đủ"
                                    class="w-full px-4 py-3 bg-slate-50 border rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:bg-white focus:ring-4 transition-all"
                                    :class="profileErrors.name ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/10' : 'border-slate-200 focus:border-[#1e4e57] focus:ring-[#1e4e57]/10'" />
                                <p v-if="profileErrors.name" class="text-xs text-rose-500 font-medium mt-1">{{
                                    profileErrors.name }}</p>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700">Số điện thoại liên hệ <span
                                        class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"
                                        :class="profileErrors.phone ? 'text-rose-400' : 'text-slate-400'">
                                        <Icon name="solar:phone-calling-rounded-linear" class="w-4 h-4" />
                                    </div>
                                    <input type="tel" v-model="profileForm.phone" @input="profileErrors.phone = ''"
                                        placeholder="VD: 0912345678"
                                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border rounded-2xl text-sm font-semibold text-slate-800 focus:outline-none focus:bg-white focus:ring-4 transition-all"
                                        :class="profileErrors.phone ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/10' : 'border-slate-200 focus:border-[#1e4e57] focus:ring-[#1e4e57]/10'" />
                                </div>
                                <p v-if="profileErrors.phone" class="text-xs text-rose-500 font-medium mt-1">{{
                                    profileErrors.phone }}</p>
                                <p v-else class="text-[11px] text-slate-400">Số điện thoại gồm 10 chữ số (VD:
                                    0912345678).</p>
                            </div>

                            <div class="pt-3 flex items-center justify-end gap-3">
                                <button type="button" @click="isProfileModalOpen = false"
                                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer">
                                    Để sau
                                </button>
                                <button type="submit" :disabled="profileSubmitting"
                                    class="px-5 py-2.5 rounded-xl bg-[#1e4e57] text-xs font-bold text-white shadow-lg shadow-[#1e4e57]/20 hover:bg-[#286874] transition-all disabled:opacity-50 cursor-pointer">
                                    <span v-if="profileSubmitting">Đang lưu...</span>
                                    <span v-else>Lưu & Tiếp tục</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup lang="ts">
useSeoMeta({
    title: 'Đăng Ký Cho Thuê Xe — Kiếm Thu Nhập Thụ Động | DRIVIO',
    description: 'Đăng ký cho thuê xe trên DRIVIO — kiếm thu nhập thụ động từ xe nhàn rỗi. Bảo hiểm toàn diện, hỗ trợ quản lý xe, thanh toán nhanh chóng.',
    keywords: 'đăng ký cho thuê xe, cho thuê xe kiếm tiền, chủ xe drivio, thu nhập thụ động xe ô tô, đăng xe cho thuê',
    ogTitle: 'Trở Thành Chủ Xe Trên DRIVIO',
    ogDescription: 'Cho thuê xe nhàn rỗi, kiếm thu nhập thụ động với DRIVIO.',
    ogImage: '/images/og/car-register.jpg',
    twitterCard: 'summary_large_image',
})

useHead({
    link: [
        { rel: 'canonical', href: 'https://drivio.vn/car-register' }
    ]
})

import { ref, onMounted, onUnmounted, nextTick, computed } from 'vue'
import { carService } from '~/services/car.service'
import { useAuth } from '~/composables/useAuth'
import { useToast } from '~/composables/useToast'
import OwnerBanner from '~/components/Banner/OwnerBanner.vue'

const { showToast } = useToast()

let maplibregl: any = null

// Goong Map Consts & Refs
import { GOONG_API_KEY as API_KEY, GOONG_MAP_KEY as MAP_KEY } from '~/constants/goong'

const suggestions = ref<any[]>([])
const mapRef = ref<any>(null)
const currentMarker = ref<any>(null)
const selectedCoords = ref<{ lat: number; lng: number } | null>(null)

const activeStep = ref(1)

// New registration form refs
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
const handleFuelConsumptionInput = () => {
    if (fuelConsumption.value !== null && fuelConsumption.value !== undefined) {
        if (Number(fuelConsumption.value) < 0) {
            fuelConsumption.value = 0
        }
    }
}
const description = ref('')
const basePrice = ref<number>(350000)

const displayBasePrice = computed(() => {
    if (!basePrice.value) return ''
    return new Intl.NumberFormat('vi-VN').format(basePrice.value)
})

const handleBasePriceInput = (e: Event) => {
    const target = e.target as HTMLInputElement
    const raw = target.value.replace(/\D/g, '')
    const num = raw ? parseInt(raw, 10) : 0
    basePrice.value = num
    target.value = num ? new Intl.NumberFormat('vi-VN').format(num) : ''
}

const address = ref('')
const rentalTerms = ref('Không sử dụng xe vào mục đích phi pháp. Lái xe cẩn thận, giữ xe sạch sẽ, trả xe đúng giờ. Phụ thu 500.000 đ nếu hút thuốc lá trong xe.')

const brands = ref<any[]>([])
const carTypes = ref<any[]>([])
const manufactureYears = ref<number[]>([])
const submitting = ref(false)

// State quản lý Searchable Select
const isBrandOpen = ref(false)
const brandSearch = ref('')
const isTypeOpen = ref(false)
const typeSearch = ref('')

const currentYear = new Date().getFullYear()
for (let y = currentYear + 1; y >= 2005; y--) {
    manufactureYears.value.push(y)
}

const steps = [
    {
        id: 1,
        title: 'Thông tin xe',
        summary: 'Biển số và cấu hình cơ bản'
    },
    {
        id: 2,
        title: 'Thông tin cho thuê',
        summary: 'Đơn giá, điều khoản và các thiết lập cho thuê'
    },
    {
        id: 3,
        title: 'Hình ảnh & hoàn tất',
        summary: 'Upload ảnh và kiểm tra lại hồ sơ'
    }
]

// Step 2 reactive state
const discountEnabled = ref(true)
const discountVal = ref(20)
const optimizeEnabled = ref(false)
const deliveryEnabled = ref(true)
const maxDistVal = ref(20)
const feeVal = ref(10000)
const freeLimitVal = ref(0)

const buildFeatureIcon = (label: string, background: string) => {
    const svg = `
        <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 96 96" fill="none">
            <rect x="6" y="6" width="84" height="84" rx="24" fill="${background}"/>
            <rect x="18" y="20" width="60" height="44" rx="14" fill="rgba(255,255,255,0.12)"/>
            <text x="48" y="57" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="22" font-weight="700" fill="#FFFFFF">${label}</text>
        </svg>
    `

    return `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(svg)}`
}

const featureItems = ref<any[]>([])

const toggleFeature = (name: string) => {
    const item = featureItems.value.find((f) => f.name === name)
    if (!item) return
    item.checked = !item.checked
}

// Step 3 - Image upload
const uploadedImages = ref<string[]>([])
const imageUploadRef = ref<any>(null)

const { user, isLoggedIn, updateProfile, refreshProfile } = useAuth()
const { openLogin } = useAuthModal()

const isProfileModalOpen = ref(false)
const profileForm = ref({ name: '', phone: '' })
const profileErrors = ref<{ name?: string; phone?: string; general?: string }>({})
const profileSubmitting = ref(false)
const formValidationErrors = ref<{ step: number; message: string }[]>([])

const submitProfileUpdate = async () => {
    profileErrors.value = {}
    const name = profileForm.value.name.trim()
    const phone = profileForm.value.phone.trim()

    let hasError = false
    if (!name) {
        profileErrors.value.name = 'Vui lòng nhập họ và tên của bạn.'
        hasError = true
    } else if (name.length < 2) {
        profileErrors.value.name = 'Họ và tên phải có ít nhất 2 ký tự.'
        hasError = true
    } else if (name.length > 50) {
        profileErrors.value.name = 'Họ và tên không được vượt quá 50 ký tự.'
        hasError = true
    }

    if (!phone) {
        profileErrors.value.phone = 'Vui lòng nhập số điện thoại liên hệ.'
        hasError = true
    } else {
        const phoneRegex = /^(0|\+84)(3|5|7|8|9|2)\d{8}$/
        if (!phoneRegex.test(phone)) {
            profileErrors.value.phone = 'Số điện thoại không hợp lệ (VD: 0912345678, gồm 10 chữ số).'
            hasError = true
        }
    }

    if (hasError) {
        showToast('Vui lòng kiểm tra và hoàn thiện thông tin.', 'error')
        return
    }

    profileSubmitting.value = true
    try {
        const res = await updateProfile({ name, phone })
        if (res && res.success) {
            showToast('Cập nhật thông tin thành công! Bạn có thể tiếp tục đăng ký xe.', 'success')
            isProfileModalOpen.value = false
            profileErrors.value = {}
        } else {
            if (res?.errors) {
                if (res.errors.phone) profileErrors.value.phone = Array.isArray(res.errors.phone) ? res.errors.phone[0] : res.errors.phone
                if (res.errors.name) profileErrors.value.name = Array.isArray(res.errors.name) ? res.errors.name[0] : res.errors.name
            }
            showToast(res?.message || 'Cập nhật thông tin thất bại.', 'error')
        }
    } catch (err: any) {
        const errData = err.data || err.response?._data || err.response?.data
        if (errData?.errors) {
            if (errData.errors.phone) profileErrors.value.phone = Array.isArray(errData.errors.phone) ? errData.errors.phone[0] : errData.errors.phone
            if (errData.errors.name) profileErrors.value.name = Array.isArray(errData.errors.name) ? errData.errors.name[0] : errData.errors.name
        }
        showToast(errData?.message || 'Có lỗi xảy ra khi cập nhật thông tin.', 'error')
    } finally {
        profileSubmitting.value = false
    }
}

// Computed filters phục vụ tính năng tìm kiếm real-time
const filteredBrands = computed(() => {
    if (!brandSearch.value.trim()) return brands.value
    return brands.value.filter(b =>
        b.brand_name.toLowerCase().includes(brandSearch.value.toLowerCase())
    )
})

const filteredCarTypes = computed(() => {
    if (!typeSearch.value.trim()) return carTypes.value
    return carTypes.value.filter(t =>
        t.type_name.toLowerCase().includes(typeSearch.value.toLowerCase())
    )
})

const selectedBrand = computed(() => {
    return brands.value.find(b => b.id === selectedBrandId.value) || null
})

const selectedType = computed(() => {
    return carTypes.value.find(t => t.id === selectedTypeId.value) || null
})

// Các hàm tương tác đóng mở / chọn của Hãng Xe
const toggleBrandDropdown = () => {
    isBrandOpen.value = !isBrandOpen.value
    if (isBrandOpen.value) {
        isTypeOpen.value = false
        brandSearch.value = ''
    }
}

const selectBrand = (brand: any) => {
    selectedBrandId.value = brand.id
    isBrandOpen.value = false
    brandSearch.value = ''
    onBrandChange()
}

// Các hàm tương tác đóng mở / chọn của Mẫu Xe
const toggleTypeDropdown = () => {
    if (!selectedBrandId.value) return
    isTypeOpen.value = !isTypeOpen.value
    if (isTypeOpen.value) {
        isBrandOpen.value = false
        typeSearch.value = ''
    }
}

const selectType = (type: any) => {
    selectedTypeId.value = type.id
    isTypeOpen.value = false
    typeSearch.value = '' // Thêm dòng này để reset từ khóa mẫu xe
}

const loadBrands = async () => {
    try {
        const res = await carService.getBrands();
        if (res && res.success) {
            brands.value = res.data;
        }
    } catch (e) {
        console.error('Không tải được danh sách hãng xe:', e);
    }
}

const onBrandChange = async () => {
    selectedTypeId.value = null;
    carTypes.value = [];
    if (!selectedBrandId.value) return;
    try {
        const res = await carService.getTypes(selectedBrandId.value);
        if (res && res.success) {
            carTypes.value = res.data;
        }
    } catch (e) {
        console.error('Không tải được danh sách mẫu xe:', e);
    }
}

const loadFeatures = async () => {
    try {
        const res = await carService.getFeatures();
        if (res && res.success && res.data.length > 0) {
            featureItems.value = res.data.map((item: any) => ({
                id: item.id,
                name: item.feature_name,
                checked: false,
                icon: item.icon
            }));
        }
    } catch (e) {
        console.error('Không tải được danh sách tính năng:', e);
    }
}

const onSubmit = async () => {
    if (!user.value?.phone || !user.value?.phone.trim()) {
        profileForm.value.name = user.value?.name || '';
        profileForm.value.phone = user.value?.phone || '';
        isProfileModalOpen.value = true;
        showToast('Vui lòng cập nhật số điện thoại để tiếp tục đăng ký xe.', 'warning');
        return;
    }

    const validationErrors: { step: number; message: string }[] = [];

    const plate = licensePlate.value?.trim().toUpperCase() || '';
    if (!plate) {
        validationErrors.push({ step: 1, message: 'Vui lòng nhập biển số xe.' });
    } else if (!/^[A-Za-z0-9]+$/.test(plate)) {
        validationErrors.push({ step: 1, message: 'Biển số xe chỉ chứa chữ cái và số.' });
    } else if (plate.length > 12) {
        validationErrors.push({ step: 1, message: 'Biển số xe không được vượt quá 12 ký tự.' });
    }

    const vin = VIN.value?.trim().toUpperCase() || '';
    if (!vin) {
        validationErrors.push({ step: 1, message: 'Vui lòng nhập số khung (VIN).' });
    } else if (vin.length !== 17) {
        validationErrors.push({ step: 1, message: 'Số khung (VIN) phải gồm đúng 17 ký tự.' });
    }

    const engine = engineNumber.value?.trim().toUpperCase() || '';
    if (!engine) {
        validationErrors.push({ step: 1, message: 'Vui lòng nhập số máy.' });
    } else if (engine.length > 100) {
        validationErrors.push({ step: 1, message: 'Số máy không được vượt quá 100 ký tự.' });
    }

    if (!selectedBrandId.value) {
        validationErrors.push({ step: 1, message: 'Vui lòng chọn hãng xe.' });
    }

    if (!selectedTypeId.value) {
        validationErrors.push({ step: 1, message: 'Vui lòng chọn mẫu xe.' });
    }

    const consumption = Number(fuelConsumption.value);
    if (fuelConsumption.value === null || fuelConsumption.value === undefined || isNaN(consumption) || consumption <= 0) {
        validationErrors.push({ step: 1, message: 'Mức tiêu thụ nhiên liệu phải lớn hơn 0 (L/100km).' });
    }

    const price = Number(basePrice.value);
    if (!basePrice.value || isNaN(price) || price <= 0) {
        validationErrors.push({ step: 2, message: 'Đơn giá thuê xe phải lớn hơn 0.' });
    }

    if (!address.value?.trim()) {
        validationErrors.push({ step: 2, message: 'Vui lòng nhập địa chỉ xe.' });
    }

    if (uploadedImages.value.length === 0) {
        validationErrors.push({ step: 3, message: 'Vui lòng tải lên ít nhất 1 hình ảnh của xe.' });
    }

    formValidationErrors.value = validationErrors;

    if (validationErrors.length > 0) {
        activeStep.value = validationErrors[0]?.step ?? 1;
        validationErrors.forEach((err, index) => {
            setTimeout(() => {
                showToast(err.message, 'error');
            }, index * 80);
        });
        return;
    }

    submitting.value = true;
    let formData = new FormData();

    try {
        formData.append('license_plate', plate);
        formData.append('VIN', vin);
        formData.append('engine_number', engine);
        formData.append('car_brand_id', selectedBrandId.value!.toString());
        formData.append('car_type_id', selectedTypeId.value!.toString());
        formData.append('seat_count', selectedSeatCount.value.toString());
        formData.append('manufacture_year', selectedManufactureYear.value.toString());
        formData.append('transmission', selectedTransmission.value);
        formData.append('fuel_type', selectedFuelType.value);
        formData.append('fuel_consumption', consumption.toString());
        formData.append('description', description.value);

        // Price (unit_price in VND)
        const unitPriceVal = price;
        formData.append('unit_price', unitPriceVal.toString());

        // Discount value
        let discountValue = 0;
        if (discountEnabled.value) {
            discountValue = Math.round(unitPriceVal * (discountVal.value / 100));
        }
        formData.append('discount_value', discountValue.toString());

        // Location address
        formData.append('address', address.value.trim());
        if (selectedCoords.value) {
            formData.append('location', `${selectedCoords.value.lat},${selectedCoords.value.lng}`);
        } else {
            formData.append('location', '');
        }

        // Delivery options
        formData.append('delivery_enabled', deliveryEnabled.value ? '1' : '0');
        formData.append('delivery_max_distance', maxDistVal.value.toString());
        formData.append('delivery_fee', feeVal.value.toString());
        formData.append('delivery_free_distance', freeLimitVal.value.toString());

        // Rental terms
        formData.append('rental_terms', rentalTerms.value);

        // Features
        const selectedFeatureIds = featureItems.value
            .filter((f: any) => f.checked)
            .map((f: any) => f.id || null)
            .filter((id: any) => id !== null);
        formData.append('features', JSON.stringify(selectedFeatureIds));

        // Upload ảnh lên Cloudinary
        const imageUrls = await imageUploadRef.value?.upload()

        if (!imageUrls || imageUrls.length === 0) {
            showToast("Không thể upload hình ảnh. Vui lòng thử lại.", "error")
            return
        }

        // gửi đúng format Laravel đang cần
        formData.append("images", JSON.stringify(imageUrls))
        formData.append("thumbnail_index", "0")
        const res = await carService.registerCar(formData);

        if (res && res.success) {
            showToast('Đăng ký xe thành công! Xe của bạn đang chờ kiểm duyệt.', 'success');
            navigateTo('/my-cars');
        } else {
            showToast(res.message || 'Đăng ký xe thất bại.', 'error');
        }
    } catch (e: any) {
        console.error('Đăng ký xe thất bại:', e);
        const data = e.data || e.response?._data || e.response?.data;
        if (data && data.errors) {
            const errorsList = Object.values(data.errors).flatMap((err: any) => err);
            if (errorsList.length > 0) {
                errorsList.forEach((msg: any, index: number) => {
                    setTimeout(() => {
                        showToast(String(msg), 'error');
                    }, index * 120);
                });
                return;
            }
        }
        const errMsg = data?.message || e.message || 'Đăng ký xe thất bại. Vui lòng kiểm tra lại thông tin.';
        showToast(errMsg, 'error');
    } finally {
        submitting.value = false;
    }
}

const generateMap = async () => {
    await nextTick();
    const container = document.getElementById('map');
    if (!container) return;

    // Destroy old map instance if it exists to bind to the new DOM container
    if (mapRef.value) {
        try {
            mapRef.value.remove();
        } catch (e) {
            console.error('Lỗi khi hủy bản đồ cũ:', e);
        }
        mapRef.value = null;
        currentMarker.value = null;
    }

    const map = new maplibregl.Map({
        container: 'map',
        style: `https://tiles.goong.io/assets/goong_map_web.json?api_key=${MAP_KEY}`,
        center: selectedCoords.value ? [selectedCoords.value.lng, selectedCoords.value.lat] : [106.660172, 10.762622], // use selected or fallback center
        zoom: selectedCoords.value ? 16 : 13
    });

    map.addControl(new maplibregl.NavigationControl(), "top-right");

    map.on('load', () => {
        if (selectedCoords.value) {
            const { lat, lng } = selectedCoords.value;
            const popup = new maplibregl.Popup({
                offset: 25
            }).setText(address.value);

            currentMarker.value = new maplibregl.Marker({ color: 'red' })
                .setLngLat([lng, lat])
                .setPopup(popup)
                .addTo(map)
                .togglePopup();
        } else {
            navigator.geolocation.getCurrentPosition((position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                map.setCenter([lng, lat]);

                if (currentMarker.value) currentMarker.value.remove();

                currentMarker.value = new maplibregl.Marker({
                    color: 'red',
                }).setLngLat([lng, lat]).addTo(map);
            }, () => {
                if (currentMarker.value) currentMarker.value.remove();

                currentMarker.value = new maplibregl.Marker({
                    color: 'red',
                }).setLngLat([106.660172, 10.762622]).addTo(map);
            });
        }

        setTimeout(() => {
            map.resize();
        }, 200);
    });

    mapRef.value = map;
};

const searchPlace = async () => {
    if (!address.value) {
        suggestions.value = [];
        return;
    }

    try {
        const res = await fetch(
            `https://rsapi.goong.io/Place/AutoComplete?api_key=${API_KEY}&input=${encodeURIComponent(address.value)}`
        );
        const data = await res.json();
        suggestions.value = data.predictions || [];
    } catch (error) {
        console.error('Lỗi khi gọi API AutoComplete:', error);
    }
};

const selectPlace = async (item: any) => {
    address.value = item.description;
    suggestions.value = [];

    try {
        const res = await fetch(
            `https://rsapi.goong.io/Place/Detail?place_id=${item.place_id}&api_key=${API_KEY}`
        );
        const data = await res.json();
        if (data.result && data.result.geometry) {
            const location = data.result.geometry.location;
            const lat = location.lat;
            const lng = location.lng;

            selectedCoords.value = { lat, lng };

            if (mapRef.value) {
                mapRef.value.setCenter([lng, lat]);
                mapRef.value.setZoom(16);

                const popup = new maplibregl.Popup({
                    offset: 25
                }).setText(item.description);

                if (currentMarker.value) {
                    currentMarker.value.remove();
                }

                currentMarker.value = new maplibregl.Marker({ color: 'red' })
                    .setLngLat([lng, lat])
                    .setPopup(popup)
                    .addTo(mapRef.value)
                    .togglePopup();
            }
        }
    } catch (error) {
        console.error('Lỗi khi lấy thông tin chi tiết địa điểm:', error);
    }
};

const nextStep = () => {
    activeStep.value = Math.min(3, activeStep.value + 1);
    if (activeStep.value === 2) {
        nextTick(() => {
            generateMap();
        });
    }
};

const prevStep = () => {
    activeStep.value = Math.max(1, activeStep.value - 1);
    if (activeStep.value === 2) {
        nextTick(() => {
            generateMap();
        });
    }
};

const handleOutsideClick = (e: MouseEvent) => {
    const target = e.target as HTMLElement
    if (isBrandOpen.value && !target.closest('.id-brand-dropdown')) {
        isBrandOpen.value = false
    }
    if (isTypeOpen.value && !target.closest('.id-type-dropdown')) {
        isTypeOpen.value = false
    }
}

onMounted(async () => {
    if (!isLoggedIn.value) {
        showToast('Vui lòng đăng nhập trước khi đăng ký xe.', 'error');
        openLogin();
        navigateTo('/');
        return;
    }

    await refreshProfile();

    if (!user.value?.phone || !user.value?.phone.trim()) {
        profileForm.value.name = user.value?.name || '';
        profileForm.value.phone = user.value?.phone || '';
        isProfileModalOpen.value = true;
        showToast('Vui lòng cập nhật số điện thoại trước khi đăng ký xe.', 'info');
    }

    if (process.client) {
        try {
            const module = await import('maplibre-gl');
            maplibregl = module.default;
            await import('maplibre-gl/dist/maplibre-gl.css');
        } catch (e) {
            console.error('Không tải được thư viện bản đồ:', e);
        }
        // Lắng nghe sự kiện click ngoài để ẩn dropdown
        window.addEventListener('click', handleOutsideClick)
    }
    await loadBrands();
    await loadFeatures();
})

// Hủy lắng nghe khi hủy component tránh rò rỉ bộ nhớ
onUnmounted(() => {
    if (process.client) {
        window.removeEventListener('click', handleOutsideClick)
    }
})
</script>

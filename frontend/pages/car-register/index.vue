<template>
    <div class="min-h-screen bg-slate-50 text-slate-900">
        <section class="relative overflow-hidden bg-gradient-to-br from-[#173f47] via-[#1e4e57] to-[#2b6f7a] text-white">
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
        </section>

        <section class="mt-12 pb-14 mw-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <form class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.10)]">
                    <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-4 sm:px-8">
                            <div>
                                <h2 class="mt-2 mb-4 text-center text-2xl font-black text-slate-900">Đăng ký xe</h2>
                            </div>
                        <div class="flex flex-col gap-4 lg:items-center lg:justify-between">
                            <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols">
                                <div
                                    v-for="step in steps"
                                    :key="step.id"
                                    class="flex items-center gap-3 rounded-2xl border px-4 py-3 transition"
                                    :class="activeStep === step.id
                                        ? 'border-[#1e4e57]/20 bg-[#1e4e57]/5 text-[#1e4e57]'
                                        : 'border-slate-200 bg-white text-slate-500'"
                                >
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
                        <div v-if="activeStep === 1" class="space-y-8">
                            <section>
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-slate-900">Biển số xe</h3>
                                    <p class="text-sm text-red-500/90">Lưu ý: Biển số sẽ không thể thay đổi sau khi đăng ký.</p>
                                </div>
                                <input type="text" placeholder="Nhập biển số xe (VD: 51H-123.45)" class="w-full rounded-2xl border border-slate-300 px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10 sm:w-1/2">
                            </section>
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-slate-900">Thông tin cơ bản</h3>
                                    <p class="text-sm text-slate-500">Nhóm các thông tin cần khai báo một lần cho xe.</p>
                                </div>

                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-slate-700">Hãng xe</label>
                                        <select class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                            <option>Chọn hãng xe</option>
                                            <option>Toyota</option>
                                            <option>Honda</option>
                                            <option>Mazda</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-slate-700">Mẫu xe</label>
                                        <select class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                            <option>Chọn dòng xe trước</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-slate-700">Số ghế</label>
                                        <select class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                            <option>4</option>
                                            <option>5</option>
                                            <option>7</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-slate-700">Năm sản xuất</label>
                                        <select class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                            <option>2026</option>
                                            <option>2025</option>
                                            <option>2024</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-slate-700">Truyền động</label>
                                        <select class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                            <option>Số tự động</option>
                                            <option>Số sàn</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-slate-700">Nhiên liệu</label>
                                        <select class="w-full cursor-pointer appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                            <option>Xăng</option>
                                            <option>Dầu Diesel</option>
                                            <option>Điện</option>
                                        </select>
                                    </div>
                                </div>
                        
                            <section>
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-slate-900">Mức tiêu thụ nhiên liệu</h3>
                                    <p class="text-sm text-slate-500">Số lít nhiên liệu cho quãng đường 100km.</p>
                                </div>
                                <div class="relative w-full sm:w-1/3">
                                    <input type="number" value="10" class="w-full rounded-2xl border border-slate-300 px-4 py-3 outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10">
                                </div>
                            </section>
                                                        <section>
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-slate-900">Mô tả</h3>
                                    <p class="text-sm text-slate-500">Mô tả ngắn gọn về chiếc xe và điểm mạnh của nó.</p>
                                </div>
                                <textarea rows="5" placeholder="Huyndai Elantra số tự động đăng ký tháng 09/2018. Xe gia đình mới đẹp, nội thất nguyên bản, sạch sẽ, bảo dưỡng thường xuyên, rửa xe miễn phí cho khách. Xe rộng rãi, an toàn, tiện nghi, phù hợp cho gia đình du lịch..." class="w-full resize-y rounded-2xl border border-slate-300 p-4 leading-relaxed outline-none transition focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"></textarea>
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
                                        <input
                                            type="checkbox"
                                            class="sr-only"
                                            :checked="feature.checked"
                                            :value="feature.name"
                                        >

                                        <div
                                            class="relative flex flex-col h-full items-center gap-3 rounded-2xl border p-4 transition-all duration-200"
                                            :class="feature.checked
                                                ? 'border-[#1e4e57] bg-[#1e4e57]/8 shadow-md shadow-[#1e4e57]/10 ring-2 ring-[#1e4e57]/20'
                                                : 'border-slate-200 bg-white group-hover:border-[#1e4e57]/40 group-hover:bg-[#1e4e57]/5'"
                                        >
                                            <!-- Checkmark badge khi đã chọn -->

                                            <img :src="feature.icon" :alt="feature.name" class="h-8 w-8 shrink-0 object-cover shadow-sm transition-transform duration-200" :class="feature.checked ? 'scale-110' : 'group-hover:scale-105'">

                                            <div class="min-w-0 text-center">
                                                <p class="text-sm font-semibold transition-colors" :class="feature.checked ? 'text-[#1e4e57]' : 'text-slate-900'">{{ feature.name }}</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </section>
                        </div>

                        <div v-else-if="activeStep === 2" class="space-y-7">

                            <!-- SECTION 1: ĐƠN GIÁ THUÊ MẶC ĐỊNH -->
                            <section class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Đơn giá thuê mặc định</h3>
                                    <p class="mt-1 text-sm text-slate-500 leading-relaxed">
                                        Đơn giá áp dụng cho tất cả các ngày. Bạn có thể tuỳ chỉnh giá khác cho các ngày đặc biệt (cuối tuần, lễ tết...) trong mục quản lý xe sau khi đăng ký.
                                    </p>
                                </div>
                                <div class="space-y-1.5">
                                    <span class="text-xs text-slate-400">Giá đề xuất: 350.000 ₫/ngày</span>
                                    <div class="relative max-w-xs flex items-center">
                                        <input
                                            type="number"
                                            id="base-price"
                                            value="350"
                                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-10 outline-none transition font-bold text-lg text-slate-800 focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                        >
                                        <span class="absolute right-4 font-bold text-slate-400">K</span>
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
                                    <button
                                        type="button"
                                        role="switch"
                                        id="discount_toggle"
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
                                            <span>Mức giảm đề xuất: <strong class="text-slate-800">{{ discountVal }}%</strong></span>
                                            <span class="font-bold">5%</span>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 max-w-2xl">
                                        <i class="fa-solid fa-circle-exclamation text-amber-500 mt-0.5 text-base shrink-0"></i>
                                        <p class="text-amber-800 text-sm leading-relaxed">
                                            Khi tính năng được thiết lập, hệ thống sẽ tự động nhận chuyến ngay sau khi khách thuê đặt xe. Tính năng giúp cải thiện tỷ lệ nhận chuyến từ <strong>80% – 120%</strong>.
                                        </p>
                                    </div>
                                </div>
                            </section>

                            <hr class="border-slate-100">

                            <!-- SECTION 3: TỐI ƯU NHẬN CHUYẾN -->
                            <section>
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900">Tối ưu nhận chuyến</h3>
                                        <p class="text-sm text-slate-500">Tự động xác nhận chuyến khi khách đặt xe</p>
                                    </div>
                                    <button
                                        type="button"
                                        role="switch"
                                        :aria-checked="optimizeEnabled"
                                        @click="optimizeEnabled = !optimizeEnabled"
                                        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-[#1e4e57]/40 focus:ring-offset-2"
                                        :class="optimizeEnabled ? 'bg-[#1e4e57]' : 'bg-slate-200'"
                                    >
                                        <span
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-300"
                                            :class="optimizeEnabled ? 'translate-x-5' : 'translate-x-0'"
                                        />
                                    </button>
                                </div>
                            </section>

                            <hr class="border-slate-100">

                            <!-- SECTION 4: ĐỊA CHỈ XE -->
                            <section class="space-y-3">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Địa chỉ xe</h3>
                                    <p class="mt-1 text-sm text-slate-500">Mốc giao nhận xe mặc định cho khách thuê.</p>
                                </div>
                                <div class="relative">
                                    <input
                                        type="text"
                                        placeholder="Nhập địa chỉ mốc giao nhận xe..."
                                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-12 outline-none transition text-sm text-slate-700 focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"
                                    >
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                                        <i class="fa-solid fa-location-dot text-lg hover:text-[#1e4e57] cursor-pointer transition-colors"></i>
                                    </div>
                                </div>
                            </section>

                            <hr class="border-slate-100">

                            <!-- SECTION 5: GIAO XE TẬN NƠI -->
                            <section class="space-y-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900">Giao xe tận nơi</h3>
                                        <p class="text-sm text-slate-500">Cho phép giao xe đến địa điểm khách yêu cầu</p>
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
                                            <span>Đề xuất: <strong class="text-slate-800">{{ maxDistVal }}km</strong></span>
                                            <span class="font-bold">50km</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700">Phí giao nhận mỗi km</label>
                                        <input type="range" v-model="feeVal" min="1" max="30" class="w-full accent-[#1e4e57]">
                                        <div class="flex justify-between text-xs text-slate-500">
                                            <span>Đề xuất: <strong class="text-slate-800">{{ feeVal }}K/km</strong></span>
                                            <span class="font-bold">30K</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700">Miễn phí giao nhận trong vòng</label>
                                        <input type="range" v-model="freeLimitVal" min="0" max="15" class="w-full accent-[#1e4e57]">
                                        <div class="flex justify-between text-xs text-slate-500">
                                            <span>Đề xuất: <strong class="text-slate-800">{{ freeLimitVal }}km</strong></span>
                                            <span class="font-bold">15km</span>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <hr class="border-slate-100">

                            <!-- SECTION 6: GIỚI HẠN SỐ KM -->
                            <section class="space-y-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900">Giới hạn số km</h3>
                                        <p class="text-sm text-slate-500">Đặt giới hạn km tối đa và phí vượt mức</p>
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
                                            <span>Đề xuất: <strong class="text-slate-800">{{ kmLimitVal }}km</strong></span>
                                            <span class="font-bold">500km</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-slate-700">Phí vượt giới hạn (mỗi km)</label>
                                        <input type="range" v-model="overFeeVal" min="1" max="10" class="w-full accent-[#1e4e57]">
                                        <div class="flex justify-between text-xs text-slate-500">
                                            <span>Đề xuất: <strong class="text-slate-800">{{ overFeeVal }}K/km</strong></span>
                                            <span class="font-bold">10K</span>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <hr class="border-slate-100">

                            <!-- SECTION 7: ĐIỀU KHOẢN THUÊ XE -->
                            <section class="space-y-3">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Điều khoản thuê xe</h3>
                                    <p class="mt-1 text-sm text-slate-500">Ghi rõ các yêu cầu để khách có thể thuê xe.</p>
                                </div>
                                <textarea
                                    rows="4"
                                    class="w-full resize-y rounded-2xl border border-slate-300 p-4 outline-none transition text-slate-700 leading-relaxed text-sm bg-slate-50/60 hover:bg-white focus:border-[#1e4e57] focus:ring-4 focus:ring-[#1e4e57]/10"
                                >Không sử dụng xe vào mục đích phi pháp. Lái xe cẩn thận, giữ xe sạch sẽ, trả xe đúng giờ. Phụ thu 500k nếu hút thuốc lá trong xe.</textarea>
                            </section>

                        </div>

                        <div v-else class="space-y-8">

                            <!-- Upload Zone -->
                            <section class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Hình ảnh xe</h3>
                                    <p class="mt-1 text-sm text-slate-500">Chọn nhiều ảnh thật để tăng độ tin cậy. Bấm vào ảnh để đặt làm <strong class="text-[#1e4e57]">ảnh đại diện</strong>.</p>
                                </div>

                                <!-- Drop Zone -->
                                <div
                                    class="relative flex flex-col items-center justify-center gap-4 rounded-3xl border-2 border-dashed transition-all duration-200 p-10 text-center cursor-pointer"
                                    :class="isDragging
                                        ? 'border-[#1e4e57] bg-[#1e4e57]/8 scale-[1.01]'
                                        : 'border-[#1e4e57]/25 bg-[#1e4e57]/3 hover:border-[#1e4e57]/50 hover:bg-[#1e4e57]/6'"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    @drop.prevent="onDrop"
                                    @click="fileInputRef?.click()"
                                >
                                    <input
                                        ref="fileInputRef"
                                        type="file"
                                        accept="image/*"
                                        multiple
                                        class="hidden"
                                        @change="onFileChange"
                                    >
                                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#1e4e57]/10 text-[#1e4e57] transition-transform duration-200" :class="isDragging ? 'scale-110' : ''">
                                        <Icon name="ic:sharp-cloud-upload" style="color: black" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">
                                            <span class="text-[#1e4e57] font-bold">Bấm để chọn ảnh</span> hoặc kéo thả vào đây
                                        </p>
                                        <p class="mt-1 text-xs text-slate-400">PNG, JPG, WEBP — Tối đa 10MB mỗi ảnh</p>
                                    </div>
                                    <div v-if="uploadedImages.length > 0" class="flex items-center gap-2 rounded-full bg-[#1e4e57]/10 px-4 py-1.5">
                                        <Icon name="ic:baseline-drive-folder-upload" style="color: black" />
                                        <span class="text-xs font-semibold text-[#1e4e57]">{{ uploadedImages.length }} ảnh đã chọn</span>
                                    </div>
                                </div>

                                <!-- Preview Grid -->
                                <div v-if="uploadedImages.length > 0" class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                                    <div
                                        v-for="(img, index) in uploadedImages"
                                        :key="img.id"
                                        class="group relative overflow-hidden rounded-2xl border-2 transition-all duration-200 cursor-pointer"
                                        :class="thumbnailIndex === index
                                            ? 'border-[#1e4e57] shadow-lg shadow-[#1e4e57]/20'
                                            : 'border-transparent hover:border-[#1e4e57]/40'"
                                        @click="thumbnailIndex = index"
                                    >
                                        <!-- Image -->
                                        <img
                                            :src="img.url"
                                            :alt="'Ảnh ' + (index + 1)"
                                            class="h-36 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        >

                                        <!-- Thumbnail badge -->
                                        <div
                                            v-if="thumbnailIndex === index"
                                            class="absolute top-2 left-2 flex items-center gap-1 rounded-full bg-[#1e4e57] px-2 py-0.5 text-white text-[10px] font-bold shadow"
                                        >
                                            <i class="fa-solid fa-star text-[9px]"></i>
                                            Đại diện
                                        </div>

                                        <!-- Select hint overlay -->
                                        <div
                                            v-else
                                            class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/20 transition-all duration-200"
                                        >
                                            <span class="scale-0 group-hover:scale-100 transition-transform duration-200 text-white text-xs font-semibold bg-black/50 rounded-full px-3 py-1">
                                                Đặt làm đại diện
                                            </span>
                                        </div>

                                        <!-- Remove button -->
                                        <button
                                            type="button"
                                            class="absolute top-2 right-2 flex h-6 w-6 items-center justify-center rounded-full bg-white/90 text-slate-600 shadow opacity-0 group-hover:opacity-100 transition-all duration-200 hover:bg-red-500 hover:text-white"
                                            @click.stop="removeImage(index)"
                                        >
                                            <Icon name="ic:outline-close" style="color: black" />
                                        </button>
                                    </div>
                                </div>

                                <!-- Empty hint -->
                                <p v-if="uploadedImages.length === 0" class="text-center text-xs text-slate-400 pt-1">
                                    Chưa có ảnh nào được chọn
                                </p>
                            </section>

                            <!-- Checklist -->
                            <section class="rounded-3xl border border-slate-100 bg-slate-50/70 p-6">
                                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Kiểm tra trước khi hoàn tất</h3>
                                <ul class="mt-4 space-y-3 text-sm text-slate-600">
                                    <li class="flex items-center gap-3">
                                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full" :class="uploadedImages.length > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-400'">
                                            <i class="fa-solid text-[10px]" :class="uploadedImages.length > 0 ? 'fa-check' : 'fa-minus'"></i>
                                        </span>
                                        <span :class="uploadedImages.length > 0 ? 'text-slate-700' : 'text-slate-400'">Đã tải lên ít nhất 1 ảnh xe</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                        </span>
                                        <span class="text-slate-700">Thông tin xe và giá thuê đã điền đầy đủ</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                        </span>
                                        <span class="text-slate-700">Điều khoản thuê xe đã được thiết lập</span>
                                    </li>
                                </ul>
                            </section>
                        </div>

                        <div class="mt-10 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <button type="button" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-4 text-sm font-bold text-slate-700 transition hover:bg-slate-50 sm:w-auto" :disabled="activeStep === 1" :class="activeStep === 1 ? 'cursor-not-allowed opacity-50' : ''" @click="activeStep = Math.max(1, activeStep - 1)">
                                Quay lại
                            </button>

                            <button v-if="activeStep < 3" type="button" class="inline-flex w-full items-center justify-center rounded-2xl bg-[#1e4e57] px-6 py-4 text-sm font-bold text-white shadow-lg shadow-[#1e4e57]/20 transition hover:bg-[#286874] sm:w-auto" @click="activeStep = Math.min(3, activeStep + 1)">
                                Kế tiếp
                            </button>
                            <button v-else type="button" class="inline-flex w-full items-center justify-center rounded-2xl bg-[#1e4e57] px-6 py-4 text-sm font-bold text-white shadow-lg shadow-[#1e4e57]/20 transition hover:bg-[#286874] sm:w-auto">
                                Hoàn tất đăng ký
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const activeStep = ref(1)

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
const feeVal = ref(10)
const freeLimitVal = ref(0)
const kmLimitEnabled = ref(true)
const kmLimitVal = ref(400)
const overFeeVal = ref(3)

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

const featureItems = ref([
    {
        name: 'Bản đồ',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781056273/map-v2_tcaq37.png'
    },
    {
        name: 'Bluetooth',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073780/bluetooth-v2_m9z2wh.png'
    },
    {
        name: 'Camera 360',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073780/360_camera-v2_nbtyux.png'
    },
    {
        name: 'Camera cập lề',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/parking_camera-v2_gbpz4n.png'
    },
    {
        name: 'Camera hành trình',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/dash_camera-v2_llqrns.png'
    },
    {
        name: 'Camera lùi',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/reverse_camera-v2_yanwh4.png'
    },
    {
        name: 'Cảm biến lốp',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073783/tpms-v2_kgsvg4.png'
    },
    {
        name: 'Cảm biến va chạm',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/impact_sensor-v2_iotcwq.png'
    },
    {
        name: 'Cảnh báo tốc độ',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/head_up-v2_fxa7mn.png'
    },
    {
        name: 'Cửa sổ trời',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/sunroof-v2_kyo2mt.png'
    },
    {
        name: 'Định vị GPS',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/gps-v2_cjy1dg.png'
    },
    {
        name: 'Ghế trẻ em',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/babyseat-v2_d1gmcr.png'
    },
    {
        name: 'Khe cắm USB',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/usb-v2_vvx1vt.png'
    },
    {
        name: 'Lốp dự phòng',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073782/spare_tire-v2_ildbl1.png'
    },
    {
        name: 'Màn hình DVD',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781074389/dvd-v2_1_uxfubm.png'
    },
    {
        name: 'Nắp thùng xe bán tải',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073781/bonnet-v2_zirugz.png'
    },
    {
        name: 'ETC',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781074484/etc-v2_vepgxt.png'
    },
    {
        name: 'Túi khí',
        checked: false,
        icon: 'https://res.cloudinary.com/dfmoftnpw/image/upload/v1781073780/airbags-v2_gtkgpl.png'
    }
])

const toggleFeature = (name: string) => {
    const item = featureItems.value.find((f) => f.name === name)
    if (!item) return
    item.checked = !item.checked
}

// Step 3 - Image upload
interface UploadedImage {
    id: number
    url: string
    file: File
}

const fileInputRef = ref<HTMLInputElement | null>(null)
const uploadedImages = ref<UploadedImage[]>([])
const thumbnailIndex = ref(0)
const isDragging = ref(false)
let imageIdCounter = 0

const addFiles = (files: FileList | File[]) => {
    Array.from(files).forEach((file) => {
        if (!file.type.startsWith('image/')) return
        const url = URL.createObjectURL(file)
        uploadedImages.value.push({ id: imageIdCounter++, url, file })
    })
    if (thumbnailIndex.value >= uploadedImages.value.length) {
        thumbnailIndex.value = 0
    }
}

const onFileChange = (e: Event) => {
    const input = e.target as HTMLInputElement
    if (input.files) addFiles(input.files)
    input.value = ''
}

const onDrop = (e: DragEvent) => {
    isDragging.value = false
    if (e.dataTransfer?.files) addFiles(e.dataTransfer.files)
}

const removeImage = (index: number) => {
    URL.revokeObjectURL(uploadedImages.value[index].url)
    uploadedImages.value.splice(index, 1)
    if (thumbnailIndex.value >= uploadedImages.value.length) {
        thumbnailIndex.value = Math.max(0, uploadedImages.value.length - 1)
    }
}

useHead({
    title: 'Đăng ký xe | DRIVIO'
})

</script>

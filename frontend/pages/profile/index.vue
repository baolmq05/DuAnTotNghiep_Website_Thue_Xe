<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Account Information -->
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm">
      <div
        class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6"
      >
        <h2 class="text-xl md:text-2xl font-semibold">Thông tin tài khoản</h2>

        <div
          class="flex items-center gap-2 px-4 py-2 border rounded-xl text-green-500 w-fit"
        >
          <Icon name="ic:outline-stars" size="22" />
          <span>0 chuyến</span>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Avatar -->
        <div class="lg:col-span-4">
          <div class="flex flex-col items-center">
            <img
              src="https://i.pravatar.cc/200"
              class="w-28 h-28 md:w-32 md:h-32 rounded-full object-cover"
            />

            <h3 class="mt-4 text-xl md:text-2xl font-semibold text-center">
              Quốc Toàn
            </h3>

            <p class="text-sm text-gray-500 mt-1">Tham gia: 13/05/2026</p>

            <div
              class="mt-4 border rounded-xl px-4 py-2 flex items-center gap-2"
            >
              <Icon
                name="ic:outline-emoji-events"
                class="text-yellow-500"
                size="22"
              />
              <span class="font-semibold"> 0 điểm </span>
            </div>
          </div>
        </div>

        <!-- Information -->
        <div class="lg:col-span-8">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
              <p class="text-gray-500 text-sm">Ngày sinh</p>

              <p class="font-medium mt-2">--/--/----</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
              <p class="text-gray-500 text-sm">Giới tính</p>

              <p class="font-medium mt-2">Nam</p>
            </div>
          </div>

          <div class="mt-6 space-y-4">
            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
              <span class="text-gray-500"> Số điện thoại </span>

              <span class="font-medium break-all"> +84338650615 </span>
            </div>
            
            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
              <span class="text-gray-500"> Email </span>

              <span class="font-medium break-all"> example@gmail.com </span>
            </div>

            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
              <span class="text-gray-500"> Facebook </span>

              <span class="font-medium"> Thêm liên kết </span>
            </div>

            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
              <span class="text-gray-500"> Google </span>

              <span class="font-medium"> Quốc Toàn </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Driving License -->
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm">
      <div
        class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
      >
        <div class="flex flex-wrap items-center gap-3">
          <h2 class="text-xl md:text-2xl font-semibold">Giấy phép lái xe</h2>

          <span class="px-3 py-1 rounded-full bg-red-100 text-red-500 text-sm">
            Chưa xác thực
          </span>
        </div>

        <button
          class="px-5 py-2 border rounded-xl flex items-center gap-2 w-fit"
        >
          Chỉnh sửa

          <Icon name="ic:outline-edit" />
        </button>
      </div>

      <div class="mt-5 p-3 rounded-lg bg-green-50 text-green-700 text-sm">
        Hình chụp cần thấy được ảnh chân dung và số GPLX.
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6">
        <div>
          <h3 class="font-semibold mb-4">Ảnh mặt trước GPLX</h3>

          <div
            class="h-[220px] md:h-[280px] border-2 border-dashed rounded-xl flex items-center justify-center"
          >
            <Icon
              name="ic:outline-cloud-upload"
              size="40"
              class="text-green-500"
            />
          </div>
        </div>

        <div>
          <h3 class="font-semibold mb-4">Thông tin chung</h3>

          <div class="space-y-4">
            <input
              placeholder="Nhập số GPLX"
              class="w-full bg-gray-50 rounded-xl p-3 outline-none border"
            />

            <input
              placeholder="Họ và tên"
              class="w-full bg-gray-50 rounded-xl p-3 outline-none border"
            />

            <input
              value="01/01/1970"
              class="w-full bg-gray-50 rounded-xl p-3 outline-none border"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Referral -->
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
        <div>
          <h2 class="text-xl md:text-2xl font-semibold">Giới thiệu bạn mới</h2>

          <p class="text-gray-500 mt-2">Tìm hiểu chi tiết chương trình</p>
        </div>

        <img
          src="https://placehold.co/600x250"
          class="rounded-xl w-full h-[220px] object-cover"
        />
      </div>
    </div>

    <!-- Payment -->
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm">
      <div
        class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center"
      >
        <h2 class="text-xl md:text-2xl font-semibold">Thẻ thanh toán</h2>

        <button class="border rounded-xl px-4 py-2 w-fit">Thêm thẻ</button>
      </div>

      <div
        class="h-[220px] md:h-[300px] flex flex-col justify-center items-center text-gray-500"
      >
        <Icon name="ic:outline-credit-card" size="80" />

        <p class="mt-4 text-center">Bạn chưa có thẻ nào</p>
      </div>
    </div>

    <!-- Car List -->
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm">
      <div
        class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center"
      >
        <h2 class="text-xl md:text-2xl font-semibold">Danh sách xe</h2>

        <div class="flex gap-4 overflow-x-auto">
          <button class="text-gray-400 whitespace-nowrap">Có tài xế</button>

          <button
            class="text-green-500 border-b-2 border-green-500 whitespace-nowrap"
          >
            Tự lái
          </button>
        </div>
      </div>

      <div
        class="h-[220px] md:h-[300px] flex flex-col justify-center items-center text-gray-500"
      >
        <Icon name="ic:outline-directions-car" size="80" />

        <p class="mt-4 text-center">Không tìm thấy xe nào.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'profile',
})
</script>

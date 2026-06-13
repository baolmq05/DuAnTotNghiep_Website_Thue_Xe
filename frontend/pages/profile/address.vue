<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 py-6">
    <div
      class="relative h-[200px] bg-[#AE7C54] rounded-2xl flex items-center justify-center text-white"
    >
      <h1 class="text-3xl font-bold">Quản lý địa chỉ</h1>
    </div>
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl md:text-2xl font-semibold">Địa chỉ đã lưu</h2>
        <button
          v-if="!showAddForm"
          @click="showAddForm = true"
          class="flex items-center gap-1 px-4 py-2 bg-blue-50 text-blue-600 font-medium rounded-xl border border-blue-200 hover:bg-blue-100 hover:border-blue-300 transition"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="currentColor"
            class="w-5 h-5"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 4.5v15m7.5-7.5h-15"
            />
          </svg>
          Thêm mới
        </button>
      </div>

      <div class="space-y-4">
        <div
          v-for="(address, index) in savedAddresses"
          :key="index"
          class="flex justify-between items-center bg-gray-50 rounded-xl p-4 border"
        >
          <div>
            <p class="font-medium text-gray-800">{{ address.name }}</p>
            <p class="text-sm text-gray-500">
              {{ address.city }}, {{ address.district }}, {{ address.ward }}
            </p>
            <p class="text-sm text-gray-500">{{ address.detail }}</p>
          </div>
          <button class="text-red-500 hover:text-red-700 transition">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <div
      v-show="showAddForm"
      class="bg-white rounded-2xl p-4 md:p-6 shadow-sm transition-all duration-300"
    >
      <h2 class="text-xl md:text-2xl font-semibold mb-6">Thêm địa chỉ mới</h2>

      <form class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-2">
            Loại địa điểm
          </label>

          <div class="flex flex-wrap gap-3">
            <button
              v-for="type in addressTypes"
              :key="type.value"
              type="button"
              :class="[
                'flex items-center gap-2 px-4 py-2 rounded-xl border transition-all duration-200',
                newAddress.type === type.value
                  ? 'bg-green-50 border-green-500 text-green-600 shadow-sm'
                  : 'bg-white border-gray-200 text-gray-600 hover:border-green-300 hover:bg-green-50',
              ]"
            >
              <div
                v-html="type.icon"
                class="w-5 h-5 flex items-center justify-center"
              ></div>
              <span class="text-sm font-medium">
                {{ type.label }}
              </span>
            </button>
          </div>
        </div>

        <div>
          <label for="city" class="block text-sm text-gray-500 mb-1"
            >Tỉnh/Thành phố</label
          >
          <select
            id="city"
            v-model="newAddress.city"
            class="w-full bg-gray-50 rounded-xl p-3 outline-none border focus:border-blue-500 transition"
          >
            <option value="" disabled>Chọn tỉnh/thành phố</option>
            <option v-for="city in cities" :key="city" :value="city">
              {{ city }}
            </option>
          </select>
        </div>

        <div>
          <label for="district" class="block text-sm text-gray-500 mb-1"
            >Quận/Huyện</label
          >
          <input
            id="district"
            v-model="newAddress.district"
            type="text"
            placeholder="Nhập quận/huyện"
            class="w-full bg-gray-50 rounded-xl p-3 outline-none border focus:border-blue-500 transition"
          />
        </div>

        <div>
          <label for="ward" class="block text-sm text-gray-500 mb-1"
            >Phường/Xã</label
          >
          <input
            id="ward"
            v-model="newAddress.ward"
            type="text"
            placeholder="Nhập phường/xã"
            class="w-full bg-gray-50 rounded-xl p-3 outline-none border focus:border-blue-500 transition"
          />
        </div>

        <div>
          <label for="detail" class="block text-sm text-gray-500 mb-1"
            >Địa chỉ cụ thể</label
          >
          <textarea
            id="detail"
            v-model="newAddress.detail"
            placeholder="Nhập địa chỉ cụ thể"
            class="w-full bg-gray-50 rounded-xl p-3 outline-none border focus:border-blue-500 transition min-h-[100px]"
          ></textarea>
        </div>

        <div class="flex gap-3 pt-2">
          <button
            type="submit"
            class="flex-1 md:flex-none px-6 py-3 bg-green-500 text-white font-medium rounded-xl hover:bg-green-600 transition"
          >
            Lưu địa chỉ
          </button>
          <button
            type="button"
            @click="showAddForm = false"
            class="flex-1 md:flex-none px-6 py-3 bg-gray-100 text-gray-600 font-medium rounded-xl hover:bg-gray-200 transition"
          >
            Hủy
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";

definePageMeta({
  layout: "profile",
});

const showAddForm = ref(false);

const cities = ref([
  "Hà Nội",
  "TP. Hồ Chí Minh",
  "Đà Nẵng",
  "Hải Phòng",
  "Cần Thơ",
  "Huế",
  "Nha Trang",
  "Vũng Tàu",
]);

const addressTypes = ref([
  {
    value: "Nhà riêng",
    label: "Nhà riêng",
    icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>',
  },
  {
    value: "Văn phòng",
    label: "Văn phòng",
    icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8M8 12h8m-8-4h8m-6 8v6m4-6v6M3 20h18a2 2 0 002-2V5a2 2 0 00-2-2H3a2 2 0 00-2 2v13a2 2 0 002 2z" /></svg>',
  },
  {
    value: "Khác",
    label: "Khác",
    icon: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20z" /></svg>',
  },
]);

const savedAddresses = ref([
  {
    name: "Nhà riêng",
    city: "Hà Nội",
    district: "Quận Ba Đình",
    ward: "Phường Điện Biên",
    detail: "123 Đường ABC",
  },
  {
    name: "Văn phòng",
    city: "TP. Hồ Chí Minh",
    district: "Quận 1",
    ward: "Phường Bến Nghé",
    detail: "456 Đường DEF",
  },
]);

const newAddress = ref({
  type: "",
  city: "",
  district: "",
  ward: "",
  detail: "",
});
</script>

<style scoped>
html {
  scroll-behavior: smooth;
}

input,
textarea,
select {
  @apply text-gray-700;
}
</style>

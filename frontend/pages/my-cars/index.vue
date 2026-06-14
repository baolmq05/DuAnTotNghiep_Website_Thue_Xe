<template>
  <div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between gap-4">
      <div class="flex gap-3">
        <button
          class="px-6 py-3 rounded-xl border border-brand-primary bg-brand-primary/10 text-brand-primary flex items-center gap-2 font-medium"
        >
          <Icon name="ic:outline-directions-car" size="20" />
          Xe tự lái
        </button>
      </div>

      <div class="relative w-full md:w-[220px]">
        <select
          v-model="selectedStatus"
          class="w-full appearance-none rounded-xl border border-gray-200 bg-white px-4 py-3 pr-10 outline-none focus:border-brand-primary text-gray-700 cursor-pointer"
        >
          <option value="all">Tất cả</option>
          <option value="1">Đang hoạt động</option>
          <option value="2">Chờ duyệt</option>
          <option value="3">Bị từ chối</option>
          <option value="0">Dừng hoạt động</option>
        </select>

        <Icon
          name="ic:round-keyboard-arrow-down"
          class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
          size="22"
        />
      </div>
    </div>

    <div v-if="filteredCars.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="car in filteredCars" 
        :key="car.licensePlate"
        class="group bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full transform translate-z-0"
      >
        <div class="relative overflow-hidden aspect-[16/10.5] shrink-0">
          <img :src="car.image" :alt="car.name"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />

          <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent"></div>

          <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
            <span
              class="bg-slate-900/60 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm uppercase tracking-wider flex items-center gap-1"
            >
              <Icon name="lucide:key-round" class="w-3 h-3" /> {{ car.rentalType }}
            </span>
          </div>

          <div class="absolute top-3 right-3 z-10">
            <span class="bg-slate-900/70 backdrop-blur-sm text-white text-xs font-mono font-bold px-2.5 py-1 rounded-lg border border-white/20 shadow-sm tracking-wide">
              {{ car.licensePlate }}
            </span>
          </div>

          <div
            class="absolute bottom-2.5 right-2.5 z-10 flex items-center gap-1 bg-slate-900/40 backdrop-blur-md rounded-full px-2.5 py-0.5 border border-white/10 text-white"
          >
            <Icon name="lucide:calendar" class="w-3 h-3 text-brand-light" />
            <span class="text-[11px] font-medium">Đời {{ car.manufactureYear }}</span>
          </div>
        </div>

        <div class="p-3.5 flex flex-col flex-grow justify-between">
          <div class="flex flex-col flex-grow justify-start">
            
            <div class="flex flex-wrap gap-1.5 mb-1.5 min-h-[20px]">
              <span v-if="car.status === 1"
                class="inline-flex items-center text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded uppercase"
              >
                ● Đang hoạt động
              </span>
              <span v-else-if="car.status === 2"
                class="inline-flex items-center text-[10px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded uppercase"
              >
                ● Chờ phê duyệt
              </span>
              <span v-else-if="car.status === 3"
                class="inline-flex items-center text-[10px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded uppercase"
              >
                ● Bị từ chối
              </span>
              <span v-else-if="car.status === 0"
                class="inline-flex items-center text-[10px] font-bold text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded uppercase"
              >
                ● Dừng hoạt động
              </span>
            </div>

            <div class="flex justify-between items-start gap-2">
              <div class="flex-grow min-w-0">
                <h3
                  class="font-bold text-base text-slate-800 line-clamp-1 group-hover:text-[#286874] transition-colors"
                >
                  {{ car.name }}
                </h3>
                <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                  <Icon name="lucide:map-pin" class="shrink-0" /> <span class="truncate">{{ car.location }}</span>
                </p>
              </div>

              <div
                class="flex items-center gap-0.5 bg-slate-100 text-slate-600 px-2 py-0.5 rounded-lg text-xs font-medium shrink-0 mt-0.5"
              >
                <Icon name="lucide:droplet" class="w-3 h-3 text-brand-primary" /> {{ car.fuelConsumption }}L/100km
              </div>
            </div>
          </div>

          <div class="mt-2">
            <div class="grid grid-cols-3 gap-1 bg-slate-50 p-2 rounded-xl text-center">
              <div class="flex flex-col items-center justify-center py-0.5 border-r border-slate-200/60">
                <span class="text-xs font-semibold text-slate-700">{{ car.seats }} chỗ</span>
                <span class="text-[10px] text-slate-400 mt-0.5">Sức chứa</span>
              </div>
              <div class="flex flex-col items-center justify-center py-0.5 border-r border-slate-200/60 min-w-0">
                <span class="text-xs font-semibold text-slate-700 truncate w-full px-1">{{ car.transmission }}</span>
                <span class="text-[10px] text-slate-400 mt-0.5">Hộp số</span>
              </div>
              <div class="flex flex-col items-center justify-center py-0.5 min-w-0">
                <span class="text-xs font-semibold text-slate-700 truncate w-full px-1">{{ car.fuel }}</span>
                <span class="text-[10px] text-slate-400 mt-0.5">Nhiên liệu</span>
              </div>
            </div>

            <div class="flex justify-between items-end mt-3 pt-3 border-t border-slate-100">
              <div>
                <p class="text-[10px] text-slate-400 font-medium">Doanh thu tháng này</p>
                <p class="font-bold text-slate-700 mt-0.5 text-sm">
                  {{ formatPrice(car.revenue) }}
                </p>
              </div>

              <div class="text-right">
                <p class="text-[10px] text-slate-400 font-medium">Tỉ lệ thuê tháng</p>
                <p class="font-extrabold text-base text-[#286874] mt-0.5">
                  {{ car.activeDays }}/30 <span class="text-[11px] font-normal text-slate-400">ngày</span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="min-h-[500px] flex flex-col items-center justify-center">
      <div class="w-64 h-64 rounded-full bg-brand-secondary flex items-center justify-center">
        <Icon name="ic:outline-no-crash" size="120" class="text-brand-primary" />
      </div>

      <h2 class="mt-8 text-2xl font-semibold text-brand-dark">Không tìm thấy xe nào</h2>
      <p class="text-gray-500 mt-2">Không có phương tiện nào thuộc bộ lọc trạng thái này.</p>

      <button 
        @click="selectedStatus = 'all'" 
        class="mt-6 px-6 py-3 rounded-xl bg-brand-primary text-white hover:opacity-90 transition shadow-sm"
      >
        Xem tất cả xe
      </button>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';

definePageMeta({
  layout: "my-cars",
});

// State lưu trạng thái bộ lọc đang được chọn (Mặc định hiển thị "Tất cả")
const selectedStatus = ref<string>('all');

// 1. Dữ liệu thô - Bổ sung trường "status" khớp 100% với kiểu số nguyên từ Migration mới của Sếp
const rawCarsFromSeeder = [
  {
    name: 'Toyota Camry',
    license_plate: '30A-12345',
    fuel_consumption: 8.5,
    unit_price: 1000000,
    discount_value: 100000,
    description: 'Xe sang trọng, tiện nghi, phù hợp cho các chuyến đi dài ngày.',
    rental_terms: 'Không hút thuốc, không chở vật nuôi, trả xe đúng giờ.',
    car_location_id: 1,
    car_brand_id: 1,
    car_type_id: 1,
    seat_count: 5,
    manufacture_year: '2020-01-01',
    fuel_type: 'Xăng',
    transmission: 'Tự động',
    user_id: 1,
    delivery_option_id: 1,
    usage_limit_id: 1,
    status: 1 // 1: Đang hoạt động
  },
  {
    name: 'Honda Civic',
    license_plate: '30B-54321',
    fuel_consumption: 7.2,
    unit_price: 900000,
    discount_value: 50000,
    description: 'Xe thể thao, năng động, phù hợp cho các chuyến đi trong thành phố.',
    rental_terms: 'Không hút thuốc, không chở vật nuôi, trả xe đúng giờ.',
    car_location_id: 1,
    car_brand_id: 2,
    car_type_id: 1,
    seat_count: 5,
    manufacture_year: '2019-01-01',
    fuel_type: 'Xăng',
    transmission: 'Tự động',
    user_id: 1,
    delivery_option_id: 1,
    usage_limit_id: 1,
    status: 2 // 2: Chờ duyệt (Trạng thái mặc định hệ thống)
  },
  {
    name: 'Toyota Wigo',
    license_plate: '30C-67890',
    fuel_consumption: 6.0,
    unit_price: 700000,
    discount_value: 30000,
    description: 'Xe nhỏ gọn, tiết kiệm nhiên liệu, phù hợp cho các chuyến đi trong thành phố.',
    rental_terms: 'Không hút thuốc, không chở vật nuôi, trả xe đúng giờ.',
    car_location_id: 1,
    car_brand_id: 1,
    car_type_id: 2,
    seat_count: 5,
    manufacture_year: '2021-01-01',
    fuel_type: 'Xăng',
    transmission: 'Tự động',
    user_id: 1,
    delivery_option_id: 1,
    usage_limit_id: 1,
    status: 0 // 0: Dừng hoạt động
  },
  {
    name: 'Toyota Corolla Cross',
    license_plate: '30D-98765',
    fuel_consumption: 7.8,
    unit_price: 800000,
    discount_value: 40000,
    description: 'Xe SUV, rộng rãi, phù hợp cho các chuyến đi gia đình hoặc nhóm bạn.',
    rental_terms: 'Không hút thuốc, không chở vật nuôi, trả xe đúng giờ.',
    car_location_id: 1,
    car_brand_id: 1,
    car_type_id: 3,
    seat_count: 5,
    manufacture_year: '2022-01-01',
    fuel_type: 'Xăng',
    transmission: 'Tự động',
    user_id: 1,
    delivery_option_id: 1,
    usage_limit_id: 1,
    status: 3 // 3: Bị từ chối
  }
];

// 2. Định dạng dữ liệu và giả lập báo cáo kinh doanh cho Chủ xe
const formattedCars = computed(() => {
  const sampleImages = [
    'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=600',
    'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=600',
    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=600',
    'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600'
  ];

  return rawCarsFromSeeder.map((car, index) => {
    // Đặt số ngày thuê giả lập theo trạng thái thực tế
    let simulatedActiveDays = 0;
    if (car.status === 1) simulatedActiveDays = 18 + index; // Xe đang chạy mới phát sinh doanh thu
    if (car.status === 2) simulatedActiveDays = 0; // Xe chờ duyệt chưa thể cho thuê

    const computedRevenue = (car.unit_price - car.discount_value) * simulatedActiveDays;

    return {
      name: car.name,
      licensePlate: car.license_plate,
      image: sampleImages[index] || sampleImages[0],
      location: 'Quận 1, TP. Hồ Chí Minh',
      seats: Number(car.seat_count),
      transmission: car.transmission,
      fuel: car.fuel_type,
      fuelConsumption: car.fuel_consumption,
      manufactureYear: car.manufacture_year.split('-')[0],
      status: car.status, // Giữ nguyên giá trị số (0, 1, 2, 3) để hiển thị và lọc
      rentalType: 'Xe tự lái',
      revenue: computedRevenue,
      activeDays: simulatedActiveDays
    };
  });
});

// 3. Xử lý logic lọc xe thời gian thực dựa vào giá trị v-model của <select>
const filteredCars = computed(() => {
  if (selectedStatus.value === 'all') {
    return formattedCars.value;
  }
  // Chuyển đổi value từ string của thẻ select về kiểu number để so sánh chuẩn xác với car.status
  return formattedCars.value.filter(car => car.status === parseInt(selectedStatus.value));
});

const formatPrice = (val: number) => {
  return val.toLocaleString('vi-VN') + 'đ';
};
</script>
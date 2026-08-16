<template>
  <section v-if="user" class="py-10 bg-slate-50/60 border-y border-slate-100/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
              Xe bạn đã xem gần đây
            </h2>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div v-for="n in 4" :key="n" class="border border-slate-100 rounded-2xl overflow-hidden shadow-sm bg-white animate-pulse">
          <div class="h-48 bg-slate-200 w-full"></div>
          <div class="p-4 space-y-3">
            <div class="h-4 bg-slate-200 rounded w-2/3"></div>
            <div class="h-3 bg-slate-200 rounded w-1/2"></div>
            <div class="flex gap-2">
              <div class="h-3 bg-slate-200 rounded w-1/4"></div>
              <div class="h-3 bg-slate-200 rounded w-1/4"></div>
              <div class="h-3 bg-slate-200 rounded w-1/4"></div>
            </div>
            <div class="border-t border-slate-100 pt-3 flex justify-between items-center">
              <div class="h-5 bg-slate-200 rounded w-1/3"></div>
              <div class="h-8 bg-slate-200 rounded-lg w-1/4"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Car Grid (Top 4 viewed cars) -->
      <div v-else-if="viewedCars.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div 
          v-for="item in viewedCars" 
          :key="item.id"
          class="block h-full cursor-pointer"
          @click="goToDetail(item.car.id)"
        >
          <VehicleCard 
            :name="item.car.name" 
            :image="item.car.image" 
            :price="item.car.price"
            :location="item.car.location" 
            :seats="item.car.seats" 
            :transmission="item.car.transmission"
            :fuel="item.car.fuel" 
            :rating="item.car.rating" 
            :trips="item.car.trips"
            :is-instant-book="item.car.isInstantBook" 
            :is-delivery="item.car.isDelivery"
            :no-deposit="item.car.noDeposit" 
            :discount="item.car.discount"
            :isFavorite="isCarFavorited(item.car.id)"
            :ownerAvatar="item.car.ownerAvatar"
            :ownerName="item.car.ownerName"
            @toggle-favorite="handleToggleFavorite(item.car.id)" 
          />
        </div>
      </div>

      <!-- Empty State khi chưa xem xe nào -->
      <div v-else class="bg-white rounded-2xl p-8 border border-slate-100 text-center shadow-sm max-w-md mx-auto my-2">
        <!-- <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3">
          <Icon name="lucide:eye-off" class="w-6 h-6" />
        </div> -->
        <h3 class="text-base font-bold text-slate-800">Chưa có lịch sử xem xe</h3>
        <NuxtLink 
          to="/vehicle-list" 
          class="inline-flex items-center gap-2 px-4 py-2 bg-brand-primary text-white text-xs font-bold rounded-xl hover:opacity-90 transition-opacity"
        >
          <Icon name="lucide:search" class="w-4 h-4" />
          <span>Tìm xe ngay</span>
        </NuxtLink>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { useRouter } from '#app';
import VehicleCard from '~/components/Vehicle/VehicleCard.vue';
import { viewHistoryService } from '~/services/view-history.service';
import { favoriteService } from '~/services/favorite.service';

const router = useRouter();
const { user } = useAuth();
const { showToast } = useToast();

const rawHistory = ref<any[]>([]);
const favoriteCarIds = ref<number[]>([]);
const isLoading = ref(false);

// Helper hàm chuẩn hóa thông số
const normalizeTransmission = (trans: string) => {
  if (!trans) return 'Số tự động';
  const lower = trans.toLowerCase();
  if (lower.includes('tự động') || lower.includes('auto') || lower.includes('at')) return 'Số tự động';
  if (lower.includes('sàn') || lower.includes('manual') || lower.includes('mt')) return 'Số sàn';
  return trans;
};

const normalizeFuel = (fuel: string) => {
  if (!fuel) return 'Xăng';
  const lower = fuel.toLowerCase();
  if (lower.includes('xăng') || lower.includes('gasoline') || lower.includes('petrol')) return 'Xăng';
  if (lower.includes('dầu') || lower.includes('diesel')) return 'Dầu';
  if (lower.includes('điện') || lower.includes('electric') || lower.includes('ev')) return 'Điện';
  return fuel;
};

const goToDetail = (carId: number) => {
  router.push(`/vehicles/${carId}`);
};

const fetchViewHistory = async () => {
  if (!user.value) {
    rawHistory.value = [];
    return;
  }

  try {
    isLoading.value = true;
    const response = await viewHistoryService.getViewHistory({ limit: 4 });
    if (response.success && response.data) {
      const list = Array.isArray(response.data) ? response.data : (response.data.data || []);
      rawHistory.value = list;
    }
  } catch (error) {
    console.error('Lỗi khi tải lịch sử xem xe:', error);
  } finally {
    isLoading.value = false;
  }
};

const fetchFavoriteCarIds = async () => {
  if (!user.value) return;
  try {
    const res = await favoriteService.getFavorites();
    if (res.success && res.data) {
      favoriteCarIds.value = res.data.map((fav: any) => fav.car_id);
    }
  } catch (error) {
    console.error('Lỗi lấy danh sách yêu thích:', error);
  }
};

const isCarFavorited = (carId: number) => {
  return favoriteCarIds.value.includes(carId);
};

const handleToggleFavorite = async (carId: number) => {
  if (!user.value) {
    showToast('Vui lòng đăng nhập để lưu xe yêu thích!', 'info');
    return;
  }

  try {
    if (isCarFavorited(carId)) {
      const res = await favoriteService.removeFavorite(carId);
      if (res.success) {
        favoriteCarIds.value = favoriteCarIds.value.filter(id => id !== carId);
        showToast('Đã xóa khỏi danh sách yêu thích!', 'success');
      }
    } else {
      const res = await favoriteService.addFavorite(carId);
      if (res.success) {
        favoriteCarIds.value.push(carId);
        showToast('Đã thêm vào danh sách yêu thích!', 'success');
      }
    }
  } catch (error) {
    console.error('Lỗi thay đổi trạng thái yêu thích:', error);
    showToast('Đã có lỗi xảy ra!', 'error');
  }
};

// Compute danh sách 4 xe hiển thị chuẩn hóa dữ liệu cho VehicleCard
const viewedCars = computed(() => {
  return rawHistory.value
    .filter((item: any) => item.car != null)
    .slice(0, 4)
    .map((item: any) => {
      const car = item.car;
      const thumbnailImg = car.images?.find((img: any) => img.is_thumbnail === 1)?.image_url
        || car.images?.[0]?.image_url
        || 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=600';

      const discountPct = car.unit_price > 0 && car.discount_value > 0
        ? Math.round((car.discount_value / car.unit_price) * 100)
        : 0;

      return {
        id: item.id,
        car: {
          id: car.id,
          name: car.name,
          image: thumbnailImg,
          price: car.unit_price,
          location: car.car_location?.address || 'Chưa cập nhật',
          seats: Number(car.seat_count),
          transmission: normalizeTransmission(car.transmission),
          fuel: normalizeFuel(car.fuel_type),
          rating: car.reviews_avg_rating ? parseFloat(car.reviews_avg_rating) : 0.0,
          trips: car.trips_count || 0,
          isInstantBook: true,
          isDelivery: Boolean(car.delivery_option_id),
          noDeposit: false,
          discount: discountPct,
          ownerAvatar: car.owner?.avatar || '',
          ownerName: car.owner?.name || 'Chủ xe'
        }
      };
    });
});

onMounted(() => {
  if (user.value) {
    fetchViewHistory();
    fetchFavoriteCarIds();
  }
});

watch(user, (newUser) => {
  if (newUser) {
    fetchViewHistory();
    fetchFavoriteCarIds();
  } else {
    rawHistory.value = [];
  }
});
</script>

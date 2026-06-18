<template>
  <div class="space-y-6 min-h-screen">
    <!-- Page Header -->
    <div class="flex flex-col gap-1">
      <h1 class="text-2xl md:text-3xl font-black text-slate-900">Danh sách xe yêu thích</h1>
      <p class="text-sm text-slate-500">Lưu lại những chiếc xe bạn yêu thích để dễ dàng tìm kiếm và đặt xe bất cứ lúc nào.</p>
    </div>

    <!-- Search and Count Bar -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
      <div class="relative flex-1 max-w-md">
        <Icon name="heroicons:magnifying-glass" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg" />
        <input 
          v-model="searchQuery" 
          type="text" 
          placeholder="Tìm xe trong danh sách yêu thích..."
          class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-700 outline-none transition focus:border-[#286874] focus:bg-white focus:ring-4 focus:ring-[#286874]/10" 
        />
      </div>
      <div class="text-sm font-semibold text-slate-600">
        Đang hiển thị {{ filteredFavorites.length }} / {{ favorites.length }} xe
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
      <div v-for="i in 3" :key="i" class="animate-pulse bg-white rounded-2xl border border-slate-100 p-4 h-[380px] flex flex-col justify-between">
        <div class="bg-slate-200 rounded-xl h-[180px] w-full mb-4"></div>
        <div class="space-y-3 flex-1">
          <div class="h-4 bg-slate-200 rounded w-2/3"></div>
          <div class="h-3 bg-slate-200 rounded w-1/2"></div>
          <div class="h-10 bg-slate-200 rounded-xl w-full mt-4"></div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="favorites.length === 0" class="flex flex-col items-center justify-center text-center py-16 px-4 rounded-2xl border border-dashed border-slate-300 bg-white shadow-sm animate-fade-in">
      <div class="w-16 h-16 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 mb-4 shadow-inner">
        <Icon name="heroicons:heart" class="w-8 h-8 animate-pulse" />
      </div>
      <h3 class="text-lg font-bold text-slate-800 mb-1">Danh sách yêu thích trống</h3>
      <p class="text-sm text-slate-500 max-w-sm mb-6">Bạn chưa nhấn yêu thích chiếc xe nào. Hãy khám phá và chọn chiếc xe ưng ý nhất nhé!</p>
      <NuxtLink 
        to="/vehicle-list" 
        class="inline-flex items-center gap-2 px-6 py-3 bg-[#286874] text-white font-semibold rounded-xl hover:bg-[#1e4e57] transition shadow-md shadow-[#286874]/20 hover:shadow-lg active:scale-95"
      >
        Khám phá xe ngay
        <Icon name="heroicons:arrow-right" class="w-4 h-4" />
      </NuxtLink>
    </div>

    <!-- Favorites Grid -->
    <div v-else-if="filteredFavorites.length > 0" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 animate-fade-in">
      <div v-for="fav in filteredFavorites" :key="fav.id" class="cursor-pointer" @click="goToDetail(fav.car_id)">
        <VehicleCard
          :name="fav.car?.name || fav.car_name"
          :image="getThumbnail(fav.car)"
          :price="fav.car?.unit_price || 0"
          :location="fav.car?.car_location?.street_name || 'Chưa cập nhật'"
          :seats="Number(fav.car?.seat_count || 4)"
          :transmission="normalizeTransmission(fav.car?.transmission || '')"
          :fuel="normalizeFuel(fav.car?.fuel_type || '')"
          :rating="fav.car?.reviews_avg_rating ? parseFloat(fav.car.reviews_avg_rating as string) : 5.0"
          :trips="fav.car?.trips_count || 0"
          :discount="getDiscount(fav.car)"
          :isFavorite="true"
          :ownerName="fav.car?.owner?.name || 'Chủ xe'"
          :ownerAvatar="fav.car?.owner?.avatar || ''"
          :isDelivery="fav.car?.delivery_option_id ? true : false"
          @toggle-favorite="handleRemoveFavorite(fav.car_id)"
        />
      </div>
    </div>

    <!-- No Search Results -->
    <div v-else class="text-center py-12 bg-white rounded-2xl border border-slate-200">
      <Icon name="heroicons:magnifying-glass" class="w-12 h-12 text-slate-300 mb-2" />
      <p class="text-slate-500 font-medium">Không tìm thấy xe phù hợp với từ khóa "{{ searchQuery }}"</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { favoriteService } from '~/services/favorite.service'
import type { FavoriteItem } from '~/services/favorite.service'
import VehicleCard from '~/components/Vehicle/VehicleCard.vue'

definePageMeta({
  layout: 'profile'
})

const { user } = useAuth()
const { showToast } = useToast()

const favorites = ref<FavoriteItem[]>([])
const loading = ref(true)
const searchQuery = ref('')

const fetchFavorites = async () => {
  loading.value = true
  try {
    const res = await favoriteService.getFavorites()
    if (res.success && res.data) {
      favorites.value = res.data
    }
  } catch (error) {
    console.error('Lỗi khi lấy danh sách yêu thích:', error)
    showToast('Lỗi khi tải danh sách yêu thích', 'error')
  } finally {
    loading.value = false
  }
}

const handleRemoveFavorite = async (carId: number) => {
  try {
    const res = await favoriteService.removeFavorite(carId)
    if (res.success) {
      favorites.value = favorites.value.filter(fav => fav.car_id !== carId)
      showToast('Đã xóa khỏi danh sách yêu thích!', 'success')
    } else {
      showToast(res.message || 'Không thể xóa khỏi danh sách yêu thích', 'error')
    }
  } catch (error) {
    console.error('Lỗi khi xóa khỏi danh sách yêu thích:', error)
    showToast('Lỗi khi xóa khỏi danh sách yêu thích', 'error')
  }
}

const filteredFavorites = computed(() => {
  if (!searchQuery.value) return favorites.value
  const query = searchQuery.value.toLowerCase()
  return favorites.value.filter(fav => {
    const name = (fav.car?.name || fav.car_name).toLowerCase()
    return name.includes(query)
  })
})

const getThumbnail = (car: any) => {
  if (!car || !car.images || car.images.length === 0) {
    return 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=600'
  }
  const thumb = car.images.find((img: any) => img.is_thumbnail === 1)
  return thumb ? thumb.image_url : car.images[0].image_url
}

const getDiscount = (car: any) => {
  if (!car || !car.unit_price || !car.discount_value) return 0
  return Math.round((car.discount_value / car.unit_price) * 100)
}

const normalizeTransmission = (trans: string) => {
  if (!trans) return 'Số tự động'
  const lower = trans.toLowerCase()
  if (lower.includes('tự động') || lower.includes('auto') || lower.includes('at')) {
    return 'Số tự động'
  }
  if (lower.includes('sàn') || lower.includes('manual') || lower.includes('mt')) {
    return 'Số sàn'
  }
  return trans
}

const normalizeFuel = (fuel: string) => {
  if (!fuel) return 'Xăng'
  const lower = fuel.toLowerCase()
  if (lower.includes('xăng') || lower.includes('gasoline') || lower.includes('petrol')) {
    return 'Xăng'
  }
  if (lower.includes('dầu') || lower.includes('diesel')) {
    return 'Dầu'
  }
  if (lower.includes('điện') || lower.includes('electric') || lower.includes('ev')) {
    return 'Điện'
  }
  return fuel
}

const router = useRouter()
const goToDetail = (carId: number) => {
  router.push(`/vehicles/${carId}`)
}

onMounted(() => {
  if (!user.value) {
    navigateTo('/')
    return
  }
  fetchFavorites()
})
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

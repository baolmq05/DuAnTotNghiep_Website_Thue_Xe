<template>
    <div class="min-h-screen bg-slate-50">
        <CommonLoadingOverlay :loading="isLoading" text="Đang tải danh sách xe..." />
        <section class="bg-gradient-to-r from-[#1e4e57] to-[#286874] text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16 md:pt-28 md:pb-20 text-center">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight">
                    Danh sách xe cho thuê
                </h1>
                <p class="mt-4 text-cyan-50/90">
                    Tìm chiếc xe phù hợp với hành trình của bạn bằng bộ lọc nhanh và chính xác
                </p>
            </div>
        </section>

        <section class="-mt-8 md:-mt-10 pb-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="rounded-2xl shadow-lg shadow-[#286874]/10 bg-white p-4 md:p-5">
                    <SearchBar />
                </div>
            </div>
        </section>

        <section class="py-6 pb-12 md:pb-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-6">
                    <div class="w-full">
                        <FilterBar :key="filterBarKey" @filterChange="handleFilterChange" />
                    </div>

                    <div class="w-full">
                        <!-- Loading Skeletons -->
                        <div v-if="isLoading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            <div v-for="n in 8" :key="n" class="border border-slate-100 rounded-2xl overflow-hidden shadow-sm bg-white animate-pulse">
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

                        <!-- Car List Grid -->
                        <div v-else-if="carList.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            <div v-for="car in paginatedCarList" :key="car.id" class="block h-full cursor-pointer"
                                @click="goToDetail(car.id)">
                                <vehicleCard :name="car.name" :image="car.image" :price="car.price"
                                    :location="car.location" :seats="car.seats" :transmission="car.transmission"
                                    :fuel="car.fuel" :rating="car.rating" :trips="car.trips"
                                    :is-instant-book="car.isInstantBook" :is-delivery="car.isDelivery"
                                    :no-deposit="car.noDeposit" :discount="car.discount"
                                    :isFavorite="isCarFavorited(car.id)"
                                    :ownerAvatar="car.ownerAvatar"
                                    @toggle-favorite="handleToggleFavorite(car.id)" />
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="flex flex-col items-center justify-center py-16 px-4 bg-white rounded-3xl border border-slate-100 shadow-sm text-center max-w-xl mx-auto my-4 transition-all duration-300">
                            <div class="p-4 bg-rose-50 rounded-full text-rose-500 mb-4 animate-bounce">
                                <Icon name="lucide:search-x" class="w-12 h-12" />
                            </div>
                            <h3 class="text-xl font-extrabold text-slate-800 mb-2">Không tìm thấy xe phù hợp</h3>
                            <p class="text-sm text-slate-500 mb-6 leading-relaxed font-medium">
                                Rất tiếc, chúng tôi không tìm thấy xe nào khớp với bộ lọc hoặc địa điểm tìm kiếm của bạn. Hãy thử thiết lập lại bộ lọc để bắt đầu lại.
                            </p>
                            <button @click="handleClearAll"
                                class="px-6 py-2.5 bg-[#1e4e57] text-white hover:bg-[#286874] font-bold text-sm rounded-xl shadow-md shadow-[#286874]/15 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2 cursor-pointer">
                                <Icon name="lucide:rotate-ccw" class="w-4 h-4" />
                                Thiết lập lại bộ lọc
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <MapTriggerButton :vehicles="carList" />
    </div>
</template>

<script setup lang="ts">
useSeoMeta({
  title: 'Danh Sách Xe Cho Thuê Tự Lái | DRIVIO',
  description: 'Tìm và đặt thuê xe tự lái phù hợp tại DRIVIO. Lọc theo hãng xe, số chỗ, khoảng giá. Đa dạng xe 4-7 chỗ, xe điện, xe hạng sang với giá ưu đãi.',
  keywords: 'danh sách xe cho thuê, tìm xe tự lái, thuê xe 4 chỗ, thuê xe 7 chỗ, lọc xe cho thuê, so sánh giá thuê xe',
  ogTitle: 'Khám Phá Xe Cho Thuê Tự Lái | DRIVIO',
  ogDescription: 'Hàng trăm xe tự lái đa dạng dòng xe chờ bạn khám phá tại DRIVIO.',
  ogImage: '/images/og/vehicle-list.jpg',
  twitterCard: 'summary_large_image',
})

useHead({
  link: [
    { rel: 'canonical', href: 'https://drivio.vn/vehicle-list' }
  ]
})

import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute } from '#app'
import SearchBar from '~/components/Vehicle/SearchBar.vue'
import FilterBar from '~/components/Vehicle/FilterBar.vue'
import vehicleCard from '~/components/Vehicle/VehicleCard.vue'
import { favoriteService } from '~/services/favorite.service'

const route = useRoute()
const router = useRouter()
const { user } = useAuth()
const { showToast } = useToast()
const { openLogin } = useAuthModal()

const activeFilters = ref<any>({})
const visibleCount = ref(8)
const favoriteCarIds = ref<number[]>([])
const filterBarKey = ref(0)
const isLoading = ref(true)

const handleFilterChange = (filters: any) => {
    activeFilters.value = filters
    visibleCount.value = 8 // Reset lại danh sách khi thay đổi bộ lọc
}

const handleClearAll = () => {
    // Clear URL parameters
    router.push({ path: '/vehicle-list', query: {} })
    // Re-mount FilterBar to reset its internal state
    filterBarKey.value++
    // Reset active filters
    activeFilters.value = {}
    // Reset visible count
    visibleCount.value = 8
}

import { carService } from '~/services/car.service'

const rawApiCars = ref<any[]>([])

const goToDetail = (carId: number) => {
    router.push(`/vehicles/${carId}`)
}

const fetchFavoriteCarIds = async () => {
    if (!user.value) return
    try {
        const res = await favoriteService.getFavorites()
        if (res.success && res.data) {
            favoriteCarIds.value = res.data.map(fav => fav.car_id)
        }
    } catch (error) {
        console.error("Lỗi khi lấy ID xe yêu thích:", error)
    }
}

const isCarFavorited = (carId: number) => {
    return favoriteCarIds.value.includes(carId)
}

const handleToggleFavorite = async (carId: number) => {
    if (!user.value) {
        showToast("Vui lòng đăng nhập để lưu xe yêu thích!", "error")
        openLogin()
        return
    }

    try {
        if (isCarFavorited(carId)) {
            const res = await favoriteService.removeFavorite(carId)
            if (res.success) {
                favoriteCarIds.value = favoriteCarIds.value.filter(id => id !== carId)
                showToast("Đã xóa khỏi danh sách yêu thích!", "success")
            }
        } else {
            const res = await favoriteService.addFavorite(carId)
            if (res.success) {
                favoriteCarIds.value.push(carId)
                showToast("Đã thêm vào danh sách yêu thích!", "success")
            }
        }
    } catch (error) {
        console.error("Lỗi khi thay đổi trạng thái yêu thích:", error)
        showToast("Đã có lỗi xảy ra!", "error")
    }
}

// Normalization Helpers
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

onMounted(async () => {
    try {
        isLoading.value = true
        const response = await carService.getCars()
        if (response.success && response.data) {
            rawApiCars.value = response.data
        }
        if (user.value) {
            await fetchFavoriteCarIds()
        }
    } catch (error) {
        console.error("Lỗi khi lấy danh sách xe từ API:", error)
    } finally {
        isLoading.value = false
    }
})

const mappedCarList = computed(() => {
    return rawApiCars.value.map(car => {
        const thumbnailImg = car.images?.find((img: any) => img.is_thumbnail === 1)?.image_url
            || car.images?.[0]?.image_url
            || 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=600';

        const discountPct = car.unit_price > 0 && car.discount_value > 0
            ? Math.round((car.discount_value / car.unit_price) * 100)
            : 0;

        return {
            id: car.id,
            name: car.name,
            image: thumbnailImg,
            price: car.unit_price,
            location: car.car_location?.address || 'Chưa cập nhật',
            coords: car.car_location?.location || null,
            discount_value: car.discount_value || 0,
            seats: Number(car.seat_count),
            transmission: normalizeTransmission(car.transmission),
            fuel: normalizeFuel(car.fuel_type),
            rating: car.reviews_avg_rating ? parseFloat(car.reviews_avg_rating) : 0,
            trips: car.trips_count || 0,
            isInstantBook: true,
            isDelivery: car.delivery_option_id ? true : false,
            noDeposit: false,
            discount: discountPct,
            ownerAvatar: car.owner?.avatar || '',
            status: car.status
        };
    });
})

const rawCarList = computed(() => {
    if (user.value && user.value.id) {
        return mappedCarList.value.filter(car => {
            const rawCar = rawApiCars.value.find(c => c.id === car.id);
            return !rawCar || rawCar.user_id !== user.value.id;
        });
    }
    return mappedCarList.value
})

const carList = computed(() => {
    let filtered = [...rawCarList.value]

    // Tìm kiếm từ URL (địa điểm, dòng xe từ HomePage)
    if (route.query.location) {
        const loc = (route.query.location as string).toLowerCase()
        filtered = filtered.filter(car => car.location.toLowerCase().includes(loc))
    }

    if (route.query.carType) {
        const cType = route.query.carType as string
        if (['4', '5', '7'].includes(cType)) {
            filtered = filtered.filter(car => car.seats === parseInt(cType))
        } else if (cType === 'ev') {
            filtered = filtered.filter(car => car.fuel.toLowerCase() === 'điện')
        }
    }

    // Lọc từ FilterBar component
    const filters = activeFilters.value

    if (filters.brands && filters.brands.length > 0) {
        filtered = filtered.filter(car =>
            filters.brands.some((brand: string) => car.name.toLowerCase().includes(brand.toLowerCase()))
        )
    }

    if (filters.seats && filters.seats.length > 0) {
        filtered = filtered.filter(car => filters.seats.includes(car.seats.toString()) || (filters.seats.includes('pickup') && car.name.toLowerCase().includes('bán tải')))
    }

    if (filters.instant) {
        filtered = filtered.filter(car => car.isInstantBook)
    }

    if (filters.delivery) {
        filtered = filtered.filter(car => car.isDelivery)
    }

    if (filters.noDeposit) {
        filtered = filtered.filter(car => car.noDeposit)
    }

    if (filters.discount) {
        filtered = filtered.filter(car => car.discount > 0)
    }

    if (filters.fiveStar) {
        filtered = filtered.filter(car => car.rating >= 5.0)
    }

    if (filters.priceMin !== undefined && filters.priceMax !== undefined) {
        filtered = filtered.filter(car => car.price >= filters.priceMin && car.price <= filters.priceMax)
    }

    if (filters.transmission) {
        const transValue = filters.transmission === 'auto' ? 'Số tự động' : 'Số sàn'
        filtered = filtered.filter(car => car.transmission === transValue)
    }

    if (filters.fuel) {
        const fuelMapping: Record<string, string[]> = {
            'gas': ['xăng'],
            'diesel': ['dầu', 'diesel'],
            'electric': ['điện']
        }
        filtered = filtered.filter(car => {
            const carFuel = car.fuel.toLowerCase()
            return fuelMapping[filters.fuel]?.some(val => carFuel.includes(val))
        })
    }

    return filtered
})

const paginatedCarList = computed(() => {
    return carList.value.slice(0, visibleCount.value)
})

const handleScroll = () => {
    // Nếu kéo xuống cách đáy < 200px
    const bottomOfWindow = document.documentElement.scrollTop + window.innerHeight >= document.documentElement.offsetHeight - 200
    if (bottomOfWindow && visibleCount.value < carList.value.length) {
        visibleCount.value += 8
    }
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped></style>
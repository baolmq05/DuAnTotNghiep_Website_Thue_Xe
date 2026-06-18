<template>
    <div class="min-h-screen bg-slate-50">
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
                        <FilterBar @filterChange="handleFilterChange" />
                    </div>

                    <div class="w-full">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            <div
                                v-for="car in paginatedCarList"
                                :key="car.id"
                                class="block h-full cursor-pointer"
                                @click="goToDetail(car.id)"
                            >
                                <vehicleCard
                                    :name="car.name"
                                    :image="car.image"
                                    :price="car.price"
                                    :location="car.location"
                                    :seats="car.seats"
                                    :transmission="car.transmission"
                                    :fuel="car.fuel"
                                    :rating="car.rating"
                                    :trips="car.trips"
                                    :is-instant-book="car.isInstantBook"
                                    :is-delivery="car.isDelivery"
                                    :no-deposit="car.noDeposit"
                                    :discount="car.discount"
                                    :isFavorite="isCarFavorited(car.id)"
                                    @toggle-favorite="handleToggleFavorite(car.id)"
                                />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <MapTriggerButton/>
    </div>
</template>

<script setup lang="ts">
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

const handleFilterChange = (filters: any) => {
    activeFilters.value = filters
    visibleCount.value = 8 // Reset lại danh sách khi thay đổi bộ lọc
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
        showToast("Vui lòng đăng nhập để lưu xe yêu thích!", "warning")
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
        const response = await carService.getCars()
        if (response.success && response.data) {
            rawApiCars.value = response.data
        }
        if (user.value) {
            await fetchFavoriteCarIds()
        }
    } catch (error) {
        console.error("Lỗi khi lấy danh sách xe từ API:", error)
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
            location: car.car_location?.street_name || 'Chưa cập nhật',
            seats: Number(car.seat_count),
            transmission: normalizeTransmission(car.transmission),
            fuel: normalizeFuel(car.fuel_type),
            rating: car.reviews_avg_rating ? parseFloat(car.reviews_avg_rating) : 5.0,
            trips: car.trips_count || 0,
            isInstantBook: true,
            isDelivery: car.delivery_option_id ? true : false,
            noDeposit: false,
            discount: discountPct
        };
    });
})

const rawCarList = computed(() => {
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
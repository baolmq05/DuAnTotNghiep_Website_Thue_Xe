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
                            <vehicleCard
                                v-for="car in paginatedCarList"
                                :key="car.id"
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
                            />
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute } from '#app'
import SearchBar from '~/components/Vehicle/SearchBar.vue'
import FilterBar from '~/components/Vehicle/FilterBar.vue'
import vehicleCard from '~/components/Vehicle/VehicleCard.vue'

const route = useRoute()

const activeFilters = ref<any>({})
const visibleCount = ref(8)

const handleFilterChange = (filters: any) => {
    activeFilters.value = filters
    visibleCount.value = 8 // Reset lại danh sách khi thay đổi bộ lọc
}

// Mảng dữ liệu gốc
const baseCarList = [
    {
        id: 1,
        name: "Toyota Camry 2023",
        image: "https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&q=80&w=600",
        price: 500000,
        location: "Ninh Kiều, Cần Thơ",
        seats: 5,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 4.8,
        trips: 45,
        isInstantBook: true,
        isDelivery: true,
        noDeposit: true,
        discount: 10
    },
    {
        id: 2,
        name: "Honda CR-V 2023",
        image: "https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=600",
        price: 550000,
        location: "Cái Răng, Cần Thơ",
        seats: 7,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 4.9,
        trips: 32,
        isInstantBook: false,
        isDelivery: true,
        discount: 0
    },
    {
        id: 3,
        name: "Mazda 3 2022",
        image: "https://images.unsplash.com/photo-1511919884226-fd3cad34687c?auto=format&fit=crop&q=80&w=600",
        price: 400000,
        location: "Bình Thủy, Cần Thơ",
        seats: 5,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 4.7,
        trips: 28,
        isInstantBook: true,
        isDelivery: false,
        discount: 5
    },
    {
        id: 4,
        name: "BMW X5 2023",
        image: "https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&q=80&w=600",
        price: 1200000,
        location: "Ninh Kiều, Cần Thơ",
        seats: 7,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 5.0,
        trips: 56,
        isInstantBook: true,
        isDelivery: true,
        discount: 15
    },
    {
        id: 5,
        name: "Hyundai Tucson 2023",
        image: "https://images.unsplash.com/photo-1617469767053-d3b508a0d825?auto=format&fit=crop&q=80&w=600",
        price: 480000,
        location: "Ninh Kiều, Cần Thơ",
        seats: 5,
        transmission: "Số tự động",
        fuel: "Dầu",
        rating: 4.6,
        trips: 38,
        isInstantBook: false,
        isDelivery: false,
        discount: 0
    },
    {
        id: 6,
        name: "Kia Seltos 2023",
        image: "https://images.unsplash.com/photo-1632245889029-e406faaa34cd?auto=format&fit=crop&q=80&w=600",
        price: 420000,
        location: "Ô Môn, Cần Thơ",
        seats: 5,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 4.7,
        trips: 42,
        isInstantBook: false,
        isDelivery: true,
        discount: 8
    },
    {
        id: 7,
        name: "Vinfast Fadil 2023",
        image: "https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=600",
        price: 350000,
        location: "Ninh Kiều, Cần Thơ",
        seats: 5,
        transmission: "Số sàn",
        fuel: "Xăng",
        rating: 4.5,
        trips: 25,
        isInstantBook: true,
        isDelivery: true,
        discount: 0
    },
    {
        id: 8,
        name: "Mercedes-Benz A-Class",
        image: "https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&q=80&w=600",
        price: 900000,
        location: "Ninh Kiều, Cần Thơ",
        seats: 5,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 4.9,
        trips: 52,
        isInstantBook: true,
        isDelivery: true,
        discount: 12
    },
    {
    id: 9,
    name: "Toyota Vios",
    image: "https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=600",
    price: 550000,
    location: "Quận 1, TP. Hồ Chí Minh",
    seats: 5,
    transmission: "Số tự động",
    fuel: "Xăng",
    rating: 4.7,
    trips: 85,
    isInstantBook: true,
    isDelivery: true,
    discount: 10
    },
    {
        id: 10,
        name: "Ford Ranger",
        image: "https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&q=80&w=600",
        price: 1200000,
        location: "Hải Châu, Đà Nẵng",
        seats: 5,
        transmission: "Số tự động",
        fuel: "Diesel",
        rating: 4.8,
        trips: 67,
        isInstantBook: true,
        isDelivery: false,
        discount: 15
    },
    {
        id: 11,
        name: "Kia Carnival",
        image: "https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&q=80&w=600",
        price: 1500000,
        location: "Nha Trang, Khánh Hòa",
        seats: 7,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 4.9,
        trips: 120,
        isInstantBook: true,
        isDelivery: true,
        discount: 8
    },
    {
        id: 12,
        name: "Mazda CX-5",
        image: "https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&q=80&w=600",
        price: 950000,
        location: "Long Xuyên, An Giang",
        seats: 5,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 4.8,
        trips: 73,
        isInstantBook: false,
        isDelivery: true,
        discount: 5
    },
    {
        id: 13,
        name: "Hyundai Accent",
        image: "https://images.unsplash.com/photo-1542282088-fe8426682b8f?auto=format&fit=crop&q=80&w=600",
        price: 500000,
        location: "Rạch Giá, Kiên Giang",
        seats: 5,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 4.6,
        trips: 91,
        isInstantBook: true,
        isDelivery: true,
        discount: 12
    },
    {
        id: 14,
        name: "Honda City",
        image: "https://images.unsplash.com/photo-1494976388531-d1058494cdd8?auto=format&fit=crop&q=80&w=600",
        price: 600000,
        location: "Vũng Tàu, Bà Rịa - Vũng Tàu",
        seats: 5,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 4.7,
        trips: 64,
        isInstantBook: false,
        isDelivery: true,
        discount: 7
    },
    {
        id: 15,
        name: "VinFast VF8",
        image: "https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&q=80&w=600",
        price: 1300000,
        location: "Hạ Long, Quảng Ninh",
        seats: 5,
        transmission: "Số tự động",
        fuel: "Điện",
        rating: 4.9,
        trips: 40,
        isInstantBook: true,
        isDelivery: true,
        discount: 20
    },
    {
        id: 16,
        name: "Mitsubishi Xpander",
        image: "https://images.unsplash.com/photo-1511919884226-fd3cad34687c?auto=format&fit=crop&q=80&w=600",
        price: 800000,
        location: "Buôn Ma Thuột, Đắk Lắk",
        seats: 7,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 4.8,
        trips: 102,
        isInstantBook: true,
        isDelivery: false,
        discount: 10
    },
    {
        id: 17,
        name: "Toyota Fortuner",
        image: "https://images.unsplash.com/photo-1489824904134-891ab64532f1?auto=format&fit=crop&q=80&w=600",
        price: 1400000,
        location: "Phú Quốc, Kiên Giang",
        seats: 7,
        transmission: "Số tự động",
        fuel: "Diesel",
        rating: 4.9,
        trips: 89,
        isInstantBook: true,
        isDelivery: true,
        discount: 18
    },
    {
        id: 18,
        name: "Suzuki XL7",
        image: "https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?auto=format&fit=crop&q=80&w=600",
        price: 750000,
        location: "Sóc Trăng",
        seats: 7,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 4.5,
        trips: 38,
        isInstantBook: false,
        isDelivery: true,
        discount: 5
    },
    {
        id: 19,
        name: "BMW 320i",
        image: "https://images.unsplash.com/photo-1502161254066-6c74afbf07aa?auto=format&fit=crop&q=80&w=600",
        price: 1800000,
        location: "Thủ Đức, TP. Hồ Chí Minh",
        seats: 5,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 5.0,
        trips: 26,
        isInstantBook: true,
        isDelivery: true,
        discount: 10
    },
    {
        id: 20,
        name: "Mercedes-Benz C200",
        image: "https://images.unsplash.com/photo-1494905998402-395d579af36f?auto=format&fit=crop&q=80&w=600",
        price: 2200000,
        location: "Đà Lạt, Lâm Đồng",
        seats: 5,
        transmission: "Số tự động",
        fuel: "Xăng",
        rating: 4.9,
        trips: 58,
        isInstantBook: true,
        isDelivery: true,
        discount: 15
    }
]

// Nhân bản dữ liệu để test infinite scroll (tạo 24 xe)
const rawCarList = [
    ...baseCarList,
    ...baseCarList.map(c => ({...c, id: c.id + 8, name: c.name + ' (2)'})),
    ...baseCarList.map(c => ({...c, id: c.id + 16, name: c.name + ' (3)'}))
]

const carList = computed(() => {
    let filtered = [...rawCarList]
    
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
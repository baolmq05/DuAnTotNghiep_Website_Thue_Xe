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
                        <FilterBar />
                    </div>

                    <div class="w-full">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            <vehicleCard
                                v-for="car in carList"
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
import { ref } from 'vue'
import SearchBar from '~/components/Vehicle/SearchBar.vue'
import FilterBar from '~/components/Vehicle/FilterBar.vue'
import vehicleCard from '~/components/Vehicle/VehicleCard.vue'

// THÊM: Mảng dữ liệu giả chứa đầy đủ thông tin để truyền vào component
const carList = ref([
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
    }
])
</script>

<style scoped></style>
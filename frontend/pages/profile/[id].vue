<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Back Button -->
    <button @click="goBack" class="flex items-center gap-2 text-slate-500 hover:text-brand-primary transition-colors font-bold text-sm focus:outline-none">
      <Icon name="lucide:arrow-left" class="w-4 h-4" />
      Quay lại
    </button>

    <!-- Loading State -->
    <div v-if="loading && !profileData" class="flex flex-col items-center justify-center py-20 space-y-4">
      <Icon name="svg-spinners:ring-resize" class="w-12 h-12 text-brand-primary" />
      <p class="text-slate-500 font-medium">Đang tải thông tin hồ sơ...</p>
    </div>

    <!-- Main Profile Content -->
    <div v-else-if="profileData" class="space-y-6">
      <!-- Profile Header Card -->
      <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100/80 flex flex-col md:flex-row items-center md:items-start gap-6 md:gap-8">
        <!-- Avatar -->
        <div class="shrink-0">
          <div v-if="!profileData.avatar" class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-brand-primary flex items-center justify-center text-white text-3xl font-bold shadow-md shadow-brand-primary/10">
            {{ profileData.name?.charAt(0).toUpperCase() || 'M' }}
          </div>
          <img v-else :src="profileData.avatar"
            class="w-24 h-24 md:w-32 md:h-32 rounded-full object-cover border border-slate-100 shadow-md shadow-brand-primary/10"
            referrerpolicy="no-referrer"
            alt="User Avatar" />
        </div>

        <!-- Info details -->
        <div class="flex-1 text-center md:text-left space-y-4">
          <div>
            <h2 class="text-2xl md:text-3xl font-black text-brand-dark">
              {{ profileData.name }}
            </h2>
            <p class="text-sm text-slate-400 font-medium mt-1">
              Thành viên từ: {{ profileData.joinDate }}
            </p>
          </div>

          <!-- Quick Stats -->
          <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
            <!-- rating -->
            <div class="bg-amber-50 border border-amber-100 rounded-2xl px-4 py-2 flex items-center gap-1.5 shadow-sm">
              <Icon name="ic:outline-star" class="text-amber-500" size="18" />
              <span class="font-bold text-amber-800 text-sm">
                {{ profileData.rating ? parseFloat(profileData.rating).toFixed(1) : '0' }} sao
              </span>
            </div>

            <!-- trips -->
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl px-4 py-2 flex items-center gap-1.5 shadow-sm">
              <Icon name="ic:outline-stars" class="text-emerald-600" size="18" />
              <span class="font-bold text-emerald-800 text-sm">
                {{ profileData.tripsCount || 0 }} chuyến đi
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation Tabs -->
      <div class="border-b border-slate-200">
        <div class="flex gap-8">
          <button 
            v-if="profileData.cars && profileData.cars.length > 0"
            @click="switchTab('owner')"
            class="pb-4 font-bold text-sm tracking-wider uppercase border-b-2 transition-all focus:outline-none"
            :class="activeTab === 'owner' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-slate-400 hover:text-slate-600'"
          >
            Vai trò Chủ xe
          </button>
          <button 
            @click="switchTab('renter')"
            class="pb-4 font-bold text-sm tracking-wider uppercase border-b-2 transition-all focus:outline-none"
            :class="activeTab === 'renter' ? 'border-brand-primary text-brand-primary' : 'border-transparent text-slate-400 hover:text-slate-600'"
          >
            Vai trò Người thuê
          </button>
        </div>
      </div>

      <!-- Tab Content -->
      <div class="space-y-6">
        <!-- Loading overlay when switching tabs -->
        <div v-if="loading" class="flex justify-center py-12">
          <Icon name="svg-spinners:ring-resize" class="w-8 h-8 text-brand-primary" />
        </div>

        <div v-else class="space-y-6 animate-fade-in">
          <!-- OWNER TAB -->
          <div v-if="activeTab === 'owner'" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Cars List -->
            <div class="lg:col-span-7 space-y-6">
              <h3 class="text-base font-bold text-brand-dark flex items-center gap-2">
                <span class="w-1.5 h-5 bg-brand-primary rounded-full"></span>
                Danh sách xe của chủ xe ({{ profileData.cars?.length || 0 }})
              </h3>

              <div v-if="profileData.cars && profileData.cars.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <NuxtLink 
                  v-for="car in profileData.cars" 
                  :key="car.id" 
                  :to="`/vehicles/${car.id}`"
                  class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden group hover:shadow-md transition-all flex flex-col"
                >
                  <!-- Car image -->
                  <div class="aspect-[16/10] overflow-hidden bg-slate-100 relative">
                    <img 
                      :src="car.image || 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=400'" 
                      alt="Car Image"
                      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                    />
                  </div>

                  <!-- Car details -->
                  <div class="p-4 flex-grow flex flex-col justify-between space-y-3">
                    <div>
                      <h4 class="font-extrabold text-brand-dark group-hover:text-brand-primary transition-colors line-clamp-1">
                        {{ car.name }}
                      </h4>
                      <p class="text-xs text-slate-400 font-semibold mt-1">
                        {{ car.car_location?.address }}
                      </p>
                    </div>

                    <!-- Tech specs -->
                    <div class="flex items-center gap-4 text-xs font-semibold text-slate-500">
                      <span class="flex items-center gap-1">
                        <Icon name="lucide:armchair" class="w-3.5 h-3.5 text-slate-400" />
                        {{ car.seat_count }} ghế
                      </span>
                      <span class="flex items-center gap-1">
                        <Icon name="lucide:cog" class="w-3.5 h-3.5 text-slate-400" />
                        {{ car.transmission === 'Automatic' || car.transmission === 'Tự động' ? 'Tự động' : 'Số sàn' }}
                      </span>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-slate-100 pt-3 flex items-center justify-between">
                      <div class="flex items-center gap-1.5 text-xs text-slate-600 font-bold">
                        <Icon name="ic:outline-star" class="text-amber-500 w-4 h-4" />
                        <span>{{ car.reviews_avg_rating ? parseFloat(car.reviews_avg_rating).toFixed(1) : '0' }}</span>
                        <span class="text-slate-300">•</span>
                        <span>{{ car.trips_count }} chuyến</span>
                      </div>
                      <div class="text-right">
                        <span class="text-base font-black text-brand-primary">{{ car.unit_price ? car.unit_price.toLocaleString('vi-VN') : 0 }} VNĐ</span>
                        <span class="text-[10px] text-slate-400 font-medium">/ngày</span>
                      </div>
                    </div>
                  </div>
                </NuxtLink>
              </div>
              <div v-else class="bg-slate-50 rounded-2xl p-8 text-center text-slate-400 font-medium">
                Chủ xe này chưa có xe được đăng ký hoạt động.
              </div>
            </div>

            <!-- Right Column: Vehicle Reviews -->
            <div class="lg:col-span-5 space-y-6">
              <h3 class="text-base font-bold text-brand-dark flex items-center gap-2">
                <span class="w-1.5 h-5 bg-brand-accent rounded-full"></span>
                Đánh giá dịch vụ xe từ khách hàng
              </h3>

              <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100/80 space-y-4">
                <div v-if="profileData.reviews && profileData.reviews.length > 0" class="space-y-4 divide-y divide-slate-100">
                  <div 
                    v-for="(review, index) in profileData.reviews" 
                    :key="review.id"
                    class="pt-4 first:pt-0 space-y-2"
                  >
                    <div class="flex items-center gap-3">
                      <!-- Reviewer avatar -->
                      <div v-if="!review.reviewer_avatar" class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm shrink-0">
                        {{ review.reviewer_name?.charAt(0).toUpperCase() || 'M' }}
                      </div>
                      <img 
                        v-else
                        :src="review.reviewer_avatar" 
                        class="w-10 h-10 rounded-full object-cover shrink-0" 
                        alt="Reviewer Avatar" 
                      />
                      <div class="min-w-0">
                        <h5 class="text-sm font-bold text-brand-dark truncate">
                          {{ review.reviewer_name }}
                        </h5>
                        <div class="flex items-center gap-1.5 mt-0.5">
                          <div class="flex items-center gap-0.5">
                            <Icon v-for="s in 5" :key="s" name="heroicons:star-solid" class="w-3 h-3"
                              :class="s <= review.rating ? 'text-amber-400' : 'text-slate-200'" />
                          </div>
                          <span class="text-[10px] text-slate-400 font-bold">{{ review.created_at }}</span>
                        </div>
                      </div>
                    </div>
                    <p class="text-xs md:text-sm text-slate-600 leading-relaxed font-medium pl-1 italic">
                      "{{ review.comment || 'Đánh giá tốt!' }}"
                    </p>
                  </div>
                </div>
                <div v-else class="py-12 text-center text-slate-400 font-medium">
                  Chưa có nhận xét hay đánh giá nào về dịch vụ xe của chủ xe này.
                </div>
              </div>
            </div>
          </div>

          <!-- RENTER TAB -->
          <div v-else-if="activeTab === 'renter'" class="max-w-3xl mx-auto space-y-6">
            <h3 class="text-base font-bold text-brand-dark flex items-center gap-2">
              <span class="w-1.5 h-5 bg-brand-primary rounded-full"></span>
              Đánh giá từ các chủ xe
            </h3>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100/80 space-y-4">
              <div v-if="profileData.reviews && profileData.reviews.length > 0" class="space-y-4 divide-y divide-slate-100">
                <div 
                  v-for="(review, index) in profileData.reviews" 
                  :key="review.id"
                  class="pt-4 first:pt-0 space-y-2"
                >
                  <div class="flex items-center gap-3">
                    <div v-if="!review.reviewer_avatar" class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm shrink-0">
                      {{ review.reviewer_name?.charAt(0).toUpperCase() || 'M' }}
                    </div>
                    <img 
                      v-else
                      :src="review.reviewer_avatar" 
                      class="w-10 h-10 rounded-full object-cover shrink-0" 
                      alt="Reviewer Avatar" 
                    />
                    <div>
                      <h5 class="text-sm font-bold text-brand-dark">
                        {{ review.reviewer_name }}
                      </h5>
                      <div class="flex items-center gap-1.5 mt-0.5">
                        <div class="flex items-center gap-0.5">
                          <Icon v-for="s in 5" :key="s" name="heroicons:star-solid" class="w-3 h-3"
                            :class="s <= review.rating ? 'text-amber-400' : 'text-slate-200'" />
                        </div>
                        <span class="text-[10px] text-slate-400 font-bold">{{ review.created_at }}</span>
                      </div>
                    </div>
                  </div>
                  <p class="text-xs md:text-sm text-slate-600 leading-relaxed font-medium pl-1 italic">
                    "{{ review.comment || 'Khách hàng đi xe giữ gìn sạch sẽ, lịch sự.' }}"
                  </p>
                </div>
              </div>
              <div v-else class="py-12 text-center text-slate-400 font-medium">
                Chưa có chủ xe nào để lại đánh giá cho người thuê này.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Error State -->
    <div v-else class="flex flex-col items-center justify-center py-20 space-y-4 bg-white rounded-3xl border border-slate-100/80">
      <Icon name="lucide:user-x" class="w-16 h-16 text-slate-300" />
      <p class="text-slate-500 font-semibold text-lg">Không tìm thấy thông tin người dùng</p>
      <p class="text-slate-400 text-sm">Vui lòng kiểm tra lại liên kết hoặc ID người dùng.</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { carService } from '~/services/car.service'

definePageMeta({ layout: "vehicle-detail" });

const route = useRoute()
const router = useRouter()
const userId = route.params.id as string

const activeTab = ref<'owner' | 'renter'>('owner')
const loading = ref(true)
const profileData = ref<any>(null)

// Initialize tab from query parameters
if (route.query.role === 'renter') {
  activeTab.value = 'renter'
}

const fetchProfile = async (updateLoading = true) => {
  if (updateLoading) {
    loading.value = true
  }
  try {
    const isOwner = activeTab.value === 'owner'
    const res = await carService.getProfileReviews(userId, isOwner)
    if (res && res.success && res.data) {
      profileData.value = res.data
      
      // Auto-fallback: if we requested 'owner' but user has no cars registered and role wasn't explicitly requested
      if (isOwner && (!res.data.cars || res.data.cars.length === 0) && !route.query.role) {
        activeTab.value = 'renter'
        const renterRes = await carService.getProfileReviews(userId, false)
        if (renterRes && renterRes.success) {
          profileData.value = renterRes.data
        }
      }
    } else {
      throw new Error('API returned success=false or empty data')
    }
  } catch (error) {
    console.error('Error fetching user profile from API, falling back to mock data:', error)
    
    // Fallback Mock Data
    const mockUser = {
      id: parseInt(userId) || 4,
      name: userId === '4' ? "Lê Minh Quốc Bảo (Chủ xe Demo)" : "Khách Hàng Trải Nghiệm",
      avatar: "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150",
      joinDate: "05/2026",
      rating: activeTab.value === 'owner' ? 4.8 : 4.9,
      tripsCount: activeTab.value === 'owner' ? 12 : 8,
      cars: [
        {
          id: 1,
          name: "VinFast VF8 2023",
          image: "https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=400",
          seat_count: 5,
          transmission: "Automatic",
          unit_price: 1200000,
          reviews_avg_rating: 4.8,
          trips_count: 10,
          car_location: {
            address: "Quận 12, TP. Hồ Chí Minh"
          }
        },
        {
          id: 2,
          name: "Toyota Camry 2022",
          image: "https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=400",
          seat_count: 5,
          transmission: "Automatic",
          unit_price: 1000000,
          reviews_avg_rating: 4.9,
          trips_count: 8,
          car_location: {
            address: "Quận Tân Bình, TP. Hồ Chí Minh"
          }
        }
      ],
      reviews: activeTab.value === 'owner' ? [
        {
          id: 101,
          reviewer_name: "Trần Anh Tuấn",
          reviewer_avatar: "https://images.unsplash.com/photo-1599566150163-29194dcaad36?w=60",
          rating: 5,
          created_at: "15/06/2026",
          comment: "Xe đi rất êm, chủ xe giao xe đúng giờ và thân thiện. VF8 đi sướng lắm mọi người ạ."
        },
        {
          id: 102,
          reviewer_name: "Phạm Minh Trí",
          reviewer_avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60",
          rating: 4,
          created_at: "10/06/2026",
          comment: "Xe sạch sẽ, đầy đủ tiện nghi. Chủ xe hướng dẫn nhiệt tình. Sẽ tiếp tục ủng hộ."
        }
      ] : [
        {
          id: 201,
          reviewer_name: "Nguyễn Văn Hùng (Chủ xe VF8)",
          reviewer_avatar: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=60",
          rating: 5,
          created_at: "20/06/2026",
          comment: "Khách thuê đi xe giữ gìn sạch sẽ, trả xe đúng giờ, thân thiện lịch sự. Đánh giá 5 sao!"
        },
        {
          id: 202,
          reviewer_name: "Lê Thị Hồng (Chủ xe Camry)",
          reviewer_avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=60",
          rating: 5,
          created_at: "12/06/2026",
          comment: "Giao dịch nhanh chóng, khách hàng uy tín, đi đúng số km cam kết. Hài lòng."
        }
      ]
    }
    profileData.value = mockUser
  } finally {
    loading.value = false
  }
}

const switchTab = (tab: 'owner' | 'renter') => {
  if (activeTab.value === tab) return
  activeTab.value = tab
  fetchProfile(true)
}

const goBack = () => {
  router.back()
}

onMounted(() => {
  fetchProfile(true)
})
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(4px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

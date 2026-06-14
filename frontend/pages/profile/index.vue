<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Account Information -->
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm">
      <div
        class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6"
      >
        <h2 class="text-xl md:text-2xl font-semibold">Thông tin tài khoản</h2>

        <div class="flex items-center gap-3">
          <button @click="openEditModal" class="px-4 py-2 bg-brand-primary text-white text-sm font-semibold rounded-xl hover:bg-brand-dark transition-colors flex items-center gap-1.5 focus:outline-none">
            <Icon name="ic:outline-edit" />
            Chỉnh sửa
          </button>

          <div
            class="flex items-center gap-2 px-4 py-2 border rounded-xl text-green-500 w-fit text-sm"
          >
            <Icon name="ic:outline-stars" size="20" />
            <span>0 chuyến</span>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Avatar -->
        <div class="lg:col-span-4">
          <div class="flex flex-col items-center">
            <img
              :src="user?.avatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100'"
              class="w-28 h-28 md:w-32 md:h-32 rounded-full object-cover border border-slate-100 shadow-sm"
              alt="User Avatar"
            />

            <h3 class="mt-4 text-xl md:text-2xl font-semibold text-center">
              {{ user?.name || 'Người dùng' }}
            </h3>

            <p class="text-sm text-gray-500 mt-1">
              Tham gia: {{ user?.created_at ? new Date(user.created_at).toLocaleDateString('vi-VN') : '13/05/2026' }}
            </p>

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

              <p class="font-medium mt-2">{{ user?.DOB ? new Date(user.DOB).toLocaleDateString('vi-VN') : 'Chưa cập nhật' }}</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
              <p class="text-gray-500 text-sm">Giới tính</p>

              <p class="font-medium mt-2">{{ user?.gender === 1 ? 'Nam' : (user?.gender === 0 ? 'Nữ' : 'Khác') }}</p>
            </div>
          </div>

          <div class="mt-6 space-y-4">
            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
              <span class="text-gray-500"> Số điện thoại </span>

              <span class="font-medium break-all">{{ user?.phone || 'Chưa cập nhật' }}</span>
            </div>
            
            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
              <span class="text-gray-500"> Email </span>

              <span class="font-medium break-all">{{ user?.email || 'Chưa cập nhật' }}</span>
            </div>

            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
              <span class="text-gray-500"> Facebook </span>

              <span class="font-medium"> Thêm liên kết </span>
            </div>

            <div class="flex flex-col gap-1 sm:flex-row sm:justify-between">
              <span class="text-gray-500"> Google </span>

              <span class="font-medium">{{ user?.name || 'Chưa liên kết' }}</span>
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
    <!-- Edit Profile Modal -->
    <div v-if="isEditModalOpen" class="fixed inset-0 z-[999] flex items-center justify-center p-4">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeEditModal"></div>

      <!-- Modal Content -->
      <div class="relative bg-white w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl z-10 p-8 border border-slate-100 flex flex-col animate-scale-in">
        <h3 class="text-xl font-black text-brand-dark mb-6">Chỉnh sửa thông tin cá nhân</h3>
        
        <form @submit.prevent="handleUpdateProfile" class="space-y-4">
          <!-- Name Field -->
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Họ và tên</label>
            <input v-model="editForm.name" type="text" required
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-primary focus:bg-white transition-all" />
          </div>

          <!-- Phone Field -->
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Số điện thoại</label>
            <input v-model="editForm.phone" type="text" placeholder="Nhập số điện thoại"
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-primary focus:bg-white transition-all" />
          </div>

          <!-- Gender & DOB Fields -->
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Giới tính</label>
              <select v-model="editForm.gender"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-primary focus:bg-white transition-all">
                <option :value="1">Nam</option>
                <option :value="0">Nữ</option>
                <option :value="2">Khác</option>
              </select>
            </div>
            
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Ngày sinh</label>
              <input v-model="editForm.DOB" type="date"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-brand-primary focus:bg-white transition-all" />
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="grid grid-cols-2 gap-4 pt-4">
            <!-- Close Button -->
            <button type="button" @click="closeEditModal"
              class="py-3 px-4 border border-slate-200 text-slate-500 font-bold rounded-xl hover:bg-slate-50 hover:text-slate-700 transition-colors focus:outline-none text-sm">
              Đóng
            </button>
            <!-- Submit Button -->
            <button type="submit"
              class="py-3 px-4 bg-brand-primary hover:bg-brand-dark text-white font-bold rounded-xl transition-all duration-200 focus:outline-none text-sm shadow-md shadow-brand-primary/10">
              Cập nhật thông tin
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'

definePageMeta({
  layout: 'profile',
})

const { user, updateProfile } = useAuth()
const { showToast } = useToast()

const isEditModalOpen = ref(false)

const editForm = reactive({
  name: '',
  phone: '',
  gender: 1,
  DOB: ''
})

const openEditModal = () => {
  if (user.value) {
    editForm.name = user.value.name || ''
    editForm.phone = user.value.phone || ''
    editForm.gender = user.value.gender !== undefined ? user.value.gender : 1
    editForm.DOB = user.value.DOB || ''
  }
  isEditModalOpen.value = true
}

const closeEditModal = () => {
  isEditModalOpen.value = false
}

const handleUpdateProfile = async () => {
  const res = await updateProfile({
    name: editForm.name,
    phone: editForm.phone,
    gender: editForm.gender,
    DOB: editForm.DOB
  })

  if (res.success) {
    showToast("Cập nhật thông tin thành công!", "success")
    closeEditModal()
  } else {
    showToast(res.message || "Cập nhật hồ sơ thất bại!", "error")
  }
}

onMounted(() => {
  if (!user.value) {
    navigateTo('/')
  }
})
</script>

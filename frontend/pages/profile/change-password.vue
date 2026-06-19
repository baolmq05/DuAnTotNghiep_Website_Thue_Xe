<template>
  <div class="space-y-6 min-h-screen">
    <!-- Page Header -->
    <div class="flex flex-col gap-1">
      <h1 class="text-2xl md:text-3xl font-black text-slate-900">Đổi mật khẩu</h1>
      <p class="text-sm text-slate-500">
        Bạn nên sử dụng mật khẩu mạnh mà bạn chưa sử dụng ở nơi khác để bảo mật tài khoản.
      </p>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 max-w-2xl">
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Current Password -->
        <div class="space-y-2">
          <label class="block text-sm font-semibold text-slate-700">Mật khẩu hiện tại</label>
          <div class="relative">
            <input
              v-model="form.current_password"
              :type="showCurrentPassword ? 'text' : 'password'"
              placeholder="Nhập mật khẩu hiện tại"
              class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-4 pr-12 text-sm text-slate-700 outline-none transition focus:border-[#286874] focus:bg-white focus:ring-4 focus:ring-[#286874]/10"
              required
            />
            <button
              type="button"
              @click="showCurrentPassword = !showCurrentPassword"
              class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition"
            >
              <Icon :name="showCurrentPassword ? 'lucide:eye-off' : 'lucide:eye'" class="text-lg" />
            </button>
          </div>
          <span v-if="errors.current_password" class="text-xs text-red-500 font-medium">
            {{ errors.current_password[0] }}
          </span>
        </div>

        <!-- New Password -->
        <div class="space-y-2">
          <label class="block text-sm font-semibold text-slate-700">Mật khẩu mới</label>
          <div class="relative">
            <input
              v-model="form.new_password"
              :type="showNewPassword ? 'text' : 'password'"
              placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)"
              class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-4 pr-12 text-sm text-slate-700 outline-none transition focus:border-[#286874] focus:bg-white focus:ring-4 focus:ring-[#286874]/10"
              required
            />
            <button
              type="button"
              @click="showNewPassword = !showNewPassword"
              class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition"
            >
              <Icon :name="showNewPassword ? 'lucide:eye-off' : 'lucide:eye'" class="text-lg" />
            </button>
          </div>
          <span v-if="errors.new_password" class="text-xs text-red-500 font-medium">
            {{ errors.new_password[0] }}
          </span>
        </div>

        <!-- Confirm New Password -->
        <div class="space-y-2">
          <label class="block text-sm font-semibold text-slate-700">Xác nhận mật khẩu mới</label>
          <div class="relative">
            <input
              v-model="form.new_password_confirmation"
              :type="showConfirmPassword ? 'text' : 'password'"
              placeholder="Xác nhận lại mật khẩu mới"
              class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-4 pr-12 text-sm text-slate-700 outline-none transition focus:border-[#286874] focus:bg-white focus:ring-4 focus:ring-[#286874]/10"
              required
            />
            <button
              type="button"
              @click="showConfirmPassword = !showConfirmPassword"
              class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition"
            >
              <Icon :name="showConfirmPassword ? 'lucide:eye-off' : 'lucide:eye'" class="text-lg" />
            </button>
          </div>
          <span v-if="errors.new_password_confirmation" class="text-xs text-red-500 font-medium">
            {{ errors.new_password_confirmation[0] }}
          </span>
        </div>

        <!-- Submit Button -->
        <div class="pt-2 flex justify-end">
          <button
            type="submit"
            :disabled="loading"
            class="flex items-center justify-center gap-2 rounded-xl bg-[#286874] px-6 py-3 text-sm font-bold text-white shadow-lg shadow-[#286874]/20 transition-all hover:bg-[#1e4e57] disabled:opacity-50 disabled:cursor-not-allowed w-full sm:w-auto"
          >
            <Icon v-if="loading" name="lucide:loader-2" class="animate-spin text-lg" />
            {{ loading ? 'Đang thực hiện...' : 'Cập nhật mật khẩu' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'

definePageMeta({
  layout: 'profile'
})

useHead({
  title: 'Đổi mật khẩu - Drivio'
})

const { user, changePassword } = useAuth()
const { showToast } = useToast()

// Redirect if not logged in
onMounted(() => {
  if (!user.value) {
    navigateTo('/')
  }
})

const form = reactive({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

const loading = ref(false)
const errors = ref<Record<string, string[]>>({})

const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmPassword = ref(false)

const handleSubmit = async () => {
  // Clear previous errors
  errors.value = {}

  // Local validation
  if (form.new_password.length < 6) {
    errors.value = {
      new_password: ['Mật khẩu mới phải có ít nhất 6 ký tự.']
    }
    return
  }

  if (form.new_password !== form.new_password_confirmation) {
    errors.value = {
      new_password_confirmation: ['Mật khẩu xác nhận không khớp.']
    }
    return
  }

  loading.value = true
  try {
    const res = await changePassword({
      current_password: form.current_password,
      new_password: form.new_password,
      new_password_confirmation: form.new_password_confirmation
    })

    if (res.success) {
      showToast(res.message || 'Đổi mật khẩu thành công!', 'success')
      // Reset form
      form.current_password = ''
      form.new_password = ''
      form.new_password_confirmation = ''
    } else {
      showToast(res.message || 'Đổi mật khẩu thất bại.', 'error')
      if (res.errors) {
        errors.value = res.errors
      }
    }
  } catch (error: any) {
    console.error('Lỗi khi đổi mật khẩu:', error)
    showToast('Đã xảy ra lỗi kết nối.', 'error')
  } finally {
    loading.value = false
  }
}
</script>

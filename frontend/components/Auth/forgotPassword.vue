<template>
  <LoadingOverlay :loading="isLoading" :text="loadingText" />

  <div v-if="isForgotPasswordOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal"></div>

    <div class="relative bg-white w-full max-w-xl rounded-3xl overflow-hidden shadow-2xl z-10 animate-fade-in">
      <button @click="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-20 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <div class="p-8 sm:p-12 flex flex-col justify-center bg-white">
        <div class="w-full">
          <div class="mb-8">
            <h2 class="text-2xl font-black text-gray-900 tracking-tight text-center">Quên mật khẩu</h2>
            <p class="text-slate-500 text-sm text-center mt-2">
              {{ step === 1 ? 'Nhập email để nhận mã OTP xác thực khôi phục mật khẩu.' : 'Nhập mã OTP và mật khẩu mới của bạn.' }}
            </p>
          </div>

          <!-- STEP 1: Enter Email -->
          <form v-if="step === 1" @submit.prevent="handleRequestOtp" class="space-y-4">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Email tài khoản</label>
              <input v-model="email" type="email" placeholder="example@gmail.com"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />
              <p v-if="emailError" class="text-xs text-red-500 font-medium pl-1 animate-fade-in">{{ emailError }}</p>
            </div>

            <button type="submit"
              class="w-full mt-2 py-3.5 bg-[#286874] text-white font-bold rounded-xl shadow-lg shadow-cyan-900/10 hover:bg-[#1e4e57] hover:shadow-xl transition-all duration-200 text-sm">
              Gửi mã OTP
            </button>
          </form>

          <!-- STEP 2: Enter OTP & New Password -->
          <form v-else @submit.prevent="handleResetPassword" class="space-y-4">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Email tài khoản</label>
              <input :value="email" type="email" readonly disabled
                class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm text-gray-500 cursor-not-allowed" />
            </div>

            <div class="space-y-1.5">
              <div class="flex justify-between items-center">
                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Mã xác thực OTP</label>
                <button type="button" @click="handleResendOtp" :disabled="resendCooldown > 0"
                  class="text-xs font-semibold text-[#286874] hover:underline disabled:text-gray-400 disabled:no-underline">
                  {{ resendCooldown > 0 ? `Gửi lại OTP (${resendCooldown}s)` : 'Gửi lại mã OTP' }}
                </button>
              </div>
              <input v-model="otp" type="text" placeholder="Gồm 6 chữ số" maxlength="6"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm tracking-widest text-center font-bold focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />
              <p v-if="otpError" class="text-xs text-red-500 font-medium pl-1 animate-fade-in">{{ otpError }}</p>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Mật khẩu mới</label>
              <div class="relative w-full flex items-center">
                <input v-model="password" :type="showPassword ? 'text' : 'password'" placeholder="••••••••"
                  class="w-full pl-4 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />
                <button type="button" @click="togglePassword"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 z-10 p-1 focus:outline-none flex items-center justify-center transition-colors">
                  <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M21 21l-18-18m18 18-3.321-3.321m3.321 3.321-1.42-1.417M17.5 17.5l-2.073-2.073M17.5 17.5A10.422 10.422 0 0 0 22.066 12c-1.292-4.338-5.31-7.5-10.066-7.5-1.956 0-3.794.53-5.378 1.458M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>
                </button>
              </div>
              <p v-if="passwordError" class="text-xs text-red-500 font-medium pl-1 animate-fade-in">{{ passwordError }}</p>
            </div>

            <div class="space-y-1.5">
              <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Xác nhận mật khẩu mới</label>
              <div class="relative w-full flex items-center">
                <input v-model="confirmPassword" :type="showConfirmPassword ? 'text' : 'password'" placeholder="••••••••"
                  class="w-full pl-4 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#286874] focus:bg-white transition-all" />
                <button type="button" @click="toggleConfirmPassword"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 z-10 p-1 focus:outline-none flex items-center justify-center transition-colors">
                  <svg v-if="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M21 21l-18-18m18 18-3.321-3.321m3.321 3.321-1.42-1.417M17.5 17.5l-2.073-2.073M17.5 17.5A10.422 10.422 0 0 0 22.066 12c-1.292-4.338-5.31-7.5-10.066-7.5-1.956 0-3.794.53-5.378 1.458M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  </svg>
                </button>
              </div>
              <p v-if="confirmPasswordError" class="text-xs text-red-500 font-medium pl-1 animate-fade-in">{{ confirmPasswordError }}</p>
            </div>

            <button type="submit"
              class="w-full mt-4 py-3.5 bg-[#286874] text-white font-bold rounded-xl shadow-lg shadow-cyan-900/10 hover:bg-[#1e4e57] hover:shadow-xl transition-all duration-200 text-sm">
              Xác nhận đổi mật khẩu
            </button>
            
            <button type="button" @click="step = 1"
              class="w-full py-2.5 text-[#286874] border border-[#286874] hover:bg-teal-50 font-bold rounded-xl transition-all duration-200 text-sm">
              Quay lại nhập Email
            </button>
          </form>

          <div class="mt-8 text-center text-sm text-gray-600">
            Quay lại
            <button type="button" @click="switchToLogin" class="text-[#286874] font-bold hover:underline ml-1 focus:outline-none">
              Đăng nhập
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onUnmounted } from 'vue'
import LoadingOverlay from '@/components/Common/LoadingOverlay.vue'

const { isForgotPasswordOpen, closeForgotPassword, switchToLogin } = useAuthModal()
const { showToast } = useToast()

const step = ref(1)
const email = ref('')
const otp = ref('')
const password = ref('')
const confirmPassword = ref('')

const emailError = ref('')
const otpError = ref('')
const passwordError = ref('')
const confirmPasswordError = ref('')

const showPassword = ref(false)
const showConfirmPassword = ref(false)
const isLoading = ref(false)
const loadingText = ref('')

const resendCooldown = ref(0)
let timer = null

const togglePassword = () => { showPassword.value = !showPassword.value }
const toggleConfirmPassword = () => { showConfirmPassword.value = !showConfirmPassword.value }

const validateEmail = () => {
  emailError.value = ''
  if (!email.value) {
    emailError.value = 'Email không được để trống.'
    return false
  }
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!emailRegex.test(email.value)) {
    emailError.value = 'Email không đúng định dạng.'
    return false
  }
  return true
}

const validateStep2 = () => {
  otpError.value = ''
  passwordError.value = ''
  confirmPasswordError.value = ''
  
  let isValid = true
  if (!otp.value) {
    otpError.value = 'Mã OTP không được để trống.'
    isValid = false
  } else if (otp.value.length !== 6) {
    otpError.value = 'Mã OTP phải gồm 6 chữ số.'
    isValid = false
  }
  
  if (!password.value) {
    passwordError.value = 'Mật khẩu mới không được để trống.'
    isValid = false
  } else if (password.value.length < 6) {
    passwordError.value = 'Mật khẩu phải có nhất 6 ký tự.'
    isValid = false
  } else if (password.value.length > 16) {
    passwordError.value = 'Mật khẩu tối đa là 16 ký tự.'
    isValid = false
  }
  
  if (!confirmPassword.value) {
    confirmPasswordError.value = 'Vui lòng xác nhận mật khẩu mới.'
    isValid = false
  } else if (confirmPassword.value !== password.value) {
    confirmPasswordError.value = 'Mật khẩu xác nhận không trùng khớp.'
    isValid = false
  }
  
  return isValid
}

const startCooldown = () => {
  resendCooldown.value = 60
  clearInterval(timer)
  timer = setInterval(() => {
    if (resendCooldown.value > 0) {
      resendCooldown.value--
    } else {
      clearInterval(timer)
    }
  }, 1000)
}

const getAuthService = async () => {
  const { authService } = await import("~/services/auth.service")
  return authService
}

const handleRequestOtp = async () => {
  if (!validateEmail()) return
  
  isLoading.value = true
  loadingText.value = 'Đang gửi mã OTP...'
  try {
    const authService = await getAuthService()
    const res = await authService.forgotPasswordApi({ email: email.value })
    if (res && res.success) {
      showToast(res.message || 'Mã OTP đã được gửi thành công.', 'success')
      step.value = 2
      startCooldown()
    } else {
      showToast(res?.message || 'Có lỗi xảy ra.', 'error')
    }
  } catch (err) {
    console.error(err)
    const errMsg = err.response?._data?.message || err.response?._data?.error || 'Không thể kết nối máy chủ.'
    if (err.response?._data?.errors?.email) {
      emailError.value = err.response._data.errors.email[0]
    } else {
      showToast(errMsg, 'error')
    }
  } finally {
    isLoading.value = false
  }
}

const handleResendOtp = async () => {
  if (resendCooldown.value > 0) return
  isLoading.value = true
  loadingText.value = 'Đang gửi lại mã OTP...'
  try {
    const authService = await getAuthService()
    const res = await authService.forgotPasswordApi({ email: email.value })
    if (res && res.success) {
      showToast('Gửi lại mã OTP thành công. Vui lòng kiểm tra email.', 'success')
      startCooldown()
    } else {
      showToast(res?.message || 'Có lỗi xảy ra.', 'error')
    }
  } catch (err) {
    console.error(err)
    const errMsg = err.response?._data?.message || 'Không thể gửi lại OTP.'
    showToast(errMsg, 'error')
  } finally {
    isLoading.value = false
  }
}

const handleResetPassword = async () => {
  if (!validateStep2()) return
  
  isLoading.value = true
  loadingText.value = 'Đang đổi mật khẩu...'
  try {
    const authService = await getAuthService()
    const res = await authService.resetPasswordApi({
      email: email.value,
      token: otp.value,
      password: password.value,
      confirm_password: confirmPassword.value
    })
    
    if (res && res.success) {
      showToast(res.message || 'Đổi mật khẩu thành công.', 'success')
      closeModal()
      switchToLogin()
    } else {
      showToast(res?.message || 'Đổi mật khẩu thất bại.', 'error')
    }
  } catch (err) {
    console.error(err)
    const data = err.response?._data
    if (data?.errors) {
      if (data.errors.token) otpError.value = data.errors.token[0]
      if (data.errors.password) passwordError.value = data.errors.password[0]
      if (data.errors.confirm_password) confirmPasswordError.value = data.errors.confirm_password[0]
    } else {
      const errMsg = data?.message || data?.error || 'Đổi mật khẩu thất bại.'
      showToast(errMsg, 'error')
    }
  } finally {
    isLoading.value = false
  }
}

const closeModal = () => {
  email.value = ''
  otp.value = ''
  password.value = ''
  confirmPassword.value = ''
  emailError.value = ''
  otpError.value = ''
  passwordError.value = ''
  confirmPasswordError.value = ''
  step.value = 1
  closeForgotPassword()
}

watch(isForgotPasswordOpen, (isOpen) => {
  if (!isOpen) {
    clearInterval(timer)
  }
})

onUnmounted(() => {
  clearInterval(timer)
})
</script>
